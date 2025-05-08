<?php

namespace TYPO3Incubator\WaveCart\Domain\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class DiscountCodeRepository extends Repository
{
    public function initializeObject(): void
    {
        if ($this->defaultQuerySettings === null) {
            $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        }
        $this->defaultQuerySettings
            ->setRespectStoragePage(false);
    }

    const string TABLE_NAME = 'tx_wavecart_domain_model_discountcode';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {
        parent::__construct();
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->connectionPool->getQueryBuilderForTable(self::TABLE_NAME);
    }

    /**
     * @throws Exception
     */
    public function findByCode(string $code): array|bool
    {
        $queryBuilder = $this->getQueryBuilder();
        return $queryBuilder->select('code', 'type', 'discount', 'has_redeem_maximum', 'current_redeem_amount')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq(
                    'code',
                    $queryBuilder->createNamedParameter($code, Connection::PARAM_STR),
                )
            )
            ->executeQuery()->fetchAssociative();
    }
}
