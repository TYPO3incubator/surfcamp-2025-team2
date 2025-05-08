<?php

namespace TYPO3Incubator\WaveCart\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\WaveCart\Domain\Model\Product;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductRepository;
use TYPO3Incubator\WaveCart\Enum\ProductTypeEnum;

class ProductController extends ActionController
{
    public function __construct(
        protected readonly ProductRepository $productRepository
    )
    {
    }

    public function listAction(): ResponseInterface
    {
        $products = $this->productRepository->findAll();
        $this->view->assign('products', $products);
        return $this->htmlResponse();
    }

    public function detailAction(Product $product): ResponseInterface
    {
        $cartPageId = $this->request->getAttribute('site')->getSettings()->get('waveCart.cartPageId');
        $type = ProductTypeEnum::from($product->getType())->getLabel();

        $this->view->assign('cartPageId', $cartPageId);
        $this->view->assign('product', $product);
        $this->view->assign('type', $type);
        return $this->htmlResponse();
    }
}
