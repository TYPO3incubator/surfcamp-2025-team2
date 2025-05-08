<?php

use TYPO3Incubator\WaveCart\Enum\OrderStatusEnum;
use TYPO3Incubator\WaveCart\Enum\PaymentMethodEnum;

$ll = 'LLL:EXT:wave_cart/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wavecart_domain_model_cart',
        'label' => 'customer_lastname',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'iconfile' => 'EXT:wave_cart/Resources/Public/Icons/order.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'customer_lastname, customer_firstname, customer_address, customer_zip, customer_city, customer_email, status, payment_method, assignee, total_price, discount_code, discount_value, cart_items'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'customer_lastname' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.customer_lastname',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_firstname' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.customer_firstname',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_address' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.customer_address',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_zip' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.customer_zip',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_city' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.customer_city',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_email' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.customer_email',
            'config' => [
                'type' => 'email',
                'eval' => 'trim'
            ],
        ],
        'payment_method' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.payment_method',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => PaymentMethodEnum::getTcaOptions(),
            ],
        ],
        'discount_code' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.discount_code',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'discount_value' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.discount_value',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'total_price' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.total_price',
            'config' => [
                'type' => 'number',
                'eval' => 'trim',
                'format' => 'decimal'
            ],
        ],
        'cart_items' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_cart.cart_items',
            'config' => [
                'type' => 'inline',
                'minitems' => 1,
                'foreign_table' => 'tx_wavecart_domain_model_cartitem',
                'foreign_field' => 'cart',
            ],
        ]
    ],
];
