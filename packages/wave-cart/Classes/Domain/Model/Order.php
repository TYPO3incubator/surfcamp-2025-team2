<?php
namespace TYPO3Incubator\WaveCart\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Order extends AbstractEntity
{
    protected string $customerLastname;
    protected string $customerFirstname;
    protected string $customerAddress;
    protected string $customerZip;
    protected string $customerCity;
    protected string $customerEmail;
    protected int $status;
    protected int $paymentMethod;
    protected int $assignee;
    protected float $totalPrice;
}
