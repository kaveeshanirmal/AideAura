document.addEventListener("DOMContentLoaded", function () {
  // Navbar dropdown functionality
  const dropdownToggle = document.querySelector(".dropdown-toggle");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  if (dropdownToggle && dropdownMenu) {
    // Toggle dropdown on mobile/touch devices
    dropdownToggle.addEventListener("hover", function (e) {
      e.preventDefault();
      dropdownMenu.style.display =
        dropdownMenu.style.display === "block" ? "none" : "block";
    });

    // Close dropdown when hovering outside
    document.addEventListener("hover", function (e) {
      if (
        !dropdownToggle.contains(e.target) &&
        !dropdownMenu.contains(e.target)
      ) {
        dropdownMenu.style.display = "none";
      }
    });

    // Handle service selection from dropdown
    const serviceLinks = document.querySelectorAll(".dropdown-item");
    serviceLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const service = this.dataset.service;
        const href = this.href;

        fetch(`${ROOT}/public/SelectService`, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "service=" + encodeURIComponent(service),
        })
          .then(function (response) {
            if (response.ok) {
              window.location.href = href;
            } else {
              console.error("Error saving service selection");
            }
          })
          .catch(function (error) {
            console.error("Error:", error);
          });
      });
    });
  }

  // Notification Panel functionality
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
              document
                .querySelector(".notification-button")
                .classList.remove("unread");
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
            document
              .querySelector(".notification-button")
              .classList.remove("unread");
          }, 500);
        }
      })
      .catch((error) =>
        console.error("Error marking all notifications as read:", error),
      );
  }

  // Use AJAX polling to check for new notifications and update the panel
  setInterval(() => {
    fetch(`${ROOT}/public/notifications/poll`, {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        // Update the notification bell status
        const notificationButton = document.querySelector(
          ".notification-button",
        );

        if (Array.isArray(data) && data.length > 0) {
          // There are unread notifications
          notificationButton.classList.add("unread");

          // Update the notification panel content with new notifications
          updateNotificationPanel(data);

          console.log("New notifications:", data);
        } else {
          // No new notifications
          notificationButton.classList.remove("unread");
          console.log("No new notifications");
        }
      })
      .catch((error) =>
        console.error("Error checking for new notifications:", error),
      );
  }, 10000); // Check every 10 seconds

  // Function to update the notification panel with new notifications
  function updateNotificationPanel(notifications) {
    const notificationList = document.querySelector(".notification-list");
    const notificationFooter = document.querySelector(".notification-footer");

    // Only update if there are notifications and the panel exists
    if (notificationList && notifications.length > 0) {
      // Clear "no notifications" message if it exists
      const noNotificationsEl =
        notificationList.querySelector(".no-notifications");
      if (noNotificationsEl) {
        notificationList.innerHTML = "";
      }

      // Create and append new notification items
      notifications.forEach((notif) => {
        // Check if this notification already exists in the panel
        const existingNotif = document.querySelector(
          `.notification-item[data-notification-id="${notif.notificationID}"]`,
        );
        if (!existingNotif) {
          // Need to make a fetch request to render the item with server-side helpers
          fetch(`${ROOT}/public/notifications/renderItem`, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${notif.notificationID}`,
          })
            .then((response) => response.text())
            .then((html) => {
              // Insert the server-rendered notification item
              if (notificationList.firstChild) {
                notificationList.insertAdjacentHTML("afterbegin", html);
              } else {
                notificationList.innerHTML = html;
              }

              // Add click event listener to the newly added mark read button
              const newItem = notificationList.querySelector(
                `.notification-item[data-notification-id="${notif.notificationID}"]`,
              );
              if (newItem) {
                const markReadBtn = newItem.querySelector(".mark-read-btn");
                markReadBtn.addEventListener("click", function () {
                  markAsRead(notif.notificationID, newItem);
                });
              }
            })
            .catch((error) =>
              console.error("Error rendering notification item:", error),
            );
        }
      });

      // Show the footer with "Mark all as read" button
      if (notificationFooter) {
        notificationFooter.style.display = "block";
      } else if (notifications.length > 0) {
        // If the footer doesn't exist, create it
        const newFooter = document.createElement("div");
        newFooter.className = "notification-footer";
        newFooter.innerHTML =
          '<button id="mark-all-read">Mark all as read</button>';
        document.getElementById("notification-panel").appendChild(newFooter);

        // Add event listener to the new mark all read button
        document
          .getElementById("mark-all-read")
          .addEventListener("click", function () {
            markAllAsRead();
          });
      }
    }
  }
});
