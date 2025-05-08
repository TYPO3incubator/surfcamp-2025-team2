<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3Incubator\WaveCart\Domain\Model\Cart;
use TYPO3Incubator\WaveCart\Domain\Model\CartItem;
use TYPO3Incubator\WaveCart\Domain\Model\Order;
use TYPO3Incubator\WaveCart\Domain\Model\OrderItem;
use TYPO3Incubator\WaveCart\Domain\Repository\CartItemRepository;
use TYPO3Incubator\WaveCart\Domain\Repository\CartRepository;
use TYPO3Incubator\WaveCart\Domain\Repository\OrderRepository;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;
use TYPO3Incubator\WaveCart\Enum\OrderStatusEnum;
use TYPO3Incubator\WaveCart\Service\GenerateInvoiceService;

class OrderController extends ActionController
{
    public function __construct(
        protected ProductVariantRepository $productVariantRepository,
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected OrderRepository $orderRepository,
        protected PersistenceManager $persistenceManager,
        protected GenerateInvoiceService $generateInvoiceService
    ) {
    }

    public function cartAction(): ResponseInterface
    {
        $cartIds = ['1', '2', '3', '4'];
        $cart = $this->createCart($cartIds);
        $this->cartRepository->add($cart);
        $this->persistenceManager->persistAll();
        $this->view->assign('cart', $cart);

        return $this->htmlResponse();
    }

    public function addCustomerDataAction(?Cart $cart = null): ResponseInterface
    {
        $this->cartRepository->update($cart);
        $this->persistenceManager->persistAll();
        $this->view->assign('cart', $cart);

        return $this->htmlResponse();
    }

    public function summaryAndPaymentMethodAction(?Cart $cart = null): ResponseInterface
    {
        if (!$this->isValidEmail($cart->getCustomerEmail())) {
            $this->view->assign('cart', $cart);

            return (new ForwardResponse('addCustomerData'))
                ->withControllerName('Order')
                ->withExtensionName('waveCart')
                ->withArguments([
                    'cart' => $cart,
                    'errormessage' => 'The provided email address is invalid. Please provide a valid email.'
                ]);
        }

        $totalPrice = $cart->calculateTotalPrice();
        $cart->setTotalPrice($totalPrice);
        $this->cartRepository->update($cart);
        $this->persistenceManager->persistAll();
        $this->view->assign('cart', $cart);

        return $this->htmlResponse();
    }

    public function submitAction(?Cart $cart = null): ResponseInterface
    {
        $order = $this->persistOrder($cart);
        $order = $this->generateInvoiceService->generateInvoicePdfAndAddToOrder($order, $this->request);
        $this->orderRepository->update($order);
        $this->persistenceManager->persistAll();

        $this->updateStock($order);
        $this->sendSenderOrderMails($order);
        $this->sendReceiverOrderMails($order);

        return $this->htmlResponse();
    }

    private function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function persistOrder(Cart $cart): Order
    {
        $settings = $this->request->getAttribute('site')->getSettings();
        $orderPid = $settings->get('waveCart.orderPid');
        $orderItemPid = $settings->get('waveCart.orderItemPid');

        $order = new Order();
        $order->setPid($orderPid);
        $order->setCustomerFirstname($cart->getCustomerFirstname());
        $order->setCustomerLastname($cart->getCustomerLastname());
        $order->setCustomerEmail($cart->getCustomerEmail());
        $order->setCustomerAddress($cart->getCustomerAddress());
        $order->setCustomerZip($cart->getCustomerZip());
        $order->setCustomerEmail($cart->getCustomerEmail());
        $order->setCustomerCity($cart->getCustomerCity());
        $order->setPaymentMethod($cart->getPaymentMethod());
        $order->setTotalPrice($cart->calculateTotalPrice());
        $order->setStatus(OrderStatusEnum::new->value);

        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setPid($orderItemPid);
            $orderItem->setName($cartItem->getName());
            $orderItem->setType($cartItem->getType());
            $orderItem->setTaxRate($cartItem->getTaxRate());
            $orderItem->setAmount($cartItem->getAmount());
            $orderItem->setSize($cartItem->getSize());
            $orderItem->setImage($cartItem->getImage());
            $orderItem->setPrice($cartItem->getPrice());
            $orderItem->setVariantId($cartItem->getVariantId());

            $order->addOrderItem($orderItem);
            $this->cartItemRepository->remove($cartItem);
        }

        $this->orderRepository->add($order);
        $this->cartRepository->remove($cart);
        $this->persistenceManager->persistAll();

        return $order;
    }

    private function sendSenderOrderMails(Order $order): void
    {
        $mailer = new FluidEmail();

        $settings = $this->request->getAttribute('site')->getSettings();
        $fromAddress = $settings->get('waveCart.mailFromAddress');
        $fromSubject = $settings->get('waveCart.mailFromSubject');
        $senderAddress = $order->getCustomerEmail();

        $fileIdentifier = $order->getInvoice()->getOriginalResource()->getOriginalFile()->getIdentifier();
        $path = Environment::getPublicPath() . '/fileadmin' . $fileIdentifier;
        $emailToSender = $mailer
            ->to($senderAddress)
            ->from($fromAddress)
            ->subject($fromSubject)
            ->format('html')
            ->attachFromPath($path)
            ->assignMultiple([
                'order' => $order,
            ])
            ->setTemplate('Sender');

        GeneralUtility::makeInstance(MailerInterface::class)->send($emailToSender);
    }

    private function sendReceiverOrderMails(Order $order): void
    {
        $mailer = new FluidEmail();

        $settings = $this->request->getAttribute('site')->getSettings();
        $fromAddress = $settings->get('waveCart.mailFromAddress');
        $receiverAddress = $settings->get('waveCart.mailReceiverAddress');
        $receiverSubject = $settings->get('waveCart.mailReceiverSubject');

        $receiverEmail = $mailer
            ->to($receiverAddress)
            ->from($fromAddress)
            ->subject($receiverSubject)
            ->format('html')
            ->assignMultiple([
                'order' => $order,
            ])
            ->setTemplate('Receiver');

        GeneralUtility::makeInstance(MailerInterface::class)->send($receiverEmail);
    }


    private function updateStock(Order $order): void
    {
        foreach ($order->getOrderItems() as $orderItem) {
            $variantUid = $orderItem->getVariantId();
            $variant = $this->productVariantRepository->findByUid($variantUid);

            if ($variant) {
                $newAmount = $variant->getAmount() - $orderItem->getAmount();
                $variant->setAmount(max($newAmount, 0));

                $this->productVariantRepository->update($variant);
            }
        }

        $this->persistenceManager->persistAll();
    }

    private function createCart(array $variantIds): Cart
    {
        $settings = $this->request->getAttribute('site')->getSettings();
        $cartPid = $settings->get('waveCart.cartPid');
        $cartItemPid = $settings->get('waveCart.cartItemPid');

        $cart = new Cart();
        $cart->setPid($cartPid);
        $cartItems = [];

        foreach ($variantIds as $variantUid) {
            $variant = $this->productVariantRepository->findByUid($variantUid);
            if (!$variant) {
                continue;
            }

            $product = $variant->getProduct();
            if (!$product) {
                continue;
            }

            $newCartItem = new CartItem();
            $newCartItem->setPid($cartItemPid);
            $newCartItem->setName($product->getName());
            $newCartItem->setType($product->getType());
            $newCartItem->setAmount(1);
            $newCartItem->setSize($variant->getSize());
            $newCartItem->setImage($product->getImage());
            $newCartItem->setPrice($product->getPrice());
            $newCartItem->setVariantId($variant->getUid());

            $cart->addCartItem($newCartItem);
        }

        return $cart;
    }
}
