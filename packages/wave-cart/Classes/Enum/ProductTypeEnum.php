<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum ProductTypeEnum: int
{
    use Base;

    case tShirt = 0;
    case cap = 1;
    case sweater = 2;

    case stuffedAnimal = 3;


    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:productTypeEnum.';
    }
}
