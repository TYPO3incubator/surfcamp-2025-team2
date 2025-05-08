<?php
namespace TYPO3Incubator\WaveCart\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class DiscountCode extends AbstractEntity
{
    protected string $code;
    protected int $type;
    protected float $discount;
    protected bool $hasRedeemMaximum;
    protected int $currentRedeemAmount;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    public function isHasRedeemMaximum(): bool
    {
        return $this->hasRedeemMaximum;
    }

    public function setHasRedeemMaximum(bool $hasRedeemMaximum): void
    {
        $this->hasRedeemMaximum = $hasRedeemMaximum;
    }

    public function getCurrentRedeemAmount(): int
    {
        return $this->currentRedeemAmount;
    }

    public function setCurrentRedeemAmount(int $currentRedeemAmount): void
    {
        $this->currentRedeemAmount = $currentRedeemAmount;
    }
}
