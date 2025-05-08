<?php

use TYPO3Incubator\WaveCart\Middleware\DiscountMiddleware;

return [
    'frontend' => [
        'typo3incubator/wave-cart/discount' => [
            'target' => DiscountMiddleware::class,
            'before' => [
                'typo3/cms-frontend/site',
            ]
        ],
    ]
];
