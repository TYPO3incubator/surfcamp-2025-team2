<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Represents a product entity with attributes such as name, description, image, type, price, tax rate, and associated variants.
 *
 * This class provides getter and setter methods to manage the product properties, including an ObjectStorage to handle
 * the associated product variants.
 */
class Product extends AbstractEntity
{
    protected string $name = '';
    protected string $description = '';
    protected ?FileReference $image = null;
    protected int $type = 0;
    protected float $price = 0.00;
    protected int $taxRate = 0;

    /**
     * @var ObjectStorage<ProductVariant>
     */
    protected ObjectStorage $variants;

    public function __construct()
    {
        $this->variants = new ObjectStorage();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function setImage(?FileReference $image): void
    {
        $this->image = $image;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getTaxRate(): int
    {
        return $this->taxRate;
    }

    public function setTaxRate(int $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getVariants(): ObjectStorage
    {
        return $this->variants;
    }

    public function setVariants(ObjectStorage $variants): void
    {
        $this->variants = $variants;
    }
}
