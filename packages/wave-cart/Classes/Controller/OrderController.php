<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductRepository;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductVariantRepository;
use Psr\Http\Message\ResponseInterface;

class OrderController extends ActionController
{
    private ProductRepository $productRepository;
    private ProductVariantRepository $productVariantRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductVariantRepository $productVariantRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }


    /**
     * Handles the cart action by retrieving the cart data from the session, preparing the cart items,
     * and assigning them to the view for rendering.
     *
     * @return ResponseInterface The rendered HTML response containing the cart data.
     */
    public function cartAction(): ResponseInterface
    {
        //TODO get the cart from somewhere
        $cart = [
            'variant_id' => '1',
            'variant_id' => '4',
            'variant_id' => '6'
        ];
        $cartItems = $this->prepareCartItems($cart);

        $this->view->assignMultiple([
            'cartItems' => $cartItems,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Prepares the cart items by retrieving product and variant details.
     *
     * @param array $cart The cart data containing product and variant identifiers.
     * @return array The prepared cart items with associated product and variant details.
     */
    private function prepareCartItems(array $cart): array
    {
        $cartItems = [];

        foreach ($cart as $key => $item) {
            $product = $this->productRepository->findByIdentifier($item['productId']);
            $variant = $item['variantId'] ? $this->productVariantRepository->findByIdentifier($item['variantId']) : null;

            $cartItems[] = [
                'key' => $key,
                'product' => $product,
                'variant' => $variant,
                'variant_amount_available' => $variant->getAmount(),
                'product_price' => $product->getPrice(),
                'product_name' => $product->getPrice(),
            ];
        }

        return $cartItems;
    }
}
