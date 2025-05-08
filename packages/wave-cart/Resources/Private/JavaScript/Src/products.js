const types = new Set(
    Array.from(document.querySelectorAll('.tile'))
        .map(el => el.dataset.type)
);

const typeSelect = document.getElementById('type-select');

if(typeSelect) {
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'All Types';
    typeSelect.appendChild(defaultOption);

    types.forEach(type => {
        const option = document.createElement('option');
        option.value = type;
        option.textContent = type;
        typeSelect.appendChild(option);
    });
}

function updateSizeSelect() {
    const sizeSelect = document.getElementById('size-select');
    if(sizeSelect) {
        sizeSelect.innerHTML = '';

        const visibleTiles = Array.from(document.querySelectorAll('.tile'))
            .filter(tile => tile.style.display !== 'none');

        const sizes = new Set();

        visibleTiles.forEach(tile => {
            try {
                const variantSizes = JSON.parse(tile.dataset.variants || '[]');
                variantSizes.forEach(size => sizes.add(size));
            } catch (e) {
                console.warn('Invalid size data:', tile.dataset.variants);
            }
        });

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'All Sizes';
        sizeSelect.appendChild(defaultOption);

        sizes.forEach(size => {
            const option = document.createElement('option');
            option.value = size;
            option.textContent = size;
            sizeSelect.appendChild(option);
        });
    }
}
updateSizeSelect()



// EventListener
if(typeSelect) {
    typeSelect.addEventListener('change', function () {
        const selectedType = this.value;
        const tiles = document.querySelectorAll('.tile');

        tiles.forEach(tile => {
            const tileType = tile.dataset.type;
            if (selectedType === '' || tileType === selectedType) {
                tile.style.display = ''; // Show
            } else {
                tile.style.display = 'none'; // Hide
            }
        });

        updateSizeSelect();
    });
}

const sortSelect = document.getElementById('sort-select');
const productList = document.getElementById('tile-container');

if(sortSelect) {
    sortSelect.addEventListener('change', function () {
        const tiles = Array.from(productList.querySelectorAll('.tile'));

        if (this.value === 'price-asc') {
            tiles.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
        } else if (this.value === 'price-desc') {
            tiles.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
        } else {
            return;
        }

        tiles.forEach(tile => productList.appendChild(tile));
    });
}

const sizeSelect = document.getElementById('size-select');

if (sizeSelect) {
    sizeSelect.addEventListener('change', function () {
        const selectedSize = this.value;
        const tiles = document.querySelectorAll('.tile');

        const selectedType = typeSelect ? typeSelect.value : '';

        tiles.forEach(tile => {
            const tileType = tile.dataset.type || '';
            tile.style.display = selectedType === '' || tileType === selectedType ? '' : 'none';
        });

        tiles.forEach(tile => {
            const variantSizes = JSON.parse(tile.dataset.variants || '[]');

            // Check if selectedSize is '' (All Sizes) or present in variantSizes
            const matchesSize = selectedSize === '' || variantSizes.includes(selectedSize);

            // Also check if the tile is already hidden by type filter
            const isHiddenByType = tile.style.display === 'none';

            if (!isHiddenByType) {
                // Apply size filter
                tile.style.display = matchesSize ? '' : 'none';
            }
        });
    });
}


document.addEventListener('DOMContentLoaded', function () {
    const variantOptions = document.querySelectorAll('.variant-option');
    const addToCartButton = document.getElementById('addToCart');

    variantOptions.forEach(option => {
        option.addEventListener('click', function () {
            // Remove active class from all
            variantOptions.forEach(o => o.classList.remove('active'));

            // Check amount from data attribute or class
            const isSoldOut = this.classList.contains('sold-out');

            if (isSoldOut) {
                addToCartButton.disabled = true;
            } else {
                addToCartButton.disabled = false;
                this.classList.add('active');
            }
        });
    });

    if(variantOptions.length === 1) {
        addToCartButton.disabled = false;
        variantOptions[0].classList.add('active');
    }

});

const addToCartButton = document.getElementById('addToCart');
if(addToCartButton) {
    addToCartButton.addEventListener('click', () => {
        addId(parseInt(document.querySelector('.variant-option.active').dataset.id))
        getCookie('cartCookie')
        document.getElementById('cartModal').style.display = 'flex';
    })
}

const closeModalButton = document.getElementById('closeModal');
console.log(closeModalButton)
if(closeModalButton) {
    closeModalButton.addEventListener('click', () => {
        closeModal();
    })
}
function closeModal() {
    document.getElementById('cartModal').style.display = 'none';
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
        return decodeURIComponent(parts.pop().split(';').shift());
    }
    return null;
}

function setCookie(name, value, days = 7) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
}

function addId(id) {
    const cookieData = getCookie('cartCookie');
    console.log('cookieData', cookieData);

    // FIX: Make sure it's always an array
    const ids = cookieData ? JSON.parse(cookieData) : [];
    console.log('ids (parsed)', ids);

    if (!ids.includes(id)) {
        ids.push(id);
        setCookie('cartCookie', JSON.stringify(ids));
        console.log('Updated cookie:', ids);
    }
}

function removeId(id) {
    const cookieData = getCookie('cartCookie');
    const ids = cookieData ? JSON.parse(cookieData) : [];

    const updatedIds = ids.filter(item => item !== id);
    setCookie('cartCookie', JSON.stringify(updatedIds));
    console.log('Removed ID, updated cookie:', updatedIds);
}

document.querySelectorAll('.cart-discount-control').forEach(control => {
    const redeemBtn = control.querySelector('.check-discount');
    const discountCouponInput = control.querySelector('.discount-code');
    const hiddenDiscountCouponInput = control.querySelector('.hidden-discount-code');
    const hiddenDiscountValueInput = control.querySelector('.hidden-discount-value');
    const redeemStatus = control.querySelector('.redeem-status');

    redeemBtn.addEventListener('click', () => {
        try {
            const response = fetch(`/api/discount?code=${discountCouponInput.value}`);
            response.then(async (response) => {
                const responseString = await response.text()
                const responseObject = await JSON.parse(responseString)

                if (response.status !== 200) {
                    redeemStatus.innerText = await responseObject.error
                    redeemStatus.classList.remove('redeem-status-success')
                    redeemStatus.classList.add('redeem-status-error')
                } else {
                    const discountType = await responseObject.type
                    const discount = await responseObject.discount


                    if (discountType === 'relative') {
                        const calculatedDiscount = - (calculateCartTotal() * (discount/100))
                        hiddenDiscountValueInput.value = calculatedDiscount
                        calculateCartTotal(calculatedDiscount)
                    } else {
                        const calculatedDiscount = - (discount)
                        hiddenDiscountValueInput.value = calculatedDiscount
                        calculateCartTotal(calculatedDiscount)
                    }

                    redeemStatus.classList.remove('redeem-status-error')
                    redeemStatus.classList.add('redeem-status-success')
                    hiddenDiscountCouponInput.value = discountCouponInput.value
                    discountCouponInput.value = ''

                    redeemStatus.innerText = 'Discount coupon added!'
                }
            })
        } catch (error) {
            redeemStatus.innerText = error.message
        }
    })
})

document.querySelectorAll('.amount-control').forEach(control => {
    const index = control.dataset.index;
    const decreaseBtn = control.querySelector('.decrease-btn');
    const increaseBtn = control.querySelector('.increase-btn');
    const amountInput = control.querySelector('.amount-number');

    if(decreaseBtn) {
        decreaseBtn.addEventListener('click', () => {
            let amount = parseInt(amountInput.value, 10) || 0;
            if (amount === 1) {
                removeId(parseInt(control.closest('.cart-item').dataset.id))
                control.closest('.cart-item').remove();
            } else if (amount > 1) {
                amount--;
                amountInput.value = amount;
                updateDecreaseButton(decreaseBtn, amount);
            }
            calculateCartTotal();
        });
        updateDecreaseButton(decreaseBtn, parseInt(amountInput.value, 10) || 0);
    }
    if(increaseBtn) {
        increaseBtn.addEventListener('click', () => {
            let amount = parseInt(amountInput.value, 10) || 0;
            amount++;
            amountInput.value = amount;
            updateDecreaseButton(decreaseBtn, amount);
            calculateCartTotal();
        });
    }

    function updateDecreaseButton(button, amount) {
        button.textContent = amount === 1 ? '🗑' : '\u00A0\u00A0\u00A0-';
    }
});

function calculateCartTotal(discount = 0) {
    let total = 0;

    const cartItems = document.querySelectorAll('.cart-item');

    cartItems.forEach(item => {
        const priceElement = item.querySelector('.item-price');
        const amountElement = item.querySelector('.amount-number');

        const price = parseFloat(priceElement.dataset.price);
        const amount = parseInt(amountElement.value, 10) || 0;

        total += price * amount;
    });

    if (discount !== 0 && discount < 0) {
        total += discount
    }

    const discountTotalElement = document.getElementById('discountTotal');
    if (discountTotalElement) {
        discountTotalElement.textContent = `Discount: ${discount.toFixed(2)}€`;
    }

    // Update the total display
    const totalElement = document.getElementById('cartTotal');
    if(totalElement) {
        totalElement.textContent = `Total (incl. tax): ${total.toFixed(2)}€`;
    }
    totalElement.textContent = `Total (incl. tax): ${total.toFixed(2)}€`;

    return total
}
calculateCartTotal();

