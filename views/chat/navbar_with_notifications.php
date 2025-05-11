<?php
// Exemple d'intégration du badge de messages non lus dans la barre de navigation
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Chat App</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Ajouter l'ID utilisateur comme meta pour JavaScript -->
          <meta name="user-id" content="<?php echo $_SESSION['user_id']; ?>">
          
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              Accueil
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="chat.php">
              Messages <span class="unread-messages-badge">
                <?php include 'unread_messages_badge.php'; ?>
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">
              Profil
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Déconnexion</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Connexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Inscription</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Inclure le script JavaScript pour les notifications -->
<script src="unread_messages.js"></script>