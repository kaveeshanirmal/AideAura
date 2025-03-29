document.getElementById("username").addEventListener("input", function () {
  let username = this.value;

  // If username is empty, clear the status message
  if (username.trim() === "") {
    document.getElementById("username-status").innerText = "";
    return;
  }

  // Create a new AJAX request
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "check_username.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = JSON.parse(xhr.responseText);

      // Display message based on the response
      if (response.exists) {
        document.getElementById("username-status").innerText =
          "Username is already taken";
        document.getElementById("username-status").style.color = "red";
      } else {
        document.getElementById("username-status").innerText =
          "Username is available";
        document.getElementById("username-status").style.color = "green";
      }
    }
  };

  // Send the username to the server
  xhr.send("username=" + encodeURIComponent(username));
});
