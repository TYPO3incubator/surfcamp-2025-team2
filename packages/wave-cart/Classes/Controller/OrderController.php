<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3Incubator\WaveCart\Domain\Model\Order;
use TYPO3Incubator\WaveCart\Domain\Model\OrderItem;
use TYPO3Incubator\WaveCart\Domain\Repository\OrderRepository;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;
use TYPO3Incubator\WaveCart\Dto\OrderDto;
use Psr\Http\Message\ResponseInterface;

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

        $this->view->assign('order', $order);
        return $this->htmlResponse();
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
