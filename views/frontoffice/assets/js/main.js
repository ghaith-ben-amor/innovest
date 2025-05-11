/**
* Template Name: Innovest
* Template URL: https://bootstrapmade.com/flexstart-bootstrap-startup-template/
* Updated: Nov 01 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  // Existing code unchanged...

  // Add helper function to refresh messages (used after reaction update)
  window.fetchMessages = function() {
    const selectedUser = "<?php echo encodeURIComponent($_GET['user'] ?? ''); ?>";
    if (!selectedUser) return;
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=fetchMessages", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      document.getElementById("messages").innerHTML = this.responseText;
      const messagesDiv = document.getElementById("messages");
      messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };
    xhr.send("sender=<?php echo $_SESSION['username']; ?>&receiver=" + selectedUser);
  };

  // WebSocket notification handling
  const userId = "<?php echo $_SESSION['username']; ?>";
  if (userId) {
    const wsProtocol = location.protocol === 'https:' ? 'wss' : 'ws';
    const wsUrl = wsProtocol + '://' + location.hostname + ':8080?userId=' + encodeURIComponent(userId);
    const socket = new WebSocket(wsUrl);

    socket.onopen = function() {
      console.log('WebSocket connection opened for user:', userId);
    };

    function requestNotificationPermission() {
      if (!("Notification" in window)) {
        console.log("This browser does not support desktop notification");
        return;
      }
      if (Notification.permission === "default") {
        Notification.requestPermission().then(function (permission) {
          console.log("Notification permission:", permission);
        });
      }
    }

    requestNotificationPermission();

    socket.onmessage = function(event) {
      try {
        const data = JSON.parse(event.data);
        if (data.type === 'notification') {
          if ("Notification" in window && Notification.permission === "granted") {
            new Notification('Notification', { body: data.message });
          } else {
            alert('Notification: ' + data.message);
          }

          // Optionally, refresh messages or notification counts here
          window.fetchMessages();
          // You can also implement a function to update notification count in UI
        }
      } catch (e) {
        console.error('Error parsing WebSocket message:', e);
      }
    };

    socket.onclose = function() {
      console.log('WebSocket connection closed');
    };

    socket.onerror = function(error) {
      console.error('WebSocket error:', error);
    };
  }

})();
