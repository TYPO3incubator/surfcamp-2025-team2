<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;
use TYPO3Incubator\WaveCart\Dto\OrderDto;
use Psr\Http\Message\ResponseInterface;
use TYPO3Incubator\WaveCart\Dto\OrderItemDto;

class OrderController extends ActionController
{
    private ProductVariantRepository $productVariantRepository;

    public function __construct(
        ProductVariantRepository $productVariantRepository
    ) {
        $this->productVariantRepository = $productVariantRepository;
    }

    public function cartAction(): ResponseInterface
    {
        $cartIds = ['1', '4', '2'];
        $orderDto = $this->createOrderDto($cartIds);

        $this->view->assign('order', $orderDto);

        return $this->htmlResponse();
    }


    public function addCustomerDataAction(?OrderDto $order=null): ResponseInterface
    {
        $this->view->assign('order', $order);
        return $this->htmlResponse();
    }

    public function summaryAddPaymentMethodAction(?OrderDto $order=null): ResponseInterface
    {
        $totalPrice = $order->calculateTotalPrice();

        $this->view->assign('order', $order);
        return $this->htmlResponse();
    }

    private function createOrderDto(array $variantIds): OrderDto
    {
        $orderDto = new OrderDto();
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

            $newCartItem = new OrderItemDto();
            $newCartItem->setName($variant->getName());
            $newCartItem->setAvailableAmount($variant->getAmount());
            $newCartItem->setSelectedAmount(1);
            $newCartItem->setSize($variant->getSize());
            $newCartItem->setImage($product->getImage());
            $newCartItem->setPrice($product->getPrice());

            $cartItems[] = $newCartItem;
        }

        $orderDto->setOrderItems($cartItems);

        return $orderDto;
    }
}
