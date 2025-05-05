<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Base;

enum OrderStatusEnum: int
{
    use Base;

    case new = 0;
    case paid = 1;
    case inProgress = 2;

    case shipped = 3;

    case returned = 4;

    protected static function getLanguageFilePath(): string
    {
        return 'LLL:EXT:wave_cart/Resources/Private/Language/locallang.xlf:orderStatusEnum.';
    }
}
