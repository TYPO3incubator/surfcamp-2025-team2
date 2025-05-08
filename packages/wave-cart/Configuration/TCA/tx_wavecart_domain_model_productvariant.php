<?php

use TYPO3Incubator\WaveCart\Enum\PaymentMethodEnum;
use TYPO3Incubator\WaveCart\Enum\ProductSizeEnum;

$ll = 'LLL:EXT:wave_cart/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wavecart_domain_model_product_variant',
        'label' => 'size',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'size',
        'searchFields' => 'size',
        'enablecolumns' => [
            'fe_group' => 'fe_group',
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'languageField' => 'sys_language_uid',
        'translationSource' => 'l10n_source',
        'iconfile' => 'EXT:wave_cart/Resources/Public/Icons/product.svg',
    ],
    'columns' => [
        'size' => [
            'label' => $ll . 'tx_wavecart_domain_model_product_variant.size',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => ProductSizeEnum::getGroupedTCAOptions(),
                'itemGroups' => ProductSizeEnum::getGroups(),
            ],
        ],
        'amount' => [
            'label' => $ll . 'tx_wavecart_domain_model_product_variant.amount',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'int',
            ],
        ],
        'product' => [
            'label' => $ll . 'tx_wavecart_domain_model_product_variant.product',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'name, size, amount'],
    ],
];
