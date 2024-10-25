document.addEventListener("DOMContentLoaded", function () {
    const notificationBell = document.getElementById("notification-bell");
    const notificationPanel = document.getElementById("notification-panel");

    if (notificationBell && notificationPanel) {
        notificationBell.addEventListener("click", () => {
            // Toggle notification panel
            notificationPanel.classList.toggle("active");

            // Set notification panel state and close menubar if notification is open
            if (notificationPanel.classList.contains("active")) {
                sessionStorage.setItem("notificationOpen", "true");
                sessionStorage.setItem("menubarOpen", "false");
                document.querySelector(".menubar")?.classList.remove("active");
            } else {
                sessionStorage.setItem("notificationOpen", "false");
            }
        });
        // Close notification panel
        document    
            .getElementById("notification-close")
            .addEventListener("click", () => {
                notificationPanel.classList.remove("active");
                sessionStorage.setItem("notificationOpen", "false");
            });
    } else {
        console.error(
            "Notification bell or notification panel element not found"
        );
    }
});
