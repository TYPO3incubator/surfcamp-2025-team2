<?php

declare(strict_types=1);

use TYPO3Incubator\WaveCart\Controller\ProductController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
// extension name, matching the PHP namespaces (but without the vendor)
    'WaveCart',
    // arbitrary, but unique plugin name (not visible in the backend)
    'Product',
    // all actions
    [ProductController::class => 'list,detail'],
    // non-cacheable actions
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);
