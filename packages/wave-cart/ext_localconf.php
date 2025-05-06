<?php

declare(strict_types=1);

use TYPO3Incubator\WaveCart\Controller\OrderController;
use TYPO3Incubator\WaveCart\Controller\ProductController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'WaveCart',
    'Product',
    [ProductController::class => 'list,detail'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'WaveCart',
    'Order',
    [OrderController::class => 'cart,addCustomerData'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);
