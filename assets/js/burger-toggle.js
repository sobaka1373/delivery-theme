function toggleMenu() {
    const menu = document.querySelector('.menu');
    const hamburger = document.getElementById('hamburger-menu');
    menu.classList.toggle('active');
    hamburger.classList.toggle('open');
}