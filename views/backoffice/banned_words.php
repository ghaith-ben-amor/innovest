<?php
require_once 'models/BannedWordModel.php';
$bannedWordModel = new BannedWordModel();
$bannedWords = $bannedWordModel->getAllBannedWords();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des mots interdits - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include 'views/backoffice/includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'views/backoffice/includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1>Gestion des mots interdits</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWordModal">
                        <i class="bi bi-plus-circle"></i> Ajouter un mot
                    </button>
                </div>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                        <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Mot</th>
                                <th>Sévérité</th>
                                <th>Date d'ajout</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bannedWords as $word): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($word['word']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $word['severity'] === 'high' ? 'danger' : 
                                                ($word['severity'] === 'medium' ? 'warning' : 'info'); 
                                        ?>">
                                            <?php echo htmlspecialchars($word['severity']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($word['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-word" 
                                                data-id="<?php echo $word['id']; ?>"
                                                data-word="<?php echo htmlspecialchars($word['word']); ?>"
                                                data-severity="<?php echo $word['severity']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-word" 
                                                data-id="<?php echo $word['id']; ?>"
                                                data-word="<?php echo htmlspecialchars($word['word']); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Ajout -->
    <div class="modal fade" id="addWordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un mot interdit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?action=addBannedWord" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="word" class="form-label">Mot</label>
                            <input type="text" class="form-control" id="word" name="word" required>
                        </div>
                        <div class="mb-3">
                            <label for="severity" class="form-label">Sévérité</label>
                            <select class="form-select" id="severity" name="severity" required>
                                <option value="low">Faible</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Élevée</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modification -->
    <div class="modal fade" id="editWordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier un mot interdit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="index.php?action=updateBannedWord" method="POST">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_word" class="form-label">Mot</label>
                            <input type="text" class="form-control" id="edit_word" name="word" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_severity" class="form-label">Sévérité</label>
                            <select class="form-select" id="edit_severity" name="severity" required>
                                <option value="low">Faible</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Élevée</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestionnaire pour le bouton d'édition
            document.querySelectorAll('.edit-word').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const word = this.dataset.word;
                    const severity = this.dataset.severity;

                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_word').value = word;
                    document.getElementById('edit_severity').value = severity;

                    new bootstrap.Modal(document.getElementById('editWordModal')).show();
                });
            });

            // Gestionnaire pour le bouton de suppression
            document.querySelectorAll('.delete-word').forEach(button => {
                button.addEventListener('click', function() {
                    const word = this.dataset.word;
                    if (confirm(`Êtes-vous sûr de vouloir supprimer le mot "${word}" ?`)) {
                        window.location.href = `index.php?action=deleteBannedWord&id=${this.dataset.id}`;
                    }
                });
            });
        });
    </script>
</body>
</html> 