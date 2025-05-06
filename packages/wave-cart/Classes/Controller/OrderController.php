<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;
use TYPO3Incubator\WaveCart\Dto\OrderDto;
use Psr\Http\Message\ResponseInterface;

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
        $cartIds = ['1', '4', '6'];
        $orderDto = $this->createOrderDto($cartIds);

        $this->view->assign('order', $orderDto);

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

            $cartItems[] = [
                'name' => $variant->getName(),
                'availableAmount' => $variant->getAmount(),
                'selectedAmount' => 1,
                'amount' => $variant->getAmount(),
                'size' => $variant->getSize(),
                'price' => $product->getPrice(),
            ];
        }

        $orderDto->setOrderItems($cartItems);

        return $orderDto;
    }
}
