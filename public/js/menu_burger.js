document.addEventListener('DOMContentLoaded', function () {
    // Gestion du menu burger
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuClose = document.getElementById('menu-close');

    menuToggle.addEventListener('click', function () {
        mobileMenu.classList.toggle('hidden');
    });

    menuClose.addEventListener('click', function () {
        mobileMenu.classList.add('hidden');
    });

    // Gestion du sous-menu "Univers" sur mobile
    const universeToggle = document.getElementById('universe-toggle');
    const universeSubmenu = document.getElementById('universe-submenu');

    universeToggle.addEventListener('click', function () {
        universeSubmenu.classList.toggle('hidden');
    });
});