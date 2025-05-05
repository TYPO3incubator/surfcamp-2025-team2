<?php

namespace TYPO3Incubator\WaveCart\Provider;

use Psr\Log\LoggerInterface;
use TYPO3Incubator\WaveCart\Domain\Repository\OrderRepository;

readonly class StoragePageProvider
{
    public function __construct(
        private OrderRepository $orderRepository,
        private LoggerInterface $logger
    ) {
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getStoragePage(): ?array
    {
        $result = $this->orderRepository->getAllOrders();

        try {
            return $result->fetchAssociative();
        } catch (\Doctrine\DBAL\Exception $e) {
            $this->logger->error('could not fetch storage pages', ['exception' => $e]);
            return null;
        }
    }
}
