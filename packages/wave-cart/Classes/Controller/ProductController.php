<?php

namespace TYPO3Incubator\WaveCart\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\WaveCart\Domain\Model\Product;
use TYPO3Incubator\WaveCart\Domain\Repository\ProductRepository;

class ProductController extends ActionController
{
    public function __construct(
        protected readonly ProductRepository $productRepository
    )
    {
    }

    /**
     * Index Action
     *
     * @return string
     */
    public function listAction()
    {
        $products = $this->productRepository->findAll();
        $this->view->assign('products', $products);
        return $this->view->render();
    }

    public function detailAction(Product $product)
    {
        $this->view->assign('product', $product);
    }
}
