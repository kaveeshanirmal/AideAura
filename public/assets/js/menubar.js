document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("profile-toggle");
    const menubar = document.querySelector(".menubar");

    if (menuToggle && menubar) {
        menuToggle.addEventListener("click", () => {
            // Toggle menubar
            menubar.classList.toggle("active");

            // Set menubar state and close notification if menubar is open
            if (menubar.classList.contains("active")) {
                sessionStorage.setItem("menubarOpen", "true");
                sessionStorage.setItem("notificationOpen", "false");
                document
                    .getElementById("notification-panel")
                    ?.classList.remove("active");
            } else {
                sessionStorage.setItem("menubarOpen", "false");
            }
        });
        // Close menubar
        document    
            .getElementById("menubar-close")
            .addEventListener("click", () => {
                menubar.classList.remove("active");
                sessionStorage.setItem("menubarOpen", "false");
            });
    } else {
        console.error("Menu toggle or menubar element not found");
    }
});
