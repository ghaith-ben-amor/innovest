# Instructions pour exécuter la migration receiver_id

Vous rencontrez une erreur car la colonne `receiver_id` n'existe pas dans votre table `notifications`. Voici comment résoudre ce problème :

## Option 1 : Via phpMyAdmin

1. Ouvrez phpMyAdmin (généralement accessible via http://localhost/phpmyadmin)
2. Sélectionnez la base de données `chat`
3. Cliquez sur l'onglet "SQL"
4. Copiez et collez la requête SQL suivante :

```sql
ALTER TABLE notifications ADD COLUMN receiver_id INT NOT NULL AFTER message_id;
```

5. Cliquez sur "Exécuter"

## Option 2 : Via le script de migration

Si vous préférez utiliser le script de migration PHP, exécutez la commande suivante dans votre terminal :

```bash
php c:/xampp/htdocs/test_chat/migrations/update_notifications_table.php
```

Ce script est plus complet car il :
1. Vérifie si la colonne existe déjà avant de l'ajouter
2. Met à jour les enregistrements existants avec les valeurs correctes de receiver_id

## Vérification

Après avoir exécuté la migration, vous pouvez vérifier que la colonne a bien été ajoutée en consultant la structure de la table `notifications` dans phpMyAdmin.

Une fois la migration effectuée, l'erreur "Unknown column 'receiver_id' in 'where clause'" devrait disparaître et le système de notifications fonctionnera correctement.

## Note importante

Cette migration est nécessaire car le système de notifications utilise la colonne `receiver_id` pour identifier l'utilisateur qui doit recevoir la notification, mais cette colonne n'était pas présente dans la structure initiale de la table.