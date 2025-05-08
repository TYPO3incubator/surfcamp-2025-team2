<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum TaxRateEnum: int
{
    use Base;

    case germany_zero = 0;
    case germany_reduced = 7;
    case germany_full = 19;


    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:taxRateEnum.';
    }
}
