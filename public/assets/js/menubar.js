document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('profile-toggle');
    const menubar = document.querySelector('.menubar');

    if (menuToggle && menubar) {
        menuToggle.addEventListener('click', () => {
            menubar.classList.toggle('active');
        });
    } else {
        console.error('Menu toggle or menubar element not found');
    }
});
