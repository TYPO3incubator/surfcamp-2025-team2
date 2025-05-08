<?php

use TYPO3Incubator\WaveCart\Enum\DiscountTypeEnum;

$ll = 'LLL:EXT:wave_cart/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wavecart_domain_model_discountcode',
        'label' => 'code',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'starttime' => 'starttime',
        'endtime' => 'endtime',
        'delete' => 'deleted',
        'enablecolumns' => [
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'iconfile' => 'EXT:wave_cart/Resources/Public/Icons/order.svg',
    ],
    'types' => [
        '1' => ['showitem' => 'code, type, discount,  starttime, endtime, has_redeem_maximum, current_redeem_amount'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'code' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_discountcode.code',
            'config' => [
                'type' => 'input',
                'fieldControl' => [
                    'passwordGenerator' => [
                        'renderType' => 'passwordGenerator',
                        'options' => [
                            'title' => 'Creates a random discount code'
                        ],
                    ],
                ],
                'required' => true,
                'eval' => 'unique'
            ],
        ],
        'type' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_discountcode.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => DiscountTypeEnum::getTcaOptions(),
                'required' => true
            ]
        ],
        'discount' => [
            'exclude' => 0,
            'label' => $ll . 'tx_wavecart_domain_model_discountcode.discount',
            'config' => [
                'type' => 'number',
                'eval' => 'trim',
                'format' => 'decimal',
                'required' => true
            ],
        ],
        'has_redeem_maximum' => [
            'exclude' => 0,
            'onChange' => 'reload',
            'label' => $ll . 'tx_wavecart_domain_model_discountcode.has_redeem_maximum',
            'config' => [
                'type' => 'check',
                'required' => true
            ],
        ],
        'current_redeem_amount' => [
            'exclude' => 0,
            'displayCond' => 'FIELD:has_redeem_maximum:REQ:true',
            'label' => $ll . 'tx_wavecart_domain_model_discountcode.current_redeem_amount',
            'config' => [
                'type' => 'number',
                'required' => true
            ]
        ]
    ],
];
