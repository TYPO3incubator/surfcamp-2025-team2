<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Domain\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Repository;

class OrderRepository extends Repository
{
    const TABLE_NAME = 'tx_wavecart_domain_model_order';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function getAllOrders(): Result
    {
        $queryBuilder = $this->getQueryBuilder(self::TABLE_NAME);

        return $queryBuilder->select('*')
            ->from(self::TABLE_NAME)
            ->executeQuery();
    }

    private function getQueryBuilder(string $table): QueryBuilder
    {
        return $this->connectionPool->getQueryBuilderForTable($table);
    }
}
