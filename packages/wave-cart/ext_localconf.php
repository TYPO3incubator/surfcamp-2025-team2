<?php

declare(strict_types=1);

use TYPO3Incubator\WaveCart\Controller\OrderController;
use TYPO3Incubator\WaveCart\Controller\ProductController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$extensionName = 'wave_cart';

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'WaveCart',
    'Product',
    [ProductController::class => 'list,detail'],
    [ProductController::class => 'list,detail'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

ExtensionUtility::configurePlugin(
    'WaveCart',
    'Order',
    [OrderController::class => 'cart,addCustomerData,summaryAndPaymentMethod,submit'],
    [OrderController::class => 'cart,addCustomerData,summaryAndPaymentMethod,submit'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'] = ['EXT:wave_cart/Resources/Private/Templates/Email/'];
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'] = ['EXT:wave_cart/Resources/Private/Layouts/'];
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'] = ['EXT:wave_cart/Resources/Private/Partials/'];

$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets'][$extensionName] = "EXT:{$extensionName}/Resources/Public/Css/";
