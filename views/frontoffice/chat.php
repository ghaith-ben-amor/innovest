<?php
session_start();
include(__DIR__.'/../../controllers/ChatController.php');

$chatController = new ChatController();
$chatData = $chatController->getChatData();
$users = $chatData['users'];
$selectedUser = $chatData['selectedUser'];

// Debug de la session admin
var_dump("Statut Admin:", $_SESSION['is_admin']);
var_dump("Session complète:", $_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Chat - Innovest</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="views/frontoffice/assets/img/favicon.png" rel="icon">
    <link href="views/frontoffice/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="views/frontoffice/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="views/frontoffice/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="views/frontoffice/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="views/frontoffice/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="views/frontoffice/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="views/frontoffice/assets/css/main.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow: hidden;
        }

        .chat-container {
            display: flex;
            margin-top: 80px;
            height: calc(100vh - 80px);
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        #header {
            width: 100% !important;
            z-index: 9999;
        }

        #user-list {
            width: 300px;
            min-width: 300px;
            background: #fff;
            border-right: 1px solid #eee;
            overflow-y: auto;
            height: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        #user-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #user-list li {
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        #user-list li:hover {
            background-color: #e9ecef;
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        #user-list li a {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .message-preview {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .message-time {
            font-size: 11px;
            color: #8e8e8e;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 4px;
        }

        .user-name {
            font-weight: 500;
            font-size: 15px;
            color: #050505;
        }

        .notification-dot {
            width: 12px;
            height: 12px;
            background-color: #dc3545;
            border-radius: 50%;
            position: absolute;
            right: 15px;
            top: 15px;
            animation: pulse 2s infinite;
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.2);
        }

        @keyframes pulse {
            0% {
                transform: translateY(-50%) scale(1);
                opacity: 1;
            }
            50% {
                transform: translateY(-50%) scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: translateY(-50%) scale(1);
                opacity: 1;
            }
        }

        .user-link {
            position: relative;
            padding-right: 30px;
        }

        .user-link.active {
            background-color: #e9ecef;
            font-weight: 600;
        }

        .user-link:hover {
            text-decoration: none;
        }

        #chat-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
            position: relative;
            height: 100%;
        }

        #chat-box h3 {
            padding: 20px;
            margin: 0;
            border-bottom: 1px solid #eee;
            font-size: 16px;
            font-weight: 600;
            color: #050505;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f0f2f5;
        }

        #messages {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message {
            max-width: 60%;
            padding: 12px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
        }

        .sent {
            background: #0084ff;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .received {
            background: white;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .timestamp {
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
        }

        .chat-input {
            padding: 20px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #message {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 20px;
            background: #f0f2f5;
            font-size: 14px;
            outline: none;
        }

        #message:focus {
            background: #e4e6eb;
        }

        button {
            padding: 8px 16px;
            background: #0084ff;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background: #0073e6;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: white;
        }

        .navmenu {
            margin-right: 20px;
        }

        .message-header {
            font-size: 12px;
            color: #888;
            font-weight: bold;
        }
        .message-preview {
            font-size: 13px;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="views/frontoffice/assets/img/logo.png" alt="">
        <h1 class="sitename">Innovest</h1>
      </a>

     

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#hero" class="active">Home</a></li>
          <li><a href="index.php#about">About</a></li>
          <li><a href="index.php#services">Services</a></li>
          <li><a href="index.php#portfolio">Portfolio</a></li>
          
          <li><a href="index.php?action=chat">Chat</a></li>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
          <li><a href="index.php?action=adminDashboard" style="color: #0084ff; font-weight: bold;"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
          <?php endif; ?>
          <li><a href="index.php#contact">Contact</a></li>
          <li><a href="index.php?action=logout" class="btn-logout" style="color: #ff0000;"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      

    </div>
  </header>

    <div class="chat-container">
        <div id="user-list">
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <a href="index.php?action=chat&user=<?php echo urlencode($user); ?>" data-username="<?php echo htmlspecialchars($user); ?>" class="user-link" data-userid="<?php echo htmlspecialchars($user); ?>">
                            <div class="user-info">
                                <span class="user-name"><?php echo htmlspecialchars($user); ?></span>
                                <?php
                                    $count = $notificationCounts[$user] ?? 0;
                                    if ($count > 0) {
                                        echo '<span class="notification-dot"></span>';
                                    }
                                ?>
                            </div>
                            <div class="last-message" id="last-message-<?php echo htmlspecialchars($user); ?>">
                                <div class="message-header"></div>
                                <div class="message-preview"></div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div id="chat-box">
            <?php if ($selectedUser): ?>
                <h3>Chat avec <?php echo htmlspecialchars($selectedUser); ?></h3>
                <div class="messages-container">
                <div id="messages"></div>

                <style>
                    .reaction-button {
                        cursor: pointer;
                        margin-left: 8px;
                        font-size: 18px;
                        user-select: none;
                    }
                    .reaction-picker {
                        position: absolute;
                        background: white;
                        border: 1px solid #ccc;
                        border-radius: 8px;
                        padding: 5px;
                        display: flex;
                        gap: 5px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        z-index: 1000;
                    }
                    .reaction-picker span {
                        cursor: pointer;
                        font-size: 20px;
                        user-select: none;
                    }
                    .reaction-picker.hidden {
                        display: none;
                    }
                    .reaction-container {
                        margin-top: 4px;
                        font-size: 16px;
                    }
                    .reaction {
                        margin-right: 6px;
                        cursor: default;
                    }
                </style>

                <script>
                    // Reaction types and their emojis
                    const reactionTypes = {
                        like: '👍',
                        love: '❤️',
                        laugh: '😂',
                        surprise: '😮',
                        sad: '😢',
                        angry: '😠'
                    };

                    // Render reactions for a message
                    function renderReactions(messageId, reactions) {
                        const container = document.getElementById(`reactions-${messageId}`);
                        container.innerHTML = '';
                        const userReactions = {};

                        reactions.forEach(r => {
                            if (!userReactions[r.reaction_type]) {
                                userReactions[r.reaction_type] = 0;
                            }
                            userReactions[r.reaction_type]++;
                        });

                        for (const [type, count] of Object.entries(userReactions)) {
                            const span = document.createElement('span');
                            span.className = 'reaction';
                            span.textContent = reactionTypes[type] + ' ' + count;
                            container.appendChild(span);
                        }
                    }

                    // Fetch reactions for all messages
                    function fetchReactions() {
                        const messageElements = document.querySelectorAll('.message');
                        messageElements.forEach(msgEl => {
                            const messageId = msgEl.getAttribute('data-message-id');
                            if (!messageId) return;
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', 'index.php?action=fetchReactions', true);
                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr.onload = function() {
                                if (this.status === 200) {
                                    const reactions = JSON.parse(this.responseText);
                                    renderReactions(messageId, reactions);
                                }
                            };
                            xhr.send('message_id=' + encodeURIComponent(messageId));
                        });
                    }

                    // Add or update reaction
                    function addOrUpdateReaction(messageId, reactionType) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'index.php?action=addOrUpdateReaction', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (this.status === 200) {
                                fetchReactions();
                            }
                        };
                        xhr.send('message_id=' + encodeURIComponent(messageId) + '&reaction_type=' + encodeURIComponent(reactionType));
                    }

                    // Remove reaction
                    function removeReaction(messageId) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'index.php?action=removeReaction', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (this.status === 200) {
                                fetchReactions();
                            }
                        };
                        xhr.send('message_id=' + encodeURIComponent(messageId));
                    }

                    // Call fetchReactions after messages are loaded
                    document.addEventListener('DOMContentLoaded', () => {
                        fetchReactions();
                    });

                    // Also call fetchReactions after messages are refreshed
                    const originalFetchMessages = window.fetchMessages;
                    window.fetchMessages = function() {
                        originalFetchMessages();
                        setTimeout(fetchReactions, 500);
                    };
                </script>
                </div>
                <div class="chat-input">
                    <input type="text" id="message" placeholder="Écris un message...">
                    <button onclick="sendMessage()">Envoyer</button>
                </div>
            <?php else: ?>
                <div class="messages-container">
                    <p style="text-align: center; color: #65676B; margin-top: 20px;">
                        Sélectionnez un utilisateur pour commencer le chat
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="views/frontoffice/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="views/frontoffice/assets/vendor/aos/aos.js"></script>
    <script src="views/frontoffice/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="views/frontoffice/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="views/frontoffice/assets/js/main.js"></script>

    // ... existing code ...
    <script>
        let selectedUser = "<?php echo $selectedUser ?? ''; ?>";
        let activeMenu = null;
        let activeEditForm = null;

        function fetchLastMessages() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "index.php?action=fetchLastMessages", true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const lastMessages = JSON.parse(this.responseText);
                    console.log(lastMessages);
                    Object.keys(lastMessages).forEach(username => {
                        const lastMessageElement = document.getElementById(`last-message-${username}`);
                        if (lastMessageElement) {
                            const message = lastMessages[username];
                            if (message) {
                                lastMessageElement.querySelector('.message-header').textContent =
                                    `${message.sender} → ${message.receiver} ${message.created_at}`;
                                lastMessageElement.querySelector('.message-preview').textContent =
                                    `${message.sender} : ${message.content}`;
                            } else {
                                lastMessageElement.querySelector('.message-header').textContent = '';
                                lastMessageElement.querySelector('.message-preview').textContent = 'Aucun message';
                            }
                        }
                    });
                }
            };
            xhr.send();
        }

        // Appeler fetchLastMessages toutes les 3 secondes au lieu de 5
        setInterval(fetchLastMessages, 3000);
        // Appeler fetchLastMessages immédiatement au chargement
        document.addEventListener('DOMContentLoaded', fetchLastMessages);

        function fetchMessages() {
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
        }

        function fetchNotificationCounts() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "index.php?action=getNotificationCountsAjax", true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const counts = JSON.parse(this.responseText);
                    document.querySelectorAll('#user-list ul li a.user-link').forEach(link => {
                        const username = link.getAttribute('data-username');
                        const count = counts[username] || 0;
                        let dot = link.querySelector('.notification-dot');
                        if (count > 0) {
                            if (!dot) {
                                dot = document.createElement('span');
                                dot.className = 'notification-dot';
                                link.appendChild(dot);
                            }
                        } else {
                            if (dot) {
                                dot.remove();
                            }
                        }
                    });
                }
            };
            xhr.send();
        }

        document.querySelectorAll('a.user-link').forEach(link => {
            link.addEventListener('click', function(event) {
                const userId = this.getAttribute('data-userid');
                if (userId) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'index.php?action=markNotificationsAsSeen', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.send('receiver_id=' + encodeURIComponent(userId));
                    // Remove notification dot immediately
                    const dot = this.querySelector('.notification-dot');
                    if (dot) {
                        dot.remove();
                    }
                }
            });
        });

        function sendMessage() {
            const message = document.getElementById("message").value;
            if (message.trim() === "") return;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?action=submitMessage", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                document.getElementById("message").value = "";
                fetchMessages();
                fetchNotificationCounts();
            };
            xhr.send("sender=<?php echo $_SESSION['username']; ?>&receiver=" + selectedUser + "&message=" + encodeURIComponent(message));
        }

        function toggleMenu(event, messageId) {
            event.stopPropagation();
            const menu = document.getElementById(`menu-${messageId}`);
            
            // Fermer le menu actif s'il existe
            if (activeMenu && activeMenu !== menu) {
                activeMenu.classList.remove('show');
            }
            
            // Fermer le formulaire d'édition actif s'il existe
            if (activeEditForm) {
                cancelEdit(null, activeEditForm);
            }
            
            // Basculer l'affichage du menu
            menu.classList.toggle('show');
            activeMenu = menu.classList.contains('show') ? menu : null;
        }

        function editMessage(event, messageId, content) {
            event.stopPropagation();
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            const messageContent = messageElement.querySelector('.message-content');
            const messageText = messageElement.querySelector('.message-text');
            
            // Fermer le menu actif
            if (activeMenu) {
                activeMenu.classList.remove('show');
                activeMenu = null;
            }
            
            // Créer le formulaire d'édition s'il n'existe pas
            if (!messageElement.querySelector('.edit-form')) {
                const form = document.createElement('div');
                form.className = 'edit-form';
                form.innerHTML = `
                    <input type="text" value="${content.replace(/"/g, '&quot;')}" class="edit-input">
                    <button onclick="saveEdit(event, ${messageId})">Enregistrer</button>
                    <button onclick="cancelEdit(event, ${messageId})">Annuler</button>
                `;
                messageText.style.display = 'none';
                messageContent.insertBefore(form, messageContent.querySelector('.message-actions'));
            }
            
            // Afficher le formulaire
            const editForm = messageElement.querySelector('.edit-form');
            editForm.classList.add('show');
            editForm.querySelector('input').focus();
            activeEditForm = messageId;
        }

        function saveEdit(event, messageId) {
            event.stopPropagation();
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            const input = messageElement.querySelector('.edit-input');
            const newContent = input.value.trim();
            
            if (newContent === '') return;
            
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?action=updateMessage", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                const response = JSON.parse(this.responseText);
                if (response.success) {
                    fetchMessages();
                } else {
                    alert('Erreur lors de la modification du message');
                }
            };
            xhr.send("message_id=" + messageId + "&content=" + encodeURIComponent(newContent));
            activeEditForm = null;
        }

        function cancelEdit(event, messageId) {
            if (event) event.stopPropagation();
            const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
            const editForm = messageElement.querySelector('.edit-form');
            const messageText = messageElement.querySelector('.message-text');
            
            editForm.classList.remove('show');
            messageText.style.display = 'block';
            setTimeout(() => {
                editForm.remove();
            }, 200);
            activeEditForm = null;
        }

        function deleteMessage(messageId) {
            if (!confirm('Voulez-vous vraiment supprimer ce message ?')) return;
            
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?action=deleteMessage", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                const response = JSON.parse(this.responseText);
                if (response.success) {
                    fetchMessages();
                } else {
                    alert('Erreur lors de la suppression du message');
                }
            };
            xhr.send("message_id=" + messageId);
        }

        // Fermer le menu si on clique ailleurs
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.message-actions') && !event.target.closest('.action-menu') && activeMenu) {
                activeMenu.classList.remove('show');
                activeMenu = null;
            }
        });

        setInterval(fetchMessages, 1000);

        // Envoi avec Entrée
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("message").addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    sendMessage();
                    e.preventDefault();
                }
            });
        });
    </script>
// ... existing code ...
</body>
</html>
