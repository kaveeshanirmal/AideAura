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

    // Mark single notification as read
    document.querySelectorAll(".mark-read-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        const notificationItem = this.closest(".notification-item");
        const notificationId = notificationItem.dataset.notificationId;

        markAsRead(notificationId, notificationItem);
      });
    });

    // Mark all notifications as read
    const markAllBtn = document.getElementById("mark-all-read");
    if (markAllBtn) {
      markAllBtn.addEventListener("click", function () {
        markAllAsRead();
      });
    }
  } else {
    console.error("Notification bell or notification panel element not found");
  }

  // Function to mark a notification as read
  function markAsRead(notificationId, element) {
    fetch(`${ROOT}/public/notifications/markAsRead`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id=${notificationId}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Add animation class
          element.classList.add("marking-read");

          // Remove the element after animation completes
          setTimeout(() => {
            element.remove();

            // Check if there are any notifications left
            const remainingNotifications =
              document.querySelectorAll(".notification-item");
            if (remainingNotifications.length === 0) {
              // No notifications left, add "No new notifications" message
              const noNotificationsMsg = document.createElement("li");
              noNotificationsMsg.className = "no-notifications";
              noNotificationsMsg.innerHTML = "<p>No new notifications</p>";
              document
                .querySelector(".notification-list")
                .appendChild(noNotificationsMsg);

              // Hide the "Mark all as read" button
              const footer = document.querySelector(".notification-footer");
              if (footer) footer.style.display = "none";

              // Remove the notification indicator from the bell
              notificationBell.classList.remove("new-notifications");
            }
          }, 500);
        }
      })
      .catch((error) =>
        console.error("Error marking notification as read:", error),
      );
  }

  // Function to mark all notifications as read
  function markAllAsRead() {
    fetch(`${ROOT}/public/notifications/markAllAsRead`, {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Apply animation to all notification items
          const notificationItems =
            document.querySelectorAll(".notification-item");
          notificationItems.forEach((item) => {
            item.classList.add("marking-read");
          });

          // Clear notifications after animation
          setTimeout(() => {
            const notificationList =
              document.querySelector(".notification-list");
            notificationList.innerHTML =
              '<li class="no-notifications"><p>No new notifications</p></li>';

            // Hide the "Mark all as read" button
            const footer = document.querySelector(".notification-footer");
            if (footer) footer.style.display = "none";

            // Remove the notification indicator from the bell
            notificationBell.classList.remove("new-notifications");
          }, 500);
        }
      })
      .catch((error) =>
        console.error("Error marking all notifications as read:", error),
      );
  }

  // Use AJAX polling to check for new notifications
  setInterval(() => {
    fetch(`${ROOT}/public/notifications/poll`, {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (Array.isArray(data) && data.length > 0) {
          // There are unread notifications
          notificationBell.classList.add("new-notifications");
          console.log("New notifications:", data);
        } else {
          // No new notifications
          notificationBell.classList.remove("new-notifications");
          console.log("No new notifications");
        }
      })
      .catch((error) =>
        console.error("Error checking for new notifications:", error),
      );
  }, 60000);
});
