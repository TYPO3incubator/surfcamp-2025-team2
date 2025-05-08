<?php

namespace TYPO3Incubator\WaveCart\Domain\Model;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Order extends AbstractEntity
{
    protected string $customerLastname = '';
    protected string $customerFirstname = '';
    protected string $customerAddress = '';
    protected string $customerZip = '';
    protected string $customerCity = '';
    protected string $customerEmail = '';
    protected int $status = 0;
    protected int $paymentMethod = 0;
    protected int $assignee = 0;
    protected float $totalPrice = 0;
    protected ?FileReference $invoice = null;
    protected ?ObjectStorage $orderItems = null;
    protected string $discountCode = '';
    protected float $discountValue = 0;

    public function __construct()
    {
        $this->orderItems = new ObjectStorage();
    }

    public function getCustomerLastname(): string
    {
        return $this->customerLastname;
    }

    public function setCustomerLastname(string $customerLastname): void
    {
        $this->customerLastname = $customerLastname;
    }

    public function getCustomerFirstname(): string
    {
        return $this->customerFirstname;
    }

    public function setCustomerFirstname(string $customerFirstname): void
    {
        $this->customerFirstname = $customerFirstname;
    }

    public function getCustomerAddress(): string
    {
        return $this->customerAddress;
    }

    public function setCustomerAddress(string $customerAddress): void
    {
        $this->customerAddress = $customerAddress;
    }

    public function getCustomerZip(): string
    {
        return $this->customerZip;
    }

    public function setCustomerZip(string $customerZip): void
    {
        $this->customerZip = $customerZip;
    }

    public function getCustomerCity(): string
    {
        return $this->customerCity;
    }

    public function setCustomerCity(string $customerCity): void
    {
        $this->customerCity = $customerCity;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): void
    {
        $this->customerEmail = $customerEmail;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getPaymentMethod(): int
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(int $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getAssignee(): int
    {
        return $this->assignee;
    }

    public function setAssignee(int $assignee): void
    {
        $this->assignee = $assignee;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function addOrderItem(OrderItem $orderItem): void
    {
        $this->orderItems?->attach($orderItem);
    }

    /**
     * Remove a post from this blog
     */
    public function removeOrderItem(OrderItem $orderItemToRemove): void
    {
        $this->orderItems?->detach($orderItemToRemove);
    }

    /**
     * Returns all posts in this blog
     *
     * @return ObjectStorage<OrderItem>
     */
    public function getOrderItems(): ObjectStorage
    {
        return $this->orderItems;
    }

    /**
     * @param ObjectStorage<OrderItem> $orderItems
     */
    public function setOrderItems(ObjectStorage $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    public function getInvoice(): FileReference
    {
        return $this->invoice;
    }

    public function setInvoice(FileReference $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function getDiscountCode(): string
    {
        return $this->discountCode;
    }

    public function setDiscountCode(string $discountCode): void
    {
        $this->discountCode = $discountCode;
    }

    public function getDiscountValue(): float
    {
        return $this->discountValue;
    }

    public function setDiscountValue(float $discountValue): void
    {
        $this->discountValue = $discountValue;
    }
}
