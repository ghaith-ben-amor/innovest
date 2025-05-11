# Système de Notifications pour Messages Non Lus

Ce document explique comment le système de notifications pour les messages non lus a été implémenté et comment l'utiliser dans votre application de chat.

## Modifications apportées

1. **Base de données**
   - Ajout d'une colonne `is_read` à la table `messages` (via le script de migration `migrations/add_is_read_column.php`)

2. **Modèle Message**
   - Ajout de méthodes pour gérer les messages non lus:
     - `markMessagesAsRead`: Marque les messages comme lus
     - `countUnreadMessages`: Compte le nombre de messages non lus
     - `getDiscussionsWithUnreadCount`: Récupère les discussions avec le nombre de messages non lus

3. **Interface utilisateur**
   - Création d'un badge pour afficher le nombre de messages non lus
   - Mise à jour automatique du badge via JavaScript
   - Marquage automatique des messages comme lus lors de l'ouverture d'une conversation

## Comment utiliser le système

### 1. Exécuter la migration

Pour ajouter la colonne `is_read` à votre base de données, exécutez le script de migration:

```php
php migrations/add_is_read_column.php
```

### 2. Intégrer le badge de notifications dans votre interface

Pour afficher le badge de messages non lus, incluez le fichier `unread_messages_badge.php` dans votre barre de navigation ou tout autre élément de votre interface:

```php
<a href="chat.php">
  Messages <span class="unread-messages-badge">
    <?php include 'views/chat/unread_messages_badge.php'; ?>
  </span>
</a>
```

### 3. Inclure le script JavaScript

Ajoutez le script JavaScript pour mettre à jour automatiquement le badge:

```html
<script src="views/chat/unread_messages.js"></script>
```

### 4. Marquer les messages comme lus

Lorsqu'un utilisateur ouvre une conversation, les messages doivent être marqués comme lus. Ajoutez cet appel JavaScript:

```javascript
// Lorsqu'une conversation est ouverte avec l'utilisateur ayant l'ID otherUserId
markMessagesAsRead(otherUserId);
```

## Exemple complet

Un exemple d'intégration complète est disponible dans le fichier `views/chat/navbar_with_notifications.php`.

## Fonctionnalités supplémentaires

- Le badge est mis à jour toutes les 30 secondes
- Les notifications sont automatiquement marquées comme vues lorsque les messages sont lus
- Le système utilise AJAX pour éviter de recharger la page

## Personnalisation

Vous pouvez personnaliser l'apparence du badge en modifiant les classes CSS dans `unread_messages_badge.php`.