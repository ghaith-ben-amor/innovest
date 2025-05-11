<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--primary-color);
            color: white;
            padding: 20px;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            min-height: 100vh;
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
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: var(--secondary-color);
            color: white;
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .bg-purple { background: linear-gradient(45deg, #9b59b6, #8e44ad); }
        .bg-blue { background: linear-gradient(45deg, #3498db, #2980b9); }
        .bg-green { background: linear-gradient(45deg, #2ecc71, #27ae60); }
        .bg-orange { background: linear-gradient(45deg, #e67e22, #d35400); }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 15px 15px 0 0 !important;
        }

        .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            font-weight: bold;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #6c757d;
        }

        .table td {
            vertical-align: middle;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .stat-trend {
            font-size: 0.9rem;
            margin-left: 5px;
        }

        .trend-up { color: #2ecc71; }
        .trend-down { color: #e74c3c; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Admin Dashboard</h4>
        </div>
        <nav>
            <a href="index.php?action=adminDashboard" class="nav-link active">
                <i class="bi bi-house-door"></i> Tableau de bord
            </a>
            <a href="index.php?action=adminUsers" class="nav-link">
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
                <h2>Tableau de bord</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="updateStats()">
                        <i class="bi bi-arrow-clockwise"></i> Actualiser
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calendar"></i> Période
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="setPeriod('today')">Aujourd'hui</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setPeriod('week')">Cette semaine</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setPeriod('month')">Ce mois</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-purple">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="fs-4 mb-2">Utilisateurs</h3>
                        <div class="d-flex align-items-baseline">
                            <h4 class="fs-2 mb-0"><?php echo $stats['totalUsers'] ?? 0; ?></h4>
                            <span class="stat-trend trend-up">+12%</span>
                        </div>
                        <small class="text-muted">Total des utilisateurs</small>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-purple" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-blue">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <h3 class="fs-4 mb-2">Messages</h3>
                        <div class="d-flex align-items-baseline">
                            <h4 class="fs-2 mb-0"><?php echo $stats['totalMessages'] ?? 0; ?></h4>
                            <span class="stat-trend trend-up">+8%</span>
                        </div>
                        <small class="text-muted">Total des messages</small>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-blue" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-green">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h3 class="fs-4 mb-2">Actifs</h3>
                        <div class="d-flex align-items-baseline">
                            <h4 class="fs-2 mb-0"><?php echo $stats['activeUsers'] ?? 0; ?></h4>
                            <span class="stat-trend trend-up">+5%</span>
                        </div>
                        <small class="text-muted">Utilisateurs actifs</small>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-green" style="width: 45%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-orange">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3 class="fs-4 mb-2">Aujourd'hui</h3>
                        <div class="d-flex align-items-baseline">
                            <h4 class="fs-2 mb-0"><?php echo $stats['todayMessages'] ?? 0; ?></h4>
                            <span class="stat-trend trend-up">+15%</span>
                        </div>
                        <small class="text-muted">Messages aujourd'hui</small>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-orange" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Activity -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Activité des messages</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="messageChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Nouveaux utilisateurs</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <?php if (!empty($newUsers)): ?>
                                    <?php foreach ($newUsers as $user): ?>
                                        <li class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar">
                                                        <span><?php echo strtoupper(substr($user['username'], 0, 2)); ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($user['username']); ?></h6>
                                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime('now')); ?></small>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-center">Aucun nouvel utilisateur</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Activité récente</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Action</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($recentActivity)): ?>
                                            <?php foreach ($recentActivity as $activity): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar me-2">
                                                                <span><?php echo strtoupper(substr($activity['username'], 0, 2)); ?></span>
                                                            </div>
                                                            <?php echo htmlspecialchars($activity['username']); ?>
                                                        </div>
                                                    </td>
                                                    <td>Message envoyé</td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?></td>
                                                    <td>
                                                        <span class="badge bg-success">Terminé</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Aucune activité récente</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration du graphique
        const ctx = document.getElementById('messageChart').getContext('2d');
        const messageChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Messages',
                    data: [12, 19, 3, 5, 2, 3, 7],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Fonction pour mettre à jour les statistiques en temps réel
        function updateStats() {
            fetch('index.php?action=getAdminStats')
                .then(response => response.json())
                .then(data => {
                    // Mise à jour des statistiques
                    document.querySelector('.stat-card:nth-child(1) h4').textContent = data.totalUsers;
                    document.querySelector('.stat-card:nth-child(2) h4').textContent = data.totalMessages;
                    document.querySelector('.stat-card:nth-child(3) h4').textContent = data.activeUsers;
                    document.querySelector('.stat-card:nth-child(4) h4').textContent = data.todayMessages;
                });
        }

        // Mettre à jour les statistiques toutes les 30 secondes
        setInterval(updateStats, 30000);

        // Fonction pour changer la période
        function setPeriod(period) {
            // Mettre à jour le texte du bouton pour indiquer la période sélectionnée
            const periodButton = document.querySelector('.dropdown-toggle');
            let periodText = 'Période';

            switch(period) {
                case 'today':
                    periodText = 'Aujourd\'hui';
                    break;
                case 'week':
                    periodText = 'Cette semaine';
                    break;
                case 'month':
                    periodText = 'Ce mois';
                    break;
                default:
                    periodText = 'Période';
            }

            periodButton.innerHTML = `<i class="bi bi-calendar"></i> ${periodText}`;

            // Récupérer les statistiques pour la période sélectionnée
            fetch(`index.php?action=getAdminStats&period=${period}`)
                .then(response => response.json())
                .then(data => {
                    // Mise à jour des statistiques
                    document.querySelector('.stat-card:nth-child(1) h4').textContent = data.totalUsers;
                    document.querySelector('.stat-card:nth-child(2) h4').textContent = data.totalMessages;
                    document.querySelector('.stat-card:nth-child(3) h4').textContent = data.activeUsers;
                    document.querySelector('.stat-card:nth-child(4) h4').textContent = data.todayMessages;

                    // Mettre à jour le titre de la 4ème carte en fonction de la période
                    const fourthCardTitle = document.querySelector('.stat-card:nth-child(4) h3');
                    switch(period) {
                        case 'today':
                            fourthCardTitle.textContent = 'Aujourd\'hui';
                            break;
                        case 'week':
                            fourthCardTitle.textContent = 'Cette semaine';
                            break;
                        case 'month':
                            fourthCardTitle.textContent = 'Ce mois';
                            break;
                        default:
                            fourthCardTitle.textContent = 'Aujourd\'hui';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des statistiques:', error);
                });
        }

        // Initialiser les statistiques au chargement
        document.addEventListener('DOMContentLoaded', updateStats);
    </script>
</body>
</html>