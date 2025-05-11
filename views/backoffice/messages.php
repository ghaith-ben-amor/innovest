<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Messages - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #2c3e50;
            color: white;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            background: #3498db;
            color: white;
        }

        .nav-link i {
            margin-right: 10px;
        }

        .message-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .message-card:hover {
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .message-header {
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .message-content {
            padding: 15px;
        }

        .message-footer {
            padding: 15px;
            border-top: 1px solid #e0e0e0;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }

        .message-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .message-card:hover .message-actions {
            opacity: 1;
        }

        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            margin: 0 2px;
        }

        .filters {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .discussion-list {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .discussion-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .discussion-item:hover {
            background-color: #f8f9fa;
        }

        .discussion-item.active {
            background-color: #e9ecef;
        }

        .messages-container {
            height: calc(100vh - 200px);
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 8px;
            max-width: 80%;
        }

        .message-sender {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .message-time {
            font-size: 0.8em;
            color: #6c757d;
        }

        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Admin Dashboard</h4>
        </div>
        <nav>
            <a href="index.php?action=adminDashboard" class="nav-link">
                <i class="bi bi-house-door"></i> Tableau de bord
            </a>
            <a href="index.php?action=adminUsers" class="nav-link">
                <i class="bi bi-people"></i> Utilisateurs
            </a>
            <a href="index.php?action=adminMessages" class="nav-link active">
                <i class="bi bi-chat-dots"></i> Messages
            </a>
            <a href="index.php?action=adminSettings" class="nav-link">
                <i class="bi bi-gear"></i> Paramètres
            </a>
            <a href="index.php?action=logout" class="nav-link text-danger mt-auto">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Liste des discussions -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Discussions</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="search-bar p-3">
                                <input type="text" class="form-control" placeholder="Rechercher une discussion..." id="searchDiscussion">
                            </div>
                            <div class="discussion-list">
                                <?php foreach ($discussions as $discussion): ?>
                                    <div class="discussion-item" data-id="<?php echo $discussion['discussion_id']; ?>">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?php echo htmlspecialchars($discussion['user1_username']); ?></strong>
                                                <i class="bi bi-arrow-right mx-2"></i>
                                                <strong><?php echo htmlspecialchars($discussion['user2_username']); ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($discussion['last_message_date'])); ?>
                                            </small>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <strong><?php echo htmlspecialchars($discussion['last_message_sender']); ?>:</strong>
                                            <?php echo htmlspecialchars(substr($discussion['last_message'], 0, 50)) . '...'; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages de la discussion sélectionnée -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Messages</h5>
                        </div>
                        <div class="card-body">
                            <div class="messages-container" id="messagesContainer">
                                <div class="text-center text-muted">
                                    <i class="bi bi-chat-dots"></i>
                                    <p>Sélectionnez une discussion pour voir les messages</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonction pour charger les messages d'une discussion
        function loadDiscussionMessages(discussionId) {
            fetch(`index.php?action=getDiscussionMessages&id=${discussionId}`)
                .then(response => response.json())
                .then(messages => {
                    const container = document.getElementById('messagesContainer');
                    container.innerHTML = '';
                    
                    messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message bg-white';
                        messageDiv.innerHTML = `
                            <div class="message-sender">${message.sender_username}</div>
                            <div class="message-content">${message.message}</div>
                            <div class="message-time">${new Date(message.created_at).toLocaleString()}</div>
                        `;
                        container.appendChild(messageDiv);
                    });
                    
                    container.scrollTop = container.scrollHeight;
                });
        }

        // Gestionnaire de clic sur les discussions
        document.querySelectorAll('.discussion-item').forEach(item => {
            item.addEventListener('click', function() {
                // Retirer la classe active de tous les items
                document.querySelectorAll('.discussion-item').forEach(i => i.classList.remove('active'));
                // Ajouter la classe active à l'item cliqué
                this.classList.add('active');
                // Charger les messages
                loadDiscussionMessages(this.dataset.id);
            });
        });

        // Recherche dans les discussions
        document.getElementById('searchDiscussion').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            document.querySelectorAll('.discussion-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(search) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html> 