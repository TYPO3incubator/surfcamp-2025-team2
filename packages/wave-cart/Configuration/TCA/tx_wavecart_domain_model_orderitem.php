<?php

use TYPO3Incubator\WaveCart\Enum\ProductTypeEnum;

$ll = 'LLL:EXT:wave_cart/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wavecart_domain_model_orderitem',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'iconfile' => 'EXT:fuxma_site/Resources/Public/Icons/Fux/fux-fux.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'name, typ, price, tax_tate, size, amount'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'name' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_orderitem.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'type' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_orderitem.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => ProductTypeEnum::getTcaOptions(),
            ],
        ],
        'price' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_orderitem.price',
            'config' => [
                'type' => 'number',
                'eval' => 'trim',
                'format' => 'decimal'
            ],
        ],
        'tax_rate' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_orderitem.tax_rate',
            'config' => [
                'type' => 'number',
                'eval' => 'trim'
            ],
        ],
        'size' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_orderitem.size',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'amount' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_orderitem.amount',
            'config' => [
                'type' => 'number',
                'eval' => 'trim'
            ],
        ],
    ],
];
