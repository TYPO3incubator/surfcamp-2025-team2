<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum DiscountTypeEnum: int
{
    use Base;

    case relative = 0;
    case absolute = 1;

    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:discountTypeEnum.';
    }
}
