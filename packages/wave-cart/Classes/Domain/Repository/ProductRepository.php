<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepository extends Repository
{
    public function initializeObject(): void
    {
        if ($this->defaultQuerySettings === null) {
            $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        }
        $this->defaultQuerySettings
            ->setRespectStoragePage(false);
    }
}
