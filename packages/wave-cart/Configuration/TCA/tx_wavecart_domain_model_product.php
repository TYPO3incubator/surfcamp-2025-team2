<?php

use TYPO3Incubator\WaveCart\Enum\ProductTypeEnum;

$ll = 'LLL:EXT:wave_cart/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'tx_wavecart_domain_model_product',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'name',
        'searchFields' => 'name, description',
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
    ],
    'columns' => [
        'name' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'description' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'image' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.image',
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
        'type' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => ProductTypeEnum::getTcaOptions(),
            ],
        ],
        'price' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.price',
            'config' => [
                'type' => 'input',
                'size' => 10,
            ],
        ],
        'tax_rate' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.tax_rate',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'minitems' => 1,
                'maxitems' => 1,
                'items' => TaxRateEnum::getTcaOptions(),
            ],
        ],
        'variants' => [
            'label' => $ll . 'tx_wavecart_domain_model_product.variants',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_wavecart_domain_model_product_variant',
                'foreign_field' => 'product',
                'maxitems' => 9999,
            ],
        ],
    ],
    'types' => [
        1 => [
            'showitem' => 'name, description, image, type, price, tax_rate, variants',
        ],
    ],
];
