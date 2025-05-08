<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Tab;


use TYPO3Incubator\WaveCart\Tab\Tab;

/**
 * A factory class creating backend related ModuleTemplate view objects.
 */
#[Autoconfigure(public: true, shared: false)]
final class TabFactory
{
    public function create(string $title, string $content, bool $active, int $position): Tab
    {
        return new Tab(
            $title,
            $content,
            $active,
            $position
        );
    }
}
