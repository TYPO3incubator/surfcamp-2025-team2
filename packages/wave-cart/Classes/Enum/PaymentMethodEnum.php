<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum PaymentMethodEnum: int
{
    use Base;

    case prepayment = 0;

    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:paymentMethodEnum.';
    }
}
