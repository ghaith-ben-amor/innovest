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
        h2, h3 {
            margin: 10px;
        }

        a {
            text-decoration: none;
            color: #1877f2;
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
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .unread-badge {
    background: #ff4757;
    color: white;
    border-radius: 50%;
    padding: 4px;
    font-size: 10px;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    right: 15px;
    top: 15px;
    z-index: 1;
}

#user-list li:hover {
            background-color: #f0f2f5;
        }

        #user-list li a {
    position: relative;
    padding-right: 30px;
    color: #050505;
    text-decoration: none;
    display: block;
    font-size: 14px;
}

/* Styles pour la barre de recherche avancée */
.search-container {
    margin-bottom: 15px;
    position: relative;
}

.search-input-container {
    display: flex;
    align-items: center;
    background-color: #f0f2f5;
    border-radius: 20px;
    padding: 8px 12px;
    margin-bottom: 10px;
}

.search-icon {
    color: #65676B;
    margin-right: 8px;
    font-size: 14px;
}

.search-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 14px;
    color: #050505;
}

.advanced-search-btn {
    background: none;
    border: none;
    color: #65676B;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.advanced-search-btn:hover {
    background-color: #e4e6eb;
}

.advanced-search-options {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 15px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.option-group {
    margin-bottom: 12px;
}

.option-group label {
    display: block;
    font-size: 13px;
    color: #65676B;
    margin-bottom: 5px;
    font-weight: 500;
}

.filter-select {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #dddfe2;
    font-size: 14px;
    color: #050505;
    background-color: #f0f2f5;
    outline: none;
}

.apply-filters-btn {
    width: 100%;
    padding: 8px 0;
    background-color: #0084ff;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    margin-top: 5px;
}

.apply-filters-btn:hover {
    background-color: #0073e6;
}

/* Styles pour les éléments filtrés */
.user-item.hidden {
    display: none;
}

.no-results {
    text-align: center;
    padding: 20px;
    color: #65676B;
    font-style: italic;
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
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 10px;
        }

        .message-actions {
            position: relative;
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .reaction-button {
            opacity: 1 !important;
            background: white !important;
            border: none;
            width: 30px !important;
            height: 30px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer;
            font-size: 16px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            transition: transform 0.2s !important;
            color: #666;
        }

        .delete-btn {
            background: none;
            border: none;
            color: #ff4444;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .delete-btn:hover, .reaction-button:hover {
            background-color: #f0f2f5 !important;
            transform: scale(1.1);
        }

        .message-content {
            background: white;
            padding: 12px;
            border-radius: 18px;
            position: relative;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            flex-grow: 1;
        }

        .reaction-picker {
            position: fixed !important;
            background-color: #ffffff !important;
            border-radius: 20px !important;
            padding: 5px 10px !important;
            display: none;
            align-items: center !important;
            gap: 5px !important;
            z-index: 9999 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
            border: 1px solid #ddd !important;
        }

        .reactions-row {
            display: flex !important;
            gap: 5px !important;
            padding: 0 !important;
        }

        .reaction-picker button {
            background: none !important;
            border: none !important;
            font-size: 20px !important;
            padding: 5px !important;
            cursor: pointer !important;
            border-radius: 50% !important;
            transition: transform 0.2s ease !important;
            width: 35px !important;
            height: 35px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .reaction-picker button:hover {
            transform: scale(1.2) !important;
            background-color: #f0f2f5 !important;
        }

        .reaction-button {
            background: none !important;
            border: none !important;
            color: #65676B !important;
            cursor: pointer !important;
            padding: 4px 8px !important;
            border-radius: 50% !important;
            transition: background-color 0.2s !important;
        }

        .reaction-button:hover {
            background-color: #f0f2f5 !important;
        }

        .message {
            position: relative !important;
        }

        .message-actions {
            position: absolute !important;
            right: -40px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 5px !important;
        }

        .reaction-picker {
            position: absolute;
            background: white;
            border-radius: 40px;
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            animation: slideIn 0.2s ease-out;
            min-width: auto;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .reaction-picker button {
            background: none;
            border: none;
            font-size: 22px;
            padding: 6px;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            margin: 0;
        }

        .reaction-picker button:hover {
            transform: scale(1.3) translateY(-4px);
            background: none;
        }

        .message-reactions {
            display: flex;
            gap: 4px;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            border-radius: 20px;
            padding: 4px 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .reaction-badge {
            font-size: 14px;
            padding: 2px 6px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            background: #f0f2f5;
            transition: background-color 0.2s;
        }

        .reaction-badge.active {
            background: #e7f3ff;
            color: #0084ff;
        }

        .sent .message-content {
            background: #0084ff;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .received .message-content {
            background: white;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .seen-status {
            font-size: 12px;
            color: #8e8e8e;
            margin-top: 4px;
            text-align: right;
            font-style: italic;
        }

        .message.sent .seen-status {
            margin-right: 10px;
        }

        .timestamp {
            display: block;
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

        .input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .emoji-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.2s;
            color: #65676B;
        }

        .emoji-button:hover {
            background-color: #f0f2f5;
        }

        .send-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.2s;
            color: #0084ff;
        }

        .send-button:hover {
            background-color: #f0f2f5;
        }

        .input-group {
            display: flex;
            gap: 8px;
            align-items: center;
            background: #f0f2f5;
            padding: 8px;
            border-radius: 20px;
        }

        #message {
            flex: 1;
            padding: 8px 12px;
            border: none;
            background: transparent;
            font-size: 14px;
            outline: none;
        }

        #message:focus {
            background: transparent;
        }

        .emoji-picker {
            position: absolute;
            bottom: 60px;
            left: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            max-width: 350px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .emoji {
            font-size: 24px;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .emoji:hover {
            background-color: #f0f2f5;
        }

        .chat-input {
            position: relative;
        }

        .photo-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-button:hover {
            background-color: #f0f2f5;
        }

        .photo-preview {
            position: relative;
            margin-top: 10px;
            max-width: 200px;
        }

        .photo-preview img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .remove-photo {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-photo:hover {
            background: #cc0000;
        }

        .message-photo {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 5px;
        }

        #error-message {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            max-width: 500px;
            background-color: #f8d7da;
            color: #842029;
            padding: 15px;
            border: 1px solid #f5c2c7;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        .unread-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: #ff0000;
            border-radius: 50%;
            margin-left: 5px;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(0.95); opacity: 1; }
        }

        .unread-message {
            font-weight: bold;
            color: #1a1a1a;
        }

        .user-info .last-message {
            color: #65676B;
            font-size: 0.9em;
            margin-top: 2px;
        }

        .user-info .last-message.unread {
            font-weight: bold;
            color: #1a1a1a;
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
          <li><a href="index.php?action=home">Home</a></li>
          <li><a href="index.php?action=home">About</a></li>
          <li><a href="index.php?action=home">Services</a></li>
          <li><a href="index.php?action=home">Portfolio</a></li>
          <li><a href="index.php?action=home">Team</a></li>
          <li><a href="index.php?action=chat" class="active">Chat</a></li>
          <li class="dropdown"><a href="index.php?action=home"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="index.php?action=home">Dropdown 1</a></li>
              <li class="dropdown"><a href="index.php?action=home"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="index.php?action=home">Deep Dropdown 1</a></li>
                  <li><a href="index.php?action=home">Deep Dropdown 2</a></li>
                  <li><a href="index.php?action=home">Deep Dropdown 3</a></li>
                  <li><a href="index.php?action=home">Deep Dropdown 4</a></li>
                  <li><a href="index.php?action=home">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="index.php?action=home">Dropdown 2</a></li>
              <li><a href="index.php?action=home">Dropdown 3</a></li>
              <li><a href="index.php?action=home">Dropdown 4</a></li>
            </ul>
          </li>
          <li class="listing-dropdown"><a href="index.php?action=home"><span>Listing Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li>
                <a href="index.php?action=home">Column 1 link 1</a>
                <a href="index.php?action=home">Column 1 link 2</a>
                <a href="index.php?action=home">Column 1 link 3</a>
              </li>
              <li>
                <a href="index.php?action=home">Column 2 link 1</a>
                <a href="index.php?action=home">Column 2 link 2</a>
                <a href="index.php?action=home">Column 3 link 3</a>
              </li>
              <li>
                <a href="index.php?action=home">Column 3 link 1</a>
                <a href="index.php?action=home">Column 3 link 2</a>
                <a href="index.php?action=home">Column 3 link 3</a>
              </li>
              <li>
                <a href="index.php?action=home">Column 4 link 1</a>
                <a href="index.php?action=home">Column 4 link 2</a>
                <a href="index.php?action=home">Column 4 link 3</a>
              </li>
              <li>
                <a href="index.php?action=home">Column 5 link 1</a>
                <a href="index.php?action=home">Column 5 link 2</a>
                <a href="index.php?action=home">Column 5 link 3</a>
              </li>
            </ul>
          </li>
          <li><a href="index.php?action=home">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted flex-md-shrink-0" href="index.php?action=logout">Déconnexion</a>

    </div>
  </header>

    <div class="chat-container">
        <div id="user-list">
            <h9>Bienvenue, <?php echo $_SESSION['username']; ?>!</h3>

            <!-- Barre de recherche avancée -->
            <div class="search-container">
                <div class="search-input-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="user-search" class="search-input" placeholder="Rechercher un utilisateur...">
                    <button id="advanced-search-toggle" class="advanced-search-btn" title="Recherche avancée">
                        <i class="bi bi-sliders"></i>
                    </button>
                </div>

                <!-- Options de recherche avancée (masquées par défaut) -->
                <div id="advanced-search-options" class="advanced-search-options" style="display: none;">
                    <div class="option-group">
                        <label>Statut</label>
                        <select id="status-filter" class="filter-select">
                            <option value="all">Tous</option>
                            <option value="online">En ligne</option>
                            <option value="unread">Messages non lus</option>
                        </select>
                    </div>
                    <div class="option-group">
                        <label>Trier par</label>
                        <select id="sort-filter" class="filter-select">
                            <option value="recent">Messages récents</option>
                            <option value="name">Nom</option>
                        </select>
                    </div>
                    <div class="option-group">
                        <label>Période</label>
                        <select id="time-filter" class="filter-select">
                            <option value="all">Tout</option>
                            <option value="today">Aujourd'hui</option>
                            <option value="week">Cette semaine</option>
                            <option value="month">Ce mois</option>
                        </select>
                    </div>
                    <button id="apply-filters" class="apply-filters-btn">Appliquer</button>
                </div>
            </div>

            <h9>Utilisateurs</h3>
            <ul id="users-list">
                <?php foreach ($users as $user): ?>
                    <li class="user-item"
                        data-username="<?= htmlspecialchars($user['username']) ?>"
                        data-last-message="<?= !empty($user['last_message_content']) ? htmlspecialchars($user['last_message_content']) : '' ?>"
                        data-last-time="<?= !empty($user['last_message_time']) ? htmlspecialchars($user['last_message_time']) : '' ?>"
                        data-unread="<?= $user['unread_count'] > 0 ? 'true' : 'false' ?>">
                        <a href="index.php?action=chat&user=<?= urlencode($user['username']) ?>">
                            <div class="user-info">
                                <strong><?= htmlspecialchars($user['username']) ?></strong>
                                <div class="last-message">
                                    <p><?= !empty($user['last_message_content']) ? substr($user['last_message_content'], 0, 20) . (strlen($user['last_message_content']) > 20 ? '...' : '') : 'Aucun message' ?></p>
                                    <small><?= !empty($user['last_message_time']) ? date('H:i', strtotime($user['last_message_time'])) : '' ?></small>
                                </div>
                            </div>
                            <?php if ($user['unread_count'] > 0): ?>
                                <span class="unread-dot"><?= $user['unread_count'] ?></span>
                            <?php endif; ?>
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
                    <div class="reaction-picker" style="display: none;">
                        <div class="reactions-row">
                            <button data-reaction="👍" title="Like">👍</button>
                            <button data-reaction="❤️" title="Love">❤️</button>
                            <button data-reaction="😂" title="Haha">😂</button>
                            <button data-reaction="😮" title="Wow">😮</button>
                            <button data-reaction="😢" title="Sad">😢</button>
                            <button data-reaction="😠" title="Angry">😠</button>
                        </div>
                    </div>
                    <?php include 'fetch_messages_styles_and_scripts.php'; ?>
                </div>
                <div class="chat-input">
                    <form id="messageForm" onsubmit="return sendMessage(event)" enctype="multipart/form-data">
                        <div class="input-group">
                            <button type="button" class="emoji-button" onclick="toggleEmojiPicker()">
                                <i class="bi bi-emoji-smile"></i>
                            </button>
                            <input type="text" id="message" name="message" placeholder="Écris un message...">
                            <label for="photo" class="photo-button">
                                <i class="bi bi-image"></i>
                                <input type="file" id="photo" name="photo" accept="image/*" style="display: none;" onchange="previewPhoto(this)">
                            </label>
                            <button type="submit" class="send-button">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                        <!-- Barre d'emojis -->
                        <div id="emoji-picker" class="emoji-picker" style="display: none;">
                            <span class="emoji" onclick="sendEmoji('😂')">😂</span>
                            <span class="emoji" onclick="sendEmoji('😍')">😍</span>
                            <span class="emoji" onclick="sendEmoji('😢')">😢</span>
                            <span class="emoji" onclick="sendEmoji('🔥')">🔥</span>
                            <span class="emoji" onclick="sendEmoji('👏')">👏</span>
                            <span class="emoji" onclick="sendEmoji('😡')">😡</span>
                            <span class="emoji" onclick="sendEmoji('😱')">😱</span>
                            <span class="emoji" onclick="sendEmoji('🥰')">🥰</span>
                            <span class="emoji" onclick="sendEmoji('💔')">💔</span>
                            <span class="emoji" onclick="sendEmoji('🤣')">🤣</span>
                            <span class="emoji" onclick="sendEmoji('😎')">😎</span>
                            <span class="emoji" onclick="sendEmoji('😅')">😅</span>
                            <span class="emoji" onclick="sendEmoji('😏')">😏</span>
                            <span class="emoji" onclick="sendEmoji('😇')">😇</span>
                            <span class="emoji" onclick="sendEmoji('🥲')">🥲</span>
                            <span class="emoji" onclick="sendEmoji('😜')">😜</span>
                            <span class="emoji" onclick="sendEmoji('🤔')">🤔</span>
                            <span class="emoji" onclick="sendEmoji('💯')">💯</span>
                        </div>
                        <div id="photo-preview" class="photo-preview" style="display: none;">
                            <img id="preview-image" src="" alt="Preview">
                            <button type="button" class="remove-photo" onclick="removePhoto()">×</button>
                        </div>
                    </form>
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

    <script>
        let selectedUser = "<?php echo $selectedUser ?? ''; ?>";
        let activeEditForm = null;
        let fetchInterval = null;
        let emojiPickerOpen = false;
        let syncInterval = null;
        let lastUpdateTime = Math.floor(Date.now() / 1000);

        function toggleEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            if (picker.style.display === 'none' || picker.style.display === '') {
                picker.style.display = 'flex';
                emojiPickerOpen = true;
            } else {
                picker.style.display = 'none';
                emojiPickerOpen = false;
            }
        }

        function sendEmoji(emoji) {
            const messageInput = document.getElementById('message');
            messageInput.value += emoji;
            messageInput.focus();
            // Fermer le picker après sélection
            document.getElementById('emoji-picker').style.display = 'none';
            emojiPickerOpen = false;
        }

        function startFetching() {
            if (fetchInterval) clearInterval(fetchInterval);
            fetchInterval = setInterval(() => {
                if (!emojiPickerOpen) {
                    fetchMessages();
                }
            }, 1000);
        }
        function stopFetching() {
            if (fetchInterval) clearInterval(fetchInterval);
            fetchInterval = null;
        }

        function fetchMessages(scrollAfter = true) {
            if (!selectedUser) return;

            const formData = new FormData();
            formData.append('sender', '<?php echo $_SESSION["username"]; ?>');
            formData.append('receiver', selectedUser);

            fetch('index.php?action=fetchMessages', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                const messagesDiv = document.getElementById('messages');
                if (messagesDiv) {
                    const wasAtBottom = isAtBottom();
                    messagesDiv.innerHTML = html;
                    if (scrollAfter && wasAtBottom) {
                        scrollToBottom();
                    }
                    // Marquer les messages comme vus après le chargement
                    markMessagesAsSeen();
                }
            })
            .catch(error => console.error('Erreur:', error));
        }

        function markMessagesAsSeen() {
            if (!selectedUser) return;

            fetch('index.php?action=markMessagesAsSeen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `sender=${encodeURIComponent(selectedUser)}&receiver=${encodeURIComponent('<?php echo $_SESSION["username"]; ?>')}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour les indicateurs "vu" dans l'interface
                    updateSeenIndicators();
                }
            })
            .catch(error => console.error('Erreur:', error));
        }

        function updateSeenIndicators() {
            const messages = document.querySelectorAll('.message.sent');
            messages.forEach(message => {
                const seenIndicator = message.querySelector('.seen-indicator');
                if (seenIndicator) {
                    seenIndicator.innerHTML = '<i class="bi bi-check-circle-fill"></i> Vu';
                }
            });
        }

        function previewPhoto(input) {
            const preview = document.getElementById('photo-preview');
            const previewImage = document.getElementById('preview-image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePhoto() {
            const input = document.getElementById('photo');
            const preview = document.getElementById('photo-preview');
            input.value = '';
            preview.style.display = 'none';
        }

        // Variable globale pour stocker les mots bannis
        let bannedWords = [];

        // Fonction pour charger les mots bannis depuis le serveur
        function loadBannedWords() {
            fetch("index.php?action=getBannedWords")
            .then(response => response.json())
            .then(data => {
                bannedWords = data;
                console.log("Mots bannis chargés:", bannedWords.length);
            })
            .catch(error => {
                console.error('Erreur lors du chargement des mots bannis:', error);
            });
        }

        // Charger les mots bannis au démarrage
        loadBannedWords();

        // Fonction pour vérifier si un message contient des mots bannis
        function checkForBannedWords(message) {
            if (!message || bannedWords.length === 0) return { valid: true };

            const lowerMessage = message.toLowerCase();
            const detectedWords = [];

            for (const banned of bannedWords) {
                const word = banned.word.toLowerCase();
                const regex = new RegExp('\\b' + word + '\\b', 'i');

                if (regex.test(lowerMessage)) {
                    detectedWords.push({
                        word: banned.word,
                        severity: banned.severity
                    });
                }
            }

            if (detectedWords.length === 0) {
                return { valid: true };
            }

            // Trouver la sévérité la plus élevée
            let highestSeverity = 'low';
            const severityLevels = { 'low': 1, 'medium': 2, 'high': 3 };

            for (const detected of detectedWords) {
                if (severityLevels[detected.severity] > severityLevels[highestSeverity]) {
                    highestSeverity = detected.severity;
                }
            }

            return {
                valid: false,
                detectedWords: detectedWords,
                highestSeverity: highestSeverity,
                message: `Le message contient le mot interdit "${detectedWords[0].word}"`
            };
        }

        function sendMessage(event) {
            event.preventDefault();
            const messageInput = document.getElementById("message");
            const message = messageInput.value.trim();
            const photoInput = document.getElementById("photo");

            if (message === "" && !photoInput.files[0]) return false;

            // Vérifier les mots bannis avant l'envoi (seulement si le message n'est pas vide)
            if (message && message.trim() !== '') {
                const checkResult = checkForBannedWords(message);
                if (!checkResult.valid) {
                    // Bloquer l'envoi de tout message contenant un mot banni, quelle que soit sa sévérité
                    showError(checkResult.message);
                    return false;
                }
            }

            // Si le message est vide mais qu'il y a une photo, c'est OK
            if (message.trim() === '' && !photoInput.files[0]) {
                showError("Veuillez saisir un message ou sélectionner une photo");
                return false;
            }

            const formData = new FormData();
            formData.append("receiver", selectedUser);
            formData.append("message", message);
            if (photoInput.files[0]) {
                formData.append("photo", photoInput.files[0]);
            }

            // Sauvegarder la position de défilement actuelle
            const wasAtBottom = isAtBottom();

            fetch("index.php?action=submitMessage", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = "";
                    removePhoto();

                    // Recharger les messages et forcer le défilement si on était en bas
                    fetchMessages(wasAtBottom);
                    syncUserList();
                } else {
                    showError(data.error || "Erreur lors de l'envoi du message");
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showError("Erreur lors de l'envoi du message");
            });

            return false;
        }

        function showError(message) {
            // Supprimer l'ancien message d'erreur s'il existe
            const oldError = document.getElementById('error-message');
            if (oldError) {
                oldError.remove();
            }

            // Créer le nouveau message d'erreur
            const div = document.createElement('div');
            div.id = 'error-message';
            div.className = 'alert alert-danger alert-dismissible fade show';
            div.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="flex-grow: 1;">
                        <i class="bi bi-exclamation-triangle-fill" style="margin-right: 10px;"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close" style="margin-left: 10px;" onclick="dismissError(this.parentElement.parentElement)"></button>
                </div>
            `;
            document.body.appendChild(div);

            // Faire disparaître le message après 5 secondes
            setTimeout(() => {
                dismissError(div);
            }, 5000);
        }

        function dismissError(errorDiv) {
            if (errorDiv) {
                errorDiv.classList.add('fade-out');
                setTimeout(() => {
                    errorDiv.remove();
                }, 300);
            }
        }

        function startEdit(messageId, content) {
            stopFetching();
            // Cacher tous les formulaires d'édition actifs
            document.querySelectorAll('.edit-form.show').forEach(form => {
                form.classList.remove('show');
            });
            // Afficher le formulaire d'édition pour ce message
            const editForm = document.getElementById(`edit-form-${messageId}`);
            if (editForm) {
                editForm.classList.add('show');
                editForm.querySelector('.edit-input').focus();
            }
        }

        function saveEdit(messageId) {
            const editForm = document.getElementById(`edit-form-${messageId}`);
            const newContent = editForm.querySelector('.edit-input').value.trim();
            if (newContent === '') return;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?action=updateMessage", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.success) {
                        fetchMessages();
                        startFetching();
                    } else {
                        alert('Erreur lors de la modification du message');
                    }
                }
            };
            xhr.send("message_id=" + messageId + "&content=" + encodeURIComponent(newContent));
        }

        function cancelEdit(messageId) {
            const editForm = document.getElementById(`edit-form-${messageId}`);
            if (editForm) {
                editForm.classList.remove('show');
            }
            startFetching();
        }

        function deleteMessage(messageId) {
            if (!confirm('Voulez-vous vraiment supprimer ce message ?')) return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?action=deleteMessage", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        if (response.success) {
                            fetchMessages();
                        } else {
                            alert(response.message || 'Erreur lors de la suppression du message');
                        }
                    } catch (e) {
                        alert('Erreur lors de la suppression du message');
                        console.error('Erreur de parsing JSON:', e);
                    }
                } else {
                    alert('Erreur lors de la communication avec le serveur');
                }
            };

            xhr.onerror = function() {
                alert('Erreur réseau lors de la suppression du message');
            };

            xhr.send("message_id=" + encodeURIComponent(messageId));
        }

        // Fonction pour synchroniser les messages en temps réel
        function syncMessages() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `index.php?action=getRealtimeUpdates&last_update=${lastUpdateTime}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        if (response.error) {
                            console.error("Erreur de synchronisation:", response.error);
                            return;
                        }

                        // Mettre à jour le timestamp
                        lastUpdateTime = response.timestamp;

                        // Traiter les nouveaux messages
                        if (response.new_messages && response.new_messages.length > 0) {
                            const messagesContainer = document.getElementById("messages");
                            response.new_messages.forEach(message => {
                                // Ajouter uniquement si le message n'existe pas déjà
                                if (!document.querySelector(`[data-message-id="${message.id}"]`)) {
                                    const messageElement = createMessageElement(message);
                                    messagesContainer.appendChild(messageElement);
                                }
                            });

                            // Faire défiler vers le bas si nécessaire
                            if (shouldScrollToBottom()) {
                                scrollToBottom();
                            }

                            // Mettre à jour la liste des utilisateurs
                            syncUserList();
                        }

                        // Marquer les messages comme lus
                        if (response.read_messages && response.read_messages.length > 0) {
                            response.read_messages.forEach(messageId => {
                                const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                                if (messageElement) {
                                    messageElement.classList.add('read');
                                }
                            });
                        }
                    } catch (e) {
                        console.error("Erreur lors du traitement de la réponse:", e);
                    }
                }
            };
            xhr.send();
        }

        // Fonction pour créer un élément de message
        function createMessageElement(message) {
            const div = document.createElement('div');
            div.className = `message ${message.sender_id === <?php echo $_SESSION['user_id']; ?> ? 'sent' : 'received'}`;
            div.setAttribute('data-message-id', message.id);

            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            contentDiv.textContent = message.message;

            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'message-actions';

            const reactionButton = document.createElement('button');
            reactionButton.className = 'reaction-button';
            reactionButton.innerHTML = '<i class="bi bi-emoji-smile"></i>';
            reactionButton.title = 'Réagir au message';
            reactionButton.onclick = (e) => {
                e.stopPropagation();
                showReactionPicker(message.id, reactionButton);
            };
            actionsDiv.appendChild(reactionButton);

            if (message.sender_id === <?php echo $_SESSION['user_id']; ?>) {
                const deleteButton = document.createElement('button');
                deleteButton.className = 'delete-btn';
                deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
                deleteButton.title = 'Supprimer le message';
                deleteButton.onclick = () => deleteMessage(message.id);
                actionsDiv.appendChild(deleteButton);
            }

            const reactionsDiv = document.createElement('div');
            reactionsDiv.className = 'message-reactions';
            reactionsDiv.setAttribute('data-message-id', message.id);

            const timeSpan = document.createElement('small');
            timeSpan.className = 'timestamp';
            timeSpan.textContent = new Date(message.created_at).toLocaleTimeString();

            contentDiv.appendChild(timeSpan);
            contentDiv.appendChild(reactionsDiv);

            div.appendChild(contentDiv);
            div.appendChild(actionsDiv);

            loadReactions(message.id);

            return div;
        }

        function showReactionPicker(messageId, button) {
            console.log('Showing reaction picker for message:', messageId); // Debug log

            const picker = document.querySelector('.reaction-picker');
            if (!picker) {
                console.error('Reaction picker not found!');
                return;
            }

            // Réinitialiser le style display
            picker.style.removeProperty('display');
            picker.style.display = 'flex';

            // Ajouter l'ID du message au picker
            picker.setAttribute('data-message-id', messageId);

            // Configurer les gestionnaires d'événements pour les boutons de réaction
            picker.querySelectorAll('button').forEach(btn => {
                const reaction = btn.getAttribute('data-reaction');
                btn.onclick = (e) => {
                    e.stopPropagation();
                    console.log('Reaction clicked:', reaction); // Debug log
                    addReaction(messageId, reaction);
                    picker.style.display = 'none';
                };
            });

            // Positionner le picker
            const buttonRect = button.getBoundingClientRect();
            console.log('Button position:', buttonRect); // Debug log

            // Positionner à droite du bouton
            const pickerLeft = buttonRect.right + 10;
            const pickerTop = buttonRect.top - (picker.offsetHeight / 2) + (buttonRect.height / 2);

            picker.style.left = `${pickerLeft}px`;
            picker.style.top = `${pickerTop}px`;

            console.log('Picker positioned at:', { left: pickerLeft, top: pickerTop }); // Debug log

            // Fermer le picker quand on clique en dehors
            const closePickerHandler = (e) => {
                if (!picker.contains(e.target) && e.target !== button) {
                    picker.style.display = 'none';
                    document.removeEventListener('click', closePickerHandler);
                }
            };

            // Retirer l'ancien gestionnaire avant d'en ajouter un nouveau
            document.removeEventListener('click', closePickerHandler);
            document.addEventListener('click', closePickerHandler);
        }

        function addReaction(messageId, reactionType) {
            fetch('index.php?action=addReaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message_id=${messageId}&reaction_type=${encodeURIComponent(reactionType)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadReactions(messageId);
                    const picker = document.querySelector('.reaction-picker');
                    if (picker) picker.remove();
                }
            });
        }

        function loadReactions(messageId) {
            fetch(`index.php?action=getReactions&message_id=${messageId}`)
            .then(response => response.json())
            .then(data => {
                const reactionsDiv = document.querySelector(`.message-reactions[data-message-id="${messageId}"]`);
                if (reactionsDiv) {
                    reactionsDiv.innerHTML = '';
                    const reactionCounts = {};
                    data.forEach(reaction => {
                        if (!reactionCounts[reaction.reaction_type]) {
                            reactionCounts[reaction.reaction_type] = {
                                count: 0,
                                users: [],
                                hasReacted: false
                            };
                        }
                        reactionCounts[reaction.reaction_type].count++;
                        reactionCounts[reaction.reaction_type].users.push(reaction.username);
                        if (reaction.user_id === <?php echo $_SESSION['user_id']; ?>) {
                            reactionCounts[reaction.reaction_type].hasReacted = true;
                        }
                    });

                    Object.entries(reactionCounts).forEach(([emoji, data]) => {
                        const badge = document.createElement('div');
                        badge.className = `reaction-badge ${data.hasReacted ? 'active' : ''}`;
                        badge.innerHTML = `${emoji} ${data.count}`;
                        badge.title = data.users.join(', ');
                        badge.onclick = () => {
                            if (data.hasReacted) {
                                removeReaction(messageId, emoji);
                            } else {
                                addReaction(messageId, emoji);
                            }
                        };
                        reactionsDiv.appendChild(badge);
                    });
                }
            });
        }

        function removeReaction(messageId, reactionType) {
            fetch('index.php?action=removeReaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message_id=${messageId}&reaction_type=${encodeURIComponent(reactionType)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadReactions(messageId);
                }
            });
        }

        // Fonction pour vérifier si on doit faire défiler vers le bas
        function shouldScrollToBottom() {
            const container = document.querySelector('.messages-container');
            return container.scrollHeight - container.scrollTop - container.clientHeight < 100;
        }

        // Fonction pour faire défiler vers le bas
        function scrollToBottom() {
            const container = document.querySelector('.messages-container');
            container.scrollTop = container.scrollHeight;
        }

        // Fonction optimisée pour synchroniser la liste des utilisateurs
        function syncUserList() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "index.php?action=chat&sync=1", true);
            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        if (response.users) {
                            updateUserList(response.users);
                        }
                    } catch (e) {
                        console.error("Erreur lors de la synchronisation de la liste des utilisateurs:", e);
                    }
                }
            };
            xhr.send();
        }

        // Fonction pour mettre à jour la liste des utilisateurs
        function updateUserList(users) {
            const userList = document.querySelector('#users-list');
            if (!userList) return;

            let newHtml = '';
            users.forEach(user => {
                const lastMessageTime = user.last_message_time ? new Date(user.last_message_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
                const lastMessage = user.last_message_content ?
                    (user.last_message_content.length > 20 ?
                        user.last_message_content.substring(0, 20) + '...' :
                        user.last_message_content) :
                    'Aucun message';
                const hasUnread = user.unread_count > 0;

                // Échapper les caractères spéciaux pour les attributs data-
                const safeUsername = user.username ? user.username.replace(/"/g, '&quot;') : '';
                const safeMessage = user.last_message_content ? user.last_message_content.replace(/"/g, '&quot;') : '';

                newHtml += `
                    <li class="user-item"
                        data-username="${safeUsername}"
                        data-last-message="${safeMessage}"
                        data-last-time="${user.last_message_time || ''}"
                        data-unread="${hasUnread ? 'true' : 'false'}">
                        <a href="index.php?action=chat&user=${encodeURIComponent(user.username)}">
                            <div class="user-info">
                                <strong>${user.username}</strong>
                                <div class="last-message ${hasUnread ? 'unread' : ''}">
                                    <p>${lastMessage}</p>
                                    <small>${lastMessageTime}</small>
                                </div>
                            </div>
                            ${hasUnread ? `<span class="unread-dot">${user.unread_count}</span>` : ''}
                        </a>
                    </li>
                `;
            });

            // Mettre à jour uniquement si le contenu a changé
            if (userList.innerHTML !== newHtml) {
                userList.innerHTML = newHtml;

                // Réappliquer les filtres actuels après la mise à jour
                if (document.getElementById('user-search').value.trim() !== '' ||
                    document.getElementById('status-filter').value !== 'all' ||
                    document.getElementById('time-filter').value !== 'all') {
                    // Simuler un événement de recherche pour réappliquer les filtres
                    const event = new Event('input');
                    document.getElementById('user-search').dispatchEvent(event);
                }
            }
        }

        // Fonction pour vérifier si on est en bas de la conversation
        function isAtBottom() {
            const container = document.querySelector('.messages-container');
            if (!container) return true;
            return Math.abs(container.scrollHeight - container.scrollTop - container.clientHeight) < 50;
        }

        // Démarrer la synchronisation
        function startSync() {
            fetchMessages(true);

            if (fetchInterval) clearInterval(fetchInterval);
            fetchInterval = setInterval(() => {
                fetchMessages(isAtBottom());
                updateSeenStatus(); // Mettre à jour le statut "vu"
            }, 1000);

            if (syncInterval) clearInterval(syncInterval);
            syncInterval = setInterval(syncUserList, 2000);
        }

        // Arrêter la synchronisation
        function stopSync() {
            if (fetchInterval) clearInterval(fetchInterval);
            if (syncInterval) clearInterval(syncInterval);
            fetchInterval = null;
            syncInterval = null;
        }

        function updateSeenStatus() {
            if (!selectedUser) return;

            fetch('index.php?action=getSeenStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `receiver=${encodeURIComponent(selectedUser)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messages = document.querySelectorAll('.message.sent');
                    const lastMessage = messages[messages.length - 1];
                    if (lastMessage) {
                        const seenStatus = lastMessage.querySelector('.seen-status');
                        if (seenStatus && data.seen) {
                            const seenTime = new Date(data.seen_at);
                            const now = new Date();
                            const diffMinutes = Math.floor((now - seenTime) / 1000 / 60);

                            if (diffMinutes < 1) {
                                seenStatus.textContent = 'Seen just now';
                            } else if (diffMinutes < 60) {
                                seenStatus.textContent = `Seen ${diffMinutes} minutes ago`;
                            } else {
                                seenStatus.textContent = `Seen ${seenTime.toLocaleTimeString()}`;
                            }
                        }
                    }
                }
            });
        }

        // Fonction pour la recherche avancée des utilisateurs
        function initAdvancedSearch() {
            const searchInput = document.getElementById('user-search');
            const advancedToggle = document.getElementById('advanced-search-toggle');
            const advancedOptions = document.getElementById('advanced-search-options');
            const applyFiltersBtn = document.getElementById('apply-filters');
            const usersList = document.getElementById('users-list');

            // Afficher/masquer les options avancées
            advancedToggle.addEventListener('click', () => {
                if (advancedOptions.style.display === 'none' || advancedOptions.style.display === '') {
                    advancedOptions.style.display = 'block';
                } else {
                    advancedOptions.style.display = 'none';
                }
            });

            // Recherche en temps réel sur le nom d'utilisateur
            searchInput.addEventListener('input', () => {
                filterUsers();
            });

            // Appliquer les filtres avancés
            applyFiltersBtn.addEventListener('click', () => {
                filterUsers();
                advancedOptions.style.display = 'none'; // Masquer les options après application
            });

            // Fonction pour filtrer les utilisateurs selon les critères
            function filterUsers() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const statusFilter = document.getElementById('status-filter').value;
                const sortFilter = document.getElementById('sort-filter').value;
                const timeFilter = document.getElementById('time-filter').value;

                const userItems = usersList.querySelectorAll('.user-item');
                let visibleCount = 0;

                // Supprimer le message "aucun résultat" s'il existe
                const noResults = usersList.querySelector('.no-results');
                if (noResults) {
                    noResults.remove();
                }

                // Filtrer les utilisateurs
                userItems.forEach(item => {
                    const username = item.getAttribute('data-username').toLowerCase();
                    const lastMessage = item.getAttribute('data-last-message') ? item.getAttribute('data-last-message').toLowerCase() : '';
                    const lastTime = item.getAttribute('data-last-time');
                    const hasUnread = item.getAttribute('data-unread') === 'true';

                    // Filtre par nom d'utilisateur ou contenu du message
                    let matchesSearch = false;

                    // Si le champ de recherche est vide, tout afficher
                    if (searchTerm === '') {
                        matchesSearch = true;
                    }
                    // Sinon, vérifier si le terme est dans le nom d'utilisateur
                    else if (username.includes(searchTerm)) {
                        matchesSearch = true;
                    }
                    // Ou si le terme est dans le contenu du message (seulement si le message existe)
                    else if (lastMessage && lastMessage !== '' && lastMessage.includes(searchTerm)) {
                        matchesSearch = true;
                    }

                    // Filtre par statut
                    let matchesStatus = true;
                    if (statusFilter === 'online') {
                        // Logique pour vérifier si l'utilisateur est en ligne
                        // Pour l'exemple, on considère que tous les utilisateurs sont en ligne
                        matchesStatus = true;
                    } else if (statusFilter === 'unread') {
                        matchesStatus = hasUnread;
                    }

                    // Filtre par période
                    let matchesTime = true;
                    if (lastTime && timeFilter !== 'all') {
                        const messageDate = new Date(lastTime);
                        const today = new Date();

                        if (timeFilter === 'today') {
                            matchesTime = messageDate.toDateString() === today.toDateString();
                        } else if (timeFilter === 'week') {
                            const weekAgo = new Date();
                            weekAgo.setDate(today.getDate() - 7);
                            matchesTime = messageDate >= weekAgo;
                        } else if (timeFilter === 'month') {
                            const monthAgo = new Date();
                            monthAgo.setMonth(today.getMonth() - 1);
                            matchesTime = messageDate >= monthAgo;
                        }
                    }

                    // Afficher ou masquer l'élément selon les filtres
                    if (matchesSearch && matchesStatus && matchesTime) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Trier les utilisateurs
                if (sortFilter === 'name') {
                    sortUsersByName();
                } else if (sortFilter === 'recent') {
                    sortUsersByRecent();
                }

                // Afficher un message si aucun résultat
                if (visibleCount === 0) {
                    const noResultsMsg = document.createElement('li');
                    noResultsMsg.className = 'no-results';
                    noResultsMsg.textContent = 'Aucun utilisateur ne correspond à votre recherche';
                    usersList.appendChild(noResultsMsg);
                }
            }

            // Fonction pour trier les utilisateurs par nom
            function sortUsersByName() {
                const userItems = Array.from(usersList.querySelectorAll('.user-item:not(.hidden)'));
                userItems.sort((a, b) => {
                    const nameA = a.getAttribute('data-username').toLowerCase();
                    const nameB = b.getAttribute('data-username').toLowerCase();
                    return nameA.localeCompare(nameB);
                });

                // Réorganiser les éléments dans la liste
                userItems.forEach(item => {
                    usersList.appendChild(item);
                });
            }

            // Fonction pour trier les utilisateurs par date de dernier message
            function sortUsersByRecent() {
                const userItems = Array.from(usersList.querySelectorAll('.user-item:not(.hidden)'));
                userItems.sort((a, b) => {
                    const timeA = a.getAttribute('data-last-time') || '1970-01-01';
                    const timeB = b.getAttribute('data-last-time') || '1970-01-01';
                    return new Date(timeB) - new Date(timeA);
                });

                // Réorganiser les éléments dans la liste
                userItems.forEach(item => {
                    usersList.appendChild(item);
                });
            }
        }

        // Fonction pour vérifier le message en temps réel
        function checkMessageInput() {
            const messageInput = document.getElementById("message");
            const message = messageInput.value.trim();

            if (message) {
                const checkResult = checkForBannedWords(message);
                if (!checkResult.valid) {
                    // Afficher un avertissement sous le champ de saisie
                    let warningElement = document.getElementById("banned-word-warning");
                    if (!warningElement) {
                        warningElement = document.createElement("div");
                        warningElement.id = "banned-word-warning";
                        warningElement.className = "banned-word-warning";
                        messageInput.parentNode.appendChild(warningElement);
                    }

                    // Message d'avertissement pour tous les mots bannis
                    let warningMessage = `<i class="bi bi-exclamation-triangle-fill"></i> Le mot "${checkResult.detectedWords[0].word}" est interdit et ne peut pas être envoyé.`;

                    // Appliquer la classe selon la sévérité (pour le style visuel)
                    warningElement.className = "banned-word-warning " + checkResult.highestSeverity;

                    warningElement.innerHTML = warningMessage;
                    return false;
                }
            }

            // Supprimer l'avertissement s'il n'y a pas de mot banni
            const warningElement = document.getElementById("banned-word-warning");
            if (warningElement) {
                warningElement.remove();
            }

            return true;
        }

        document.addEventListener("DOMContentLoaded", () => {
            const messageInput = document.getElementById("message");

            // Vérifier à chaque touche pressée
            messageInput.addEventListener("input", checkMessageInput);

            // Envoyer le message avec Enter
            messageInput.addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    sendMessage(e);
                }
            });

            // Ajouter des styles pour l'avertissement de mot banni
            const style = document.createElement('style');
            style.textContent = `
                .banned-word-warning {
                    padding: 8px 12px;
                    margin-top: 5px;
                    border-radius: 4px;
                    font-size: 14px;
                    display: flex;
                    align-items: center;
                    animation: fadeIn 0.3s;
                }

                .banned-word-warning i {
                    margin-right: 8px;
                }

                .banned-word-warning.low {
                    background-color: #fff3cd;
                    color: #856404;
                    border: 1px solid #ffeeba;
                }

                .banned-word-warning.medium {
                    background-color: #f8d7da;
                    color: #721c24;
                    border: 1px solid #f5c6cb;
                }

                .banned-word-warning.high {
                    background-color: #dc3545;
                    color: white;
                    border: 1px solid #dc3545;
                    font-weight: bold;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(style);

            // Initialiser la recherche avancée
            initAdvancedSearch();

            // Démarrer la synchronisation
            startSync();

            // Synchroniser immédiatement
            syncMessages();
            syncUserList();

            // Faire défiler vers le bas au chargement
            scrollToBottom();
        });

        // Arrêter la synchronisation quand l'utilisateur quitte la page
        window.addEventListener('beforeunload', stopSync);
    </script>
</body>
</html>