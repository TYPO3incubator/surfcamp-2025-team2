<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum TaxRateEnum: int
{
    use Base;

    case germany_zero = 0;
    case germany_reduced = 9;
    case germany_full = 17;


    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:taxRateEnum.';
    }
}
