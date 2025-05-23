<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(static function (): void {
    $pluginKey = ExtensionUtility::registerPlugin(
        'WaveCart',
        'Product',
        'Wave Card: Product',
    );
    $pluginKey = ExtensionUtility::registerPlugin(
        'WaveCart',
        'Order',
        'Wave Card: Order',
    );
})();
