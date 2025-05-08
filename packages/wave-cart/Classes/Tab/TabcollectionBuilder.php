<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Tab;

use TYPO3Incubator\WaveCart\Tab\TabCollection;
use TYPO3Incubator\WaveCart\Tab\TabFactory;

class TabcollectionBuilder
{

    public function __construct(
        private TabFactory $tabFactory,
        private Tabcollection $tabcollection,
        private string $title = '',
        private array $content = [],
        private bool $active = false,
        private int $position = 10,
    ) {
    }

    public function build(iterable $collection): iterable
    {
        foreach ($collection as $tab) {
            $tab =  $this->tabFactory->create(
                $tab['title'],
                $tab['content'],
                $tab['active'],
                $tab['position']
            );
            $this->tabcollection->addEntity(
              $tab
            );
        }

        return $this->tabcollection->getContents();
    }
}
