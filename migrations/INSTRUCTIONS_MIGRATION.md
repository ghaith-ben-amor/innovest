# Instructions pour exécuter la migration

Vous rencontrez une erreur car la colonne `is_read` n'existe pas dans votre table `messages`. Voici comment résoudre ce problème :

## Option 1 : Via phpMyAdmin

1. Ouvrez phpMyAdmin (généralement accessible via http://localhost/phpmyadmin)
2. Sélectionnez la base de données `chat`
3. Cliquez sur l'onglet "SQL"
4. Copiez et collez la requête SQL suivante :

```sql
ALTER TABLE messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER has_offensive_content;
```

5. Cliquez sur "Exécuter"

## Option 2 : Via le script de migration

Si vous préférez utiliser le script de migration PHP, exécutez la commande suivante dans votre terminal :

```bash
php c:/xampp/htdocs/test_chat/migrations/add_is_read_column.php
```

## Vérification

Après avoir exécuté la migration, vous pouvez vérifier que la colonne a bien été ajoutée en consultant la structure de la table `messages` dans phpMyAdmin.

Une fois la migration effectuée, l'erreur "Unknown column 'is_read' in 'where clause'" devrait disparaître et le système de notifications pour messages non lus fonctionnera correctement.