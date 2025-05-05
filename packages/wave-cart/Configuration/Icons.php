<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-wavecart-product' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:wave_cart/Resources/Public/Icons/product.svg',
    ],
    'tx-wavecart-order' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:wave_cart/Resources/Public/Icons/order.svg',
    ]
];
