const types = new Set(
    Array.from(document.querySelectorAll('.tile'))
        .map(el => el.dataset.type)
);

const typeSelect = document.getElementById('type-select');

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

function updateSizeSelect() {
    const sizeSelect = document.getElementById('size-select');
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
updateSizeSelect()



// EventListener
document.getElementById('type-select').addEventListener('change', function () {
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
});

document.getElementById('type-select').addEventListener('change', function () {
    const selectedType = this.value;
    const tiles = document.querySelectorAll('.tile');

    tiles.forEach(tile => {
        const tileType = tile.dataset.type;
        if (selectedType === '' || tileType === selectedType) {
            tile.style.display = '';
        } else {
            tile.style.display = 'none';
        }
    });

    updateSizeSelect();
});

const sortSelect = document.getElementById('sort-select');
const productList = document.getElementById('tile-container');

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

