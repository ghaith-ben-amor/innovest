<?php
// Prepare reaction counts for all messages
$messageIds = array_column($messages, 'id');
$reactionCounts = [];
if (!empty($messageIds)) {
    $msgModel = new Message();
    $reactionCounts = $msgModel->getReactionCountsByMessageIds($messageIds);
}
$userId = $_SESSION['username'];
?>

<?php foreach ($messages as $msg): ?>
    <?php
        $isSent = ($msg['sender'] === $userId);
        $class = $isSent ? 'sent' : 'received';
        $userReaction = null;
        foreach ($msg['reactions'] as $reaction) {
            if ($reaction['user_id'] === $userId) {
                $userReaction = $reaction['reaction_type'];
                break;
            }
        }
        $msgReactionCounts = $reactionCounts[$msg['id']] ?? [];
        $emojis = ['�', '❤️', '�', '�', '�', '�'];
    ?>
    <div class="message-container <?php echo $class; ?>">
        <div class="message <?php echo $class; ?>" data-message-id="<?php echo $msg['id']; ?>">
            <!-- Barre de réactions style Instagram -->
            <div class="instagram-reaction-bar" id="emoji-picker-<?php echo $msg['id']; ?>">
                <?php foreach ($emojis as $emoji): ?>
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '<?php echo $emoji; ?>')"><?php echo $emoji; ?></span>
                <?php endforeach; ?>
            </div>

            <div class="message-content">
                <div class="message-text">
                    <?php echo htmlspecialchars($msg['message']); ?>
                </div>

                <div class="message-reactions" id="reactions-<?php echo $msg['id']; ?>">
                    <?php
                    // Afficher les réactions existantes
                    if (!empty($msg['reactions'])) {
                        $reactionCounts = [];
                        foreach ($msg['reactions'] as $reaction) {
                            if (!isset($reactionCounts[$reaction['reaction_type']])) {
                                $reactionCounts[$reaction['reaction_type']] = 0;
                            }
                            $reactionCounts[$reaction['reaction_type']]++;
                        }

                        foreach ($reactionCounts as $type => $count) {
                            $userReactionClass = ($userReaction === $type) ? ' user-reaction' : '';
                            echo '<span class="reaction-bubble' . $userReactionClass . '">' . htmlspecialchars($type) . ' <span class="reaction-count">' . $count . '</span></span>';
                        }
                    }
                    ?>
                </div>

                <span class="timestamp">
                    <?php echo $msg['created_at']; ?>
                </span>
            </div>
        </div>

        <div class="buttons-container">
            <button class="reaction-btn" onclick="toggleEmojiPicker(<?php echo $msg['id']; ?>)" title="Ajouter une réaction">
                <i class="bi bi-emoji-smile"></i>
            </button>
            <?php if ($isSent): ?>
                <button class="action-btn delete-btn" onclick="deleteMessage(<?php echo $msg['id']; ?>)" title="Supprimer le message">
                    <i class="bi bi-trash-fill"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
