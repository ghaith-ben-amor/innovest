<?php foreach ($messages as $msg): ?>
    <?php
        $isSent = ($msg['sender'] === $_SESSION['username']);
        $class = $isSent ? 'sent' : 'received';
    ?>
    <div class="message <?php echo $class; ?>" data-message-id="<?php echo $msg['id']; ?>">
        <div class="message-content">
            <div class="message-text">
                <?php echo htmlspecialchars($msg['content']); ?>
            </div>
            <div class="message-reactions">
                <?php if (!empty($msg['reactions'])): ?>
                    <?php
                        $reactionCounts = [];
                        foreach ($msg['reactions'] as $reaction) {
                            $reactionType = $reaction['reaction_type'];
                            if (!isset($reactionCounts[$reactionType])) {
                                $reactionCounts[$reactionType] = 0;
                            }
                            $reactionCounts[$reactionType]++;
                        }
                    ?>
                    <?php foreach ($reactionCounts as $type => $count): ?>
                        <span class="reaction" data-reaction-type="<?php echo htmlspecialchars($type); ?>">
                            <?php echo htmlspecialchars($type); ?> <?php echo $count > 1 ? $count : ''; ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
                <button class="add-reaction-btn" onclick="toggleReactionPicker(event, <?php echo $msg['id']; ?>)">+</button>
                <div class="reaction-picker" id="reaction-picker-<?php echo $msg['id']; ?>" style="display:none;">
                    <?php
                        $reactionTypes = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];
                        foreach ($reactionTypes as $reactionType):
                    ?>
                        <span class="reaction-option" onclick="reactToMessage(<?php echo $msg['id']; ?>, '<?php echo $reactionType; ?>')">
                            <?php echo $reactionType; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <span class="timestamp">
                <?php echo $msg['created_at']; ?>
            </span>
        </div>
        <?php if ($isSent): ?>
            <button class="action-btn delete-btn" onclick="deleteMessage(<?php echo $msg['id']; ?>)">
                <i class="bi bi-trash-fill"></i>
            </button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<style>
.message-reactions {
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.reaction {
    background-color: #e4e6eb;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
    cursor: default;
    user-select: none;
}

.add-reaction-btn {
    background-color: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    user-select: none;
}

.reaction-picker {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 5px;
    display: flex;
    gap: 5px;
    z-index: 1000;
}

.reaction-option {
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 6px;
    background-color: #f0f2f5;
    user-select: none;
    transition: background-color 0.2s ease;
}

.reaction-option:hover {
    background-color: #d8dadf;
}
</style>

<script>
function toggleReactionPicker(event, messageId) {
    event.stopPropagation();
    const picker = document.getElementById('reaction-picker-' + messageId);
    if (picker.style.display === 'none' || picker.style.display === '') {
        // Hide any other open pickers
        document.querySelectorAll('.reaction-picker').forEach(p => p.style.display = 'none');
        picker.style.display = 'flex';
    } else {
        picker.style.display = 'none';
    }
}

document.addEventListener('click', function() {
    document.querySelectorAll('.reaction-picker').forEach(p => p.style.display = 'none');
});

function reactToMessage(messageId, reactionType) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?action=addOrUpdateReaction', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.success) {
                // Refresh messages to show updated reactions
                fetchMessages();
            } else {
                alert('Erreur lors de l\'ajout de la réaction');
            }
        }
    };
    xhr.send('message_id=' + messageId + '&reaction_type=' + encodeURIComponent(reactionType));
}
</script>

<style>
.message {
    position: relative;
    margin-bottom: 15px;
    padding: 10px 50px 10px 15px;
    border-radius: 8px;
    max-width: 70%;
    display: flex;
    align-items: center;
    gap: 15px;
}

.message.sent {
    background-color: #e3f2fd;
    margin-left: auto;
}

.message.received {
    background-color: #f5f5f5;
    margin-right: auto;
}

.message-content {
    position: relative;
    flex: 1;
}

.message-text {
    word-break: break-word;
}

.action-btn {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    flex-shrink: 0;
}

.action-btn i {
    font-size: 18px;
    transition: transform 0.3s ease;
}

.action-btn:hover i {
    transform: scale(1.2);
}

.delete-btn {
    background-color: #f44336;
    color: white;
}

.delete-btn:hover {
    background-color: #D32F2F;
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
}

.timestamp {
    display: block;
    font-size: 11px;
    margin-top: 4px;
    opacity: 0.7;
}
</style>

<script>
function deleteMessage(messageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "index.php?action=deleteMessage", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (this.status === 200) {
                const response = JSON.parse(this.responseText);
                if (response.success) {
                    fetchMessages();
                } else {
                    alert('Erreur lors de la suppression du message');
                }
            }
        };
        xhr.send("message_id=" + messageId);
    }
}
</script>
