<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3Incubator\WaveCart\Dto\OrderDto;

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
        //TODO get the cart from somewhere else
        $cart = ['1', '4', '6'];
        $cartItems = $this->prepareCartItems($cart);

        $this->view->assignMultiple([
            'cartItems' => $cartItems,
        ]);

        return $this->htmlResponse();
    }

    public function addCustomerDataAction(OrderDto $order): ResponseInterface
    {
        $this->view->assign('order', $order);

        return $this->htmlResponse();
    }

    private function prepareCartItems(array $cart): array
    {
        $cartItems = [];

        foreach ($cart as $key => $variantUid) {
            $variant = $this->productVariantRepository->findByUid($variantUid);
            if (!$variant) {
                continue;
            }

            $product = $variant->getProduct();
            if (!$product) {
                continue;
            }

            $cartItems[] = [
                'key' => $key,
                'id' => $variant->getUid(),
                'name' => $variant->getName(),
                'amount' => $variant->getAmount(),
                'size' => $variant->getSize(),
                'price' => $product->getPrice(),
            ];
        }

        return $cartItems;
    }
}
