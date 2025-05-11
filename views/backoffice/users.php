<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
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

        .user-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        tr:hover .user-actions {
            opacity: 1;
        }

        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            margin: 0 2px;
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
            <a href="index.php?action=adminUsers" class="nav-link active">
                <i class="bi bi-people"></i> Utilisateurs
            </a>
            <a href="index.php?action=adminMessages" class="nav-link">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gestion des Utilisateurs</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus"></i> Ajouter un utilisateur
                </button>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date de création</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['iduser']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td>-</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    User
                                                </span>
                                            </td>
                                            <td>-</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    Active
                                                </span>
                                            </td>
                                            <td>
                                                <div class="user-actions">
                                                    <button class="btn btn-sm btn-primary action-btn" onclick="editUser(<?php echo $user['iduser']; ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger action-btn" onclick="deleteUser(<?php echo $user['iduser']; ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun utilisateur trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter/Modifier un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <input type="hidden" name="iduser" value="">
                        <div class="mb-3">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editUser(userId) {
            // Récupérer les informations de l'utilisateur
            fetch(`index.php?action=getUser&id=${userId}`)
                .then(response => response.json())
                .then(user => {
                    // Remplir le formulaire avec les informations de l'utilisateur
                    document.querySelector('#addUserForm [name="username"]').value = user.username;
                    document.querySelector('#addUserForm [name="password"]').value = user.password;
                    document.querySelector('#addUserForm [name="iduser"]').value = user.iduser;
                    // Ouvrir le modal
                    const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
                    modal.show();
                });
        }

        function deleteUser(userId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                fetch('index.php?action=deleteUser', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `iduser=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression de l\'utilisateur');
                    }
                });
            }
        }

        function saveUser() {
            const formData = new FormData(document.getElementById('addUserForm'));
            fetch('index.php?action=saveUser', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de l\'enregistrement de l\'utilisateur');
                }
            });
        }
    </script>
</body>
</html> 