<?php
namespace TYPO3Incubator\WaveCart\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Cart extends AbstractEntity
{
    protected string $customerLastname = '';
    protected string $customerFirstname = '';
    protected string $customerAddress = '';
    protected string $customerZip = '';
    protected string $customerCity = '';
    protected string $customerEmail = '';
    protected int $paymentMethod = 0;
    protected float $totalPrice = 0;

    protected string $discountCode = '';

    protected float $discountValue = 0;

    protected ?ObjectStorage $cartItems = null;

    public function __construct()
    {
        $this->cartItems = new ObjectStorage();
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

    public function addCartItem(CartItem $cartItem): void
    {
        $this->cartItems?->attach($cartItem);
    }

    public function removeCartItem(CartItem $cartItemToRemove): void
    {
        $this->cartItems?->detach($cartItemToRemove);
    }

    /**
     * @return ObjectStorage<CartItem>
     */
    public function getCartItems(): ObjectStorage
    {
        return $this->cartItems;
    }

    /**
     * @param ObjectStorage<CartItem> $cartItems
     */
    public function setCartItems(ObjectStorage $cartItems): void
    {
        $this->cartItems = $cartItems;
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

    public function calculateTotalPrice(float $discount = 0): float
    {
        $totalPrice = 0;
        foreach ($this->cartItems as $cartItem) {
            $totalPrice += $cartItem->getPrice() * $cartItem->getAmount();
        }

        if ($discount < 0) {
            $totalPrice += $discount;
        }

        $this->totalPrice = $totalPrice;

        return $totalPrice;
    }
}
