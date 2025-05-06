const types = new Set(
    Array.from(document.querySelectorAll('.tile'))
        .map(el => el.dataset.type)
);
