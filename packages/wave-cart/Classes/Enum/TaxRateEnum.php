<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum TaxRateEnum: int
{
    use Base;

    case none = 0;
    case reduced = 7;
    case standard = 19;

    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:taxRateEnum.';
    }
}
