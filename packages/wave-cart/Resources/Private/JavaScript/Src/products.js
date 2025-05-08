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

        tiles.forEach(tile => {
            tile.style.display = '';
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

    if(variantOptions.length === 0) {
        addToCartButton.disabled = false;
    }

});


