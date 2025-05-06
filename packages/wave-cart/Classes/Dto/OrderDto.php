<?php
namespace TYPO3Incubator\WaveCart\Dto;

use TYPO3Incubator\WaveCart\Domain\Model\Product;

class OrderDto
{
    protected string $customerLastname;
    protected string $customerFirstname;
    protected string $customerAddress;
    protected string $customerZip;
    protected string $customerCity;
    protected string $customerEmail;
    protected int $paymentMethod;
    protected float $totalPrice;

    protected array $orderItems;

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

    public function getPaymentMethod(): int
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(int $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function setOrderItems(array $orderItems): void
    {
        $this->orderItems = $orderItems;
    }
}
