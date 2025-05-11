<style>
    .message-container {
        position: relative;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    /* Conteneur pour les boutons d'action */
    .message-container .buttons-container {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .message-container.sent {
        justify-content: flex-end;
    }

    .message-container.received {
        justify-content: flex-start;
    }

    .message {
        background-color: #f5f5f5;
        padding: 8px 15px;
        border-radius: 20px;
        max-width: 70%;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sent .message {
        background-color: #e3f2fd;
    }

    .message-content {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .message-text {
        word-break: break-word;
        color: #333;
        font-size: 14px;
    }

    .my-reaction-emoji {
        font-size: 20px;
        cursor: pointer;
        user-select: none;
        padding: 2px 6px;
        border-radius: 12px;
        background-color: #0084ff;
        color: white;
        font-weight: bold;
        width: fit-content;
    }

    .reaction-btn {
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        cursor: pointer;
        user-select: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
        opacity: 1 !important; /* Toujours visible */
        transform: translateX(0) !important; /* Pas de translation */
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .emoji-picker {
        position: absolute;
        bottom: 40px;
        right: 80px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 5px;
        display: flex;
        gap: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 1000;
    }

    .emoji {
        font-size: 20px;
        cursor: pointer;
        user-select: none;
        padding: 2px 4px;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .emoji:hover {
        background-color: #eee;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        flex-shrink: 0;
        opacity: 0;
        transform: translateX(10px);
    }

    .message-container:hover .action-btn {
        opacity: 0.8;
        transform: translateX(0);
    }

    .action-btn:hover {
        opacity: 1 !important;
        transform: scale(1.1) !important;
    }

    .delete-btn {
        background-color: #f44336;
        color: white;
    }

    .delete-btn:hover {
        background-color: #D32F2F;
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
    }

    .reaction-btn:hover {
        background-color: #388E3C;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    }

    .timestamp {
        font-size: 11px;
        color: #666;
        margin-left: 8px;
    }
    .notification-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
        margin-left: 6px;
        vertical-align: middle;
    }
</style>

<script>
function toggleEmojiPicker(messageId) {
    const picker = document.getElementById('emoji-picker-' + messageId);
    if (picker.style.display === 'none' || picker.style.display === '') {
        // Hide all other pickers
        document.querySelectorAll('.emoji-picker').forEach(p => p.style.display = 'none');
        picker.style.display = 'flex';

        // Ensure the picker is positioned correctly
        const messageContainer = picker.closest('.message-container');
        if (messageContainer) {
            const messageElement = messageContainer.querySelector('.message');
            if (messageElement) {
                // Position the picker relative to the message element
                const messageRect = messageElement.getBoundingClientRect();

                // Adjust position based on viewport
                setTimeout(() => {
                    const pickerRect = picker.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    const viewportWidth = window.innerWidth;

                    // Adjust vertical position if needed
                    if (pickerRect.bottom > viewportHeight) {
                        picker.style.bottom = 'auto';
                        picker.style.top = '-60px';
                    }

                    // Adjust horizontal position if needed
                    if (pickerRect.right > viewportWidth) {
                        picker.style.right = '0';
                        picker.style.left = 'auto';
                    }
                }, 0);
            }
        }

        window.emojiPickerOpen = true;
    } else {
        picker.style.display = 'none';
        window.emojiPickerOpen = false;
    }
}

function selectEmoji(messageId, emoji) {
    const userReactionDiv = document.querySelector(`.my-reaction-emoji[data-message-id="${messageId}"]`);
    if (userReactionDiv && userReactionDiv.textContent === emoji) {
        // Remove reaction
        removeReaction(messageId);
    } else {
        // Add or update reaction
        addOrUpdateReaction(messageId, emoji);
    }
    toggleEmojiPicker(messageId);
}

function addOrUpdateReaction(messageId, reactionType) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=addOrUpdateReaction", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.success) {
                // Update reaction emoji in DOM directly without full reload
                const userReactionDiv = document.querySelector(`.my-reaction-emoji[data-message-id="${messageId}"]`);
                if (userReactionDiv) {
                    userReactionDiv.textContent = reactionType;
                } else {
                    // If no reaction div, create one
                    const messageContent = document.querySelector(`.message[data-message-id="${messageId}"] .message-content`);
                    if (messageContent) {
                        const newReactionDiv = document.createElement('div');
                        newReactionDiv.className = 'my-reaction-emoji';
                        newReactionDiv.setAttribute('data-message-id', messageId);
                        newReactionDiv.textContent = reactionType;
                        messageContent.appendChild(newReactionDiv);
                    }
                }
                emojiPickerOpen = false;
                toggleEmojiPicker(messageId);
            } else {
                alert('Erreur lors de l\'ajout de la réaction');
            }
        }
    };
    xhr.send("message_id=" + messageId + "&reaction_type=" + encodeURIComponent(reactionType));
}

function removeReaction(messageId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=removeReaction", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.success) {
                fetchMessages();
            } else {
                alert('Erreur lors de la suppression de la réaction');
            }
        }
    };
    xhr.send("message_id=" + messageId);
}
</script>
