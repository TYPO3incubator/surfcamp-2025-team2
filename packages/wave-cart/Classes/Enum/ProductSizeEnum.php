<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum ProductSizeEnum: string
{
    use Base;
    use GroupTCAOptions;

    case general_unisize = 'unisize';

    case tShirt_s = 'S';
    case tShirt_m = 'M';
    case tShirt_l = 'L';
    case tShirt_xl = 'XL';
    case tShirt_xxl = 'XXL';

    case sweater_s = 'S';
    case sweater_m = 'M';
    case sweater_l = 'L';
    case sweater_xl = 'XL';


    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:sizeEnum.';
    }
}
