<?php

use TYPO3Incubator\WaveCart\Enum\OrderStatusEnum;
use TYPO3Incubator\WaveCart\Enum\PaymentMethodEnum;

$ll = 'LLL:EXT:wave_cart/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wavecart_domain_model_order',
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
        '1' => ['showitem' => 'customer_lastname, customer_firstname, customer_address, customer_zip, customer_city, customer_email, status, payment_method, assignee, total_price, order_items, discount_code, discount_value, invoice'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'customer_lastname' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.customer_lastname',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_firstname' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.customer_firstname',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_address' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.customer_address',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_zip' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.customer_zip',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_city' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.customer_city',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'customer_email' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.customer_email',
            'config' => [
                'type' => 'email',
                'eval' => 'trim'
            ],
        ],
        'status' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => OrderStatusEnum::getTcaOptions(),
            ],
        ],
        'payment_method' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.payment_method',
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
            'label' => $ll . 'tx_wavecart_domain_model_order.discount_code',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'discount_value' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.discount_value',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ],
        ],
        'assignee' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.assignee',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'None',
                        'value' => 0,
                    ],
                ],
                'foreign_table' => 'be_users',
            ],
        ],
        'total_price' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.total_price',
            'config' => [
                'type' => 'number',
                'eval' => 'trim',
                'format' => 'decimal'
            ],
        ],
        'order_items' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.order_items',
            'config' => [
                'type' => 'inline',
                'minitems' => 1,
                'foreign_table' => 'tx_wavecart_domain_model_orderitem',
                'foreign_field' => 'order',
            ],
        ],
        'invoice' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_order.invoice',
            'config' => [
                'type' => 'file',
                'appearance' => [
                    'collapseAll' => true,
                    'useSortable' => false,
                    'enabledControls' => [
                        'hide' => false,
                    ],
                ],
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
    ],
];
