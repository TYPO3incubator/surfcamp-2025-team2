<?php

namespace TYPO3Incubator\WaveCart\Tab;

class TabCollection extends \ArrayObject
{

    protected array $contents = [];

    public function addEntity(Tab $tab): void
    {
        $this->contents[] = $tab;
    }

    public function getContents(): iterable
    {
        return $this->contents;
    }
}
