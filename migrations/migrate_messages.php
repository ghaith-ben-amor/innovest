<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Insert unique discussions
    $sqlDiscussions = "
        INSERT INTO discussions (user1_id, user2_id, created_at)
        SELECT DISTINCT
            LEAST(u1.iduser, u2.iduser) AS user1_id,
            GREATEST(u1.iduser, u2.iduser) AS user2_id,
            NOW()
        FROM messages m
        JOIN users u1 ON m.sender = u1.username
        JOIN users u2 ON m.receiver = u2.username
        WHERE NOT EXISTS (
            SELECT 1 FROM discussions d
            WHERE (d.user1_id = LEAST(u1.iduser, u2.iduser) AND d.user2_id = GREATEST(u1.iduser, u2.iduser))
        )
    ";
    $conn->exec($sqlDiscussions);

    // 2. Migrate messages to new messages table
    $sqlMessages = "
        INSERT INTO messages_new (discussion_id, sender_id, message, created_at)
        SELECT d.id, u.iduser, m.content, m.created_at
        FROM messages m
        JOIN users u ON m.sender = u.username
        JOIN users u2 ON m.receiver = u2.username
        JOIN discussions d ON d.user1_id = LEAST(u.iduser, u2.iduser) AND d.user2_id = GREATEST(u.iduser, u2.iduser)
    ";
    $conn->exec($sqlMessages);

    // 3. Drop old messages table and rename new messages table
    $conn->exec("DROP TABLE messages");
    $conn->exec("RENAME TABLE messages_new TO messages");

    echo "Migration completed successfully.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>
