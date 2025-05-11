-- Script SQL pour ajouter la colonne is_read à la table messages
ALTER TABLE messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER has_offensive_content;