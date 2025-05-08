<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3Incubator\WaveCart\Domain\Model\Order;
use TYPO3Incubator\WaveCart\Domain\Model\OrderItem;
use TYPO3Incubator\WaveCart\Domain\Repository\OrderRepository;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;

class OrderController extends ActionController
{
    private ProductVariantRepository $productVariantRepository;
    private OrderRepository $orderRepository;
    private PersistenceManager $persistenceManager;

    public function __construct(
        ProductVariantRepository $productVariantRepository,
        OrderRepository $orderRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->productVariantRepository = $productVariantRepository;
        $this->orderRepository = $orderRepository;
        $this->persistenceManager = $persistenceManager;
    }

    public function cartAction(): ResponseInterface
    {
        $cartIds = ['1', '2', '3', '4'];
        $order = $this->createOrder($cartIds);
        $this->orderRepository->add($order);
        $this->persistenceManager->persistAll();
        $this->view->assign('order', $order);

        return $this->htmlResponse();
    }

    public function addCustomerDataAction(?Order $order = null): ResponseInterface
    {
        $this->orderRepository->update($order);
        $this->persistenceManager->persistAll();
        $this->view->assign('order', $order);

        return $this->htmlResponse();
    }

    public function summaryAndPaymentMethodAction(?Order $order = null): ResponseInterface
    {
        $totalPrice = $order->calculateTotalPrice();
        $order->setTotalPrice($totalPrice);
        $this->orderRepository->update($order);
        $this->persistenceManager->persistAll();
        $this->view->assign('order', $order);

        return $this->htmlResponse();
    }

    public function submitAction(?Order $order = null): ResponseInterface
    {
        $this->updateStock($order);
        $this->sendOrderMails($order);

        $this->view->assign('order', $order);
        return $this->htmlResponse();
    }

    private function sendOrderMails(Order $order): void
    {
        $mailer = new FluidEmail();

        /** @var Site $site */
        $site = $this->request->getAttribute('site');
        $fromAddress = $site->getSettings()->get('waveCart.mailFromAddress');
        $fromSubject = $site->getSettings()->get('waveCart.mailFromSubject');
        $receiverAddress = $site->getSettings()->get('waveCart.mailReceiverAddress');
        $receiverSubject = $site->getSettings()->get('waveCart.mailReceiverSubject');
        $senderEmail = $order->getCustomerEmail();

        $emailToSender = $mailer
            ->to($senderEmail)
            ->from($fromAddress)
            ->subject($fromSubject)
            ->format('html')
            ->assignMultiple([
                'order' => $order,
            ])
            ->setTemplate('Sender');

        GeneralUtility::makeInstance(MailerInterface::class)->send($emailToSender);

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

    private function createOrder(array $variantIds): Order
    {
        $order = new Order();
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

            $newOrderItem = new OrderItem();
            $newOrderItem->setName($product->getName());
            $newOrderItem->setType($product->getType());
            $newOrderItem->setAmount(1);
            $newOrderItem->setSize($variant->getSize());
            $newOrderItem->setImage($product->getImage());
            $newOrderItem->setPrice($product->getPrice());
            $newOrderItem->setVariantId($variant->getUid());

            $order->addOrderItem($newOrderItem);
        }

        return $order;
    }
}
