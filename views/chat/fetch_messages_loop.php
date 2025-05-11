<?php foreach ($messages as $msg): ?>
    <?php
        $isSent = ($msg['sender'] === $_SESSION['username']);
        $class = $isSent ? 'sent' : 'received';
        $userId = $_SESSION['username'];
        $userReaction = null;
        foreach ($msg['reactions'] as $reaction) {
            if ($reaction['user_id'] === $userId) {
                $userReaction = $reaction['reaction_type'];
                break;
            }
        }
        $msgReactionCounts = $reactionCounts[$msg['id']] ?? [];
        $emojis = ['😀', '😂', '😍', '😢', '👍', '👎', '❤️', '🎉'];
    ?>
    <div class="message-container <?php echo $class; ?>">
        <div class="message" data-message-id="<?php echo $msg['id']; ?>">
            <div class="message-content">
                <div class="message-text">
                    <?php echo htmlspecialchars($msg['content']); ?>
                </div>
                <span class="timestamp">
                    <?php echo $msg['created_at']; ?>
                </span>
                <div class="reactions">
                    <?php foreach ($emojis as $emoji):
                        $count = $msgReactionCounts[$emoji] ?? 0;
                        $isUserReaction = ($userReaction === $emoji);
                    ?>
                        <span class="reaction-emoji <?php echo $isUserReaction ? 'my-reaction' : ''; ?>"
                              data-message-id="<?php echo $msg['id']; ?>"
                              data-reaction-type="<?php echo htmlspecialchars($emoji); ?>"
                              onclick="selectEmoji(<?php echo $msg['id']; ?>, '<?php echo $emoji; ?>')">
                            <?php echo $emoji; ?>
                            <?php if ($count > 0): ?>
                                <span class="reaction-count"><?php echo $count; ?></span>
                            <?php endif; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="emoji-picker" id="emoji-picker-<?php echo $msg['id']; ?>" style="display:none;">
                <?php foreach ($emojis as $emoji): ?>
                    <span class="emoji" onclick="selectEmoji(<?php echo $msg['id']; ?>, '<?php echo $emoji; ?>')"><?php echo $emoji; ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="buttons-container">
            <button class="reaction-btn" onclick="toggleEmojiPicker(<?php echo $msg['id']; ?>)">&#x1F60A;</button>
            <?php if ($isSent): ?>
                <button class="action-btn delete-btn" onclick="deleteMessage(<?php echo $msg['id']; ?>)">
                    <i class="bi bi-trash-fill"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
