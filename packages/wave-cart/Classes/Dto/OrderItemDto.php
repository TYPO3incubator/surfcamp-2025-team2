<?php
namespace TYPO3Incubator\WaveCart\Dto;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3Incubator\WaveCart\Domain\Model\Product;

class OrderItemDto
{
    protected string $name = '';
    protected int $availableAmount = 0;
    protected int $selectedAmount = 1;
    protected string $size = '';
    protected ?FileReference $image = null;
    protected float $price = 0;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAvailableAmount(): int
    {
        return $this->availableAmount;
    }

    public function setAvailableAmount(int $availableAmount): void
    {
        $this->availableAmount = $availableAmount;
    }

    public function getSelectedAmount(): int
    {
        return $this->selectedAmount;
    }

    public function setSelectedAmount(int $selectedAmount): void
    {
        $this->selectedAmount = $selectedAmount;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function setImage(?FileReference $image): void
    {
        $this->image = $image;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
