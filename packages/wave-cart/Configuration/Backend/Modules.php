<?php

use TYPO3Incubator\WaveCart\Controller\Backend\OrderBackendController;

return [
    'wavecart' => [
        'labels' => ['title' => 'wavecart'],
        'iconIdentifier' => 'tx-wavecart-product',
        'position' => ['after' => 'web'],
    ],
    'wavecart_orders' => [
        'parent' => 'wavecart',
        'position' => ['top'],
        'access' => 'user',
        'labels' =>  ['title' => 'orders'],
        'iconIdentifier' => 'tx-wavecart-order',
        'path' => '/module/wavecart/orders',
        'routes' => [
            '_default' => [
                'target' => OrderBackendController::class . '::indexAction',
            ],
        ],
    ],
];

