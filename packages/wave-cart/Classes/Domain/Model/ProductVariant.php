<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Represents a variant of a product with distinct properties such as name, size, and amount.
 */
class ProductVariant extends AbstractEntity
{
    protected string $name = '';
    protected string $size = '';
    protected int $amount = 0;
    protected Product $product;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
}
