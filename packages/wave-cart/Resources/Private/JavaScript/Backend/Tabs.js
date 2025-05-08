document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const selectedTab = tab.getAttribute('data-tab');

        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        document.getElementById(selectedTab).classList.add('active');
    });
});

