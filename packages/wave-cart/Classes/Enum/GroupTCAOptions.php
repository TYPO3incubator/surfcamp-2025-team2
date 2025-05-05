<?php

namespace TYPO3Incubator\WaveCart\Enum;
use Brosua\Enums\Translation;

trait GroupTCAOptions
{
    use Translation;

    public static function getGroupedTCAOptions(): array
    {
        $items = [];
        foreach (self::cases() as $case) {
            $identifier = self::getIdentifier($case);
            $items[] = [
                'label' => $case->getTranslationString(),
                'value' => $case->value,
                'group' => $identifier
            ];
        }
        return $items;
    }

    public static function getGroups(): array
    {
        $groupIdentifiers = [];
        $groups = [];
        foreach (self::cases() as $case) {
            if (!in_array(self::getIdentifier($case), $groupIdentifiers)) {
                $groupIdentifier = self::getIdentifier($case);
                $groups[$groupIdentifier] = self::getGroupTranslationString($groupIdentifier);
                $groupIdentifiers[] = self::getIdentifier($case);
            }
        }

        return $groups;
    }

    public static function getGroupTranslationString(string $groupIdentifier): string
    {
        return self::getLanguageFilePath() . $groupIdentifier;
    }

    protected static function getIdentifier(\UnitEnum $case): string
    {
        return substr($case->name, 0, strpos($case->name, '_'));
    }
}
