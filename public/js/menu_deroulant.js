document.addEventListener('DOMContentLoaded', function () {
    const universeTrigger = document.getElementById('universe-trigger');
    const universeMenu = document.getElementById('universe-menu');
    const universeDropdown = document.getElementById('universe-dropdown');

    let hideTimeout;

    if (universeTrigger && universeMenu) {
        universeDropdown.addEventListener('mouseenter', function () {
            clearTimeout(hideTimeout); // Empêche de cacher si on y revient vite
            universeMenu.classList.remove('hidden');
        });

        universeDropdown.addEventListener('mouseleave', function () {
            hideTimeout = setTimeout(() => {
                universeMenu.classList.add('hidden');
            }, 200); // 200ms de marge
        });

        universeTrigger.addEventListener('click', function (e) {
            e.preventDefault();
        });
    }
});