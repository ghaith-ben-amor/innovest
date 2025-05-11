<?php
error_log("Messages to display: " . print_r($messages, true));
error_log("Session data in fetch_messages: " . print_r($_SESSION, true));

// Récupérer l'ID de l'utilisateur
$userId = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    error_log("User ID found in session: " . $userId);
} else {
    error_log("User ID not found in session, trying to get it from database");
    $userModel = new User();
    $user = $userModel->getByUsername($_SESSION['username']);
    if ($user) {
        $userId = $user['iduser'];
        $_SESSION['user_id'] = $userId;
        error_log("User ID retrieved from database: " . $userId);
    } else {
        error_log("Could not find user in database");
    }
}

if (!$userId) {
    error_log("No user ID available, messages will not be displayed correctly");
}
?>
<?php if (empty($messages)): ?>
    <div class="no-messages">
        <p>Aucun message pour le moment</p>
    </div>
<?php else: ?>
    <?php foreach ($messages as $msg): ?>
        <?php
            $isSent = ($userId && $msg['sender_id'] === $userId);
            $class = $isSent ? 'sent' : 'received';

            // Debug log pour les photos
            if (isset($msg['photo']) && $msg['photo']) {
                error_log("Photo information: " . print_r($msg['photo'], true));
            }
        ?>
        <div class="message-container <?php echo $class; ?>">
            <div class="message <?php echo $class; ?>" data-message-id="<?php echo $msg['id']; ?>">
                <!-- Barre de réactions style Instagram -->
                <div class="instagram-reaction-bar" id="emoji-picker-<?php echo $msg['id']; ?>">
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '❤️')">❤️</span>
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '😂')">😂</span>
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '😮')">😮</span>
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '😢')">😢</span>
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '😡')">😡</span>
                    <span class="instagram-emoji-btn" onclick="selectEmoji(<?php echo $msg['id']; ?>, '👍')">👍</span>
                </div>

                <div class="message-content">
                    <?php if (!empty($msg['message']) && trim($msg['message']) !== ' '): ?>
                        <div class="message-text">
                            <?php echo htmlspecialchars($msg['message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($msg['photo']) && $msg['photo']): ?>
                        <div class="message-photo">
                            <?php
                            error_log("=== AFFICHAGE DE L'IMAGE ===");
                            error_log("Données de la photo : " . print_r($msg['photo'], true));

                            // Le chemin est déjà au format web dans la base de données
                            $photoPath = $msg['photo']['file_path'];
                            error_log("Chemin de l'image : " . $photoPath);

                            // Vérifier si le fichier existe physiquement
                            $physicalPath = __DIR__ . '/../../' . ltrim($photoPath, '/');
                            error_log("Chemin physique : " . $physicalPath);
                            error_log("Le fichier existe ? : " . (file_exists($physicalPath) ? 'Oui' : 'Non'));
                            ?>
                            <img src="<?php echo htmlspecialchars($photoPath); ?>"
                                 alt="Photo"
                                 onclick="openImageInFullscreen(this.src)"
                                 onerror="this.onerror=null; handleImageError(this);"
                                 style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 8px;">
                        </div>
                    <?php endif; ?>

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
                                echo '<span class="reaction-bubble">' . htmlspecialchars($type) . ' <span class="reaction-count">' . $count . '</span></span>';
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
<?php endif; ?>

<script>
function openImageInFullscreen(imageSrc) {
    const modal = document.createElement('div');
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
    modal.style.display = 'flex';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    modal.style.zIndex = '9999';
    modal.onclick = () => document.body.removeChild(modal);

    const img = document.createElement('img');
    img.src = imageSrc;
    img.style.maxWidth = '90%';
    img.style.maxHeight = '90%';
    img.style.objectFit = 'contain';

    modal.appendChild(img);
    document.body.appendChild(modal);
}

function handleImageError(img) {
    console.error('Error loading image:', img.src);
    img.style.display = 'none';
    const errorDiv = document.createElement('div');
    errorDiv.className = 'image-error';
    errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Erreur de chargement de l\'image';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.padding = '10px';
    errorDiv.style.backgroundColor = '#f8d7da';
    errorDiv.style.border = '1px solid #f5c6cb';
    errorDiv.style.borderRadius = '4px';
    errorDiv.style.marginTop = '5px';
    errorDiv.style.textAlign = 'center';
    errorDiv.style.fontSize = '14px';
    img.parentNode.appendChild(errorDiv);
}

function toggleEmojiPicker(messageId) {
    console.log('Toggling emoji picker for message ID:', messageId);
    const picker = document.getElementById('emoji-picker-' + messageId);
    console.log('Picker element:', picker);

    if (!picker) {
        console.error('Picker element not found for message ID:', messageId);
        // Create the emoji picker if it doesn't exist
        createEmojiPicker(messageId);
        return;
    }

    // Fermer tous les autres pickers
    document.querySelectorAll('.instagram-reaction-bar').forEach(p => {
        if (p.id !== 'emoji-picker-' + messageId) {
            p.style.display = 'none';
        }
    });

    // Basculer l'affichage du picker actuel
    if (picker.style.display === 'none' || picker.style.display === '') {
        console.log('Showing picker');
        picker.style.display = 'flex';

        // Animation d'apparition
        picker.style.animation = 'fadeIn 0.2s ease-out';
    } else {
        picker.style.display = 'none';
    }
}

// Function to create an emoji picker if it doesn't exist
function createEmojiPicker(messageId) {
    console.log('Creating emoji picker for message ID:', messageId);
    const messageElement = document.querySelector(`.message[data-message-id="${messageId}"]`);

    if (!messageElement) {
        console.error('Message element not found for ID:', messageId);
        return;
    }

    // Create the emoji picker
    const picker = document.createElement('div');
    picker.id = 'emoji-picker-' + messageId;
    picker.className = 'instagram-reaction-bar';

    // Add emoji buttons
    const emojis = ['❤️', '😂', '😮', '😢', '😡', '👍'];
    emojis.forEach(emoji => {
        const emojiBtn = document.createElement('span');
        emojiBtn.className = 'instagram-emoji-btn';
        emojiBtn.textContent = emoji;
        emojiBtn.onclick = function() { selectEmoji(messageId, emoji); };
        picker.appendChild(emojiBtn);
    });

    // Add the picker to the message element
    messageElement.appendChild(picker);

    // Show the picker
    picker.style.display = 'flex';
    picker.style.animation = 'fadeIn 0.2s ease-out';
}

function selectEmoji(messageId, emoji) {
    addOrUpdateReaction(messageId, emoji);
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
                // Mettre à jour l'affichage des réactions sans recharger tous les messages
                updateReactionsDisplay(messageId, response.reactions);
            } else {
                alert('Erreur lors de l\'ajout de la réaction');
            }
        }
    };
    xhr.send("message_id=" + messageId + "&reaction_type=" + encodeURIComponent(reactionType));
}

// Fonction pour mettre à jour l'affichage des réactions
function updateReactionsDisplay(messageId, reactions) {
    const reactionsContainer = document.getElementById('reactions-' + messageId);
    if (!reactionsContainer) return;

    // Vider le conteneur
    reactionsContainer.innerHTML = '';

    // Compter les réactions par type
    const reactionCounts = {};
    let currentUserReaction = null;

    if (reactions && reactions.length > 0) {
        reactions.forEach(reaction => {
            if (!reactionCounts[reaction.reaction_type]) {
                reactionCounts[reaction.reaction_type] = 0;
            }
            reactionCounts[reaction.reaction_type]++;

            // Vérifier si c'est la réaction de l'utilisateur actuel
            if (reaction.user_id === '<?php echo $userId; ?>') {
                currentUserReaction = reaction.reaction_type;
            }
        });

        // Créer les bulles de réaction
        for (const [type, count] of Object.entries(reactionCounts)) {
            const bubble = document.createElement('span');
            bubble.className = 'reaction-bubble';

            // Ajouter une classe spéciale si c'est la réaction de l'utilisateur actuel
            if (type === currentUserReaction) {
                bubble.classList.add('user-reaction');
            }

            // Ajouter un effet d'animation pour les nouvelles réactions
            bubble.classList.add('reaction-appear');

            bubble.innerHTML = type + ' <span class="reaction-count">' + count + '</span>';

            // Ajouter un événement pour supprimer sa propre réaction en cliquant dessus
            if (type === currentUserReaction) {
                bubble.addEventListener('click', function() {
                    addOrUpdateReaction(messageId, type); // Cela va supprimer la réaction si elle existe déjà
                });
                bubble.title = "Cliquez pour supprimer votre réaction";
            }

            reactionsContainer.appendChild(bubble);

            // Supprimer la classe d'animation après l'animation
            setTimeout(() => {
                bubble.classList.remove('reaction-appear');
            }, 500);
        }
    }
}
</script>

<style>
.message-container {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
    position: relative;
}

.message-wrapper {
    display: flex;
    align-items: flex-start;
    position: relative;
    max-width: 80%;
}

.message {
    max-width: 100%;
    padding: 10px;
    border-radius: 10px;
    position: relative;
    margin-top: 20px; /* Espace pour la barre de réactions */
}

.sent .message-wrapper {
    margin-left: auto;
}

.received .message-wrapper {
    margin-right: auto;
}

.message.sent {
    background-color: #e3f2fd;
}

.message.received {
    background-color: #f5f5f5;
}

.message-content {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.message-photo {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 5px 0;
    min-height: 50px;
    max-width: 100%;
}

.message-photo img {
    transition: transform 0.2s ease;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: contain;
}

.message-photo img:hover {
    transform: scale(1.05);
    cursor: pointer;
}

.timestamp {
    font-size: 0.75rem;
    color: #666;
    margin-top: 5px;
}

.image-error {
    background-color: #fff3f3;
    border: 1px solid #ffcdd2;
    border-radius: 4px;
    font-size: 0.9em;
}

/* Styles pour les boutons d'action */
.buttons-container {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-left: 5px;
}

.reaction-btn {
    background-color: #4CAF50; /* Vert */
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
    border: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: all 0.2s ease;
    opacity: 0.85;
}

.reaction-btn:hover {
    background-color: #388E3C; /* Vert plus foncé */
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    transform: scale(1.05);
    opacity: 1;
}

.reaction-btn i {
    font-size: 18px;
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
}

.delete-btn {
    background-color: #f44336;
    color: white;
}

.delete-btn:hover {
    background-color: #D32F2F;
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
}

.instagram-reaction-bar {
    position: absolute;
    top: -50px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border-radius: 30px;
    padding: 6px 10px;
    display: none; /* Initialement caché, sera changé en flex par JS */
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    z-index: 1000;
    animation: fadeIn 0.2s ease-out;
    gap: 6px;
}

.instagram-reaction-bar::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid white;
}

.reaction-emoji-btn {
    font-size: 22px;
    cursor: pointer;
    user-select: none;
    transition: transform 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.reaction-emoji-btn:hover {
    transform: scale(1.3);
}

.instagram-emoji-btn {
    font-size: 26px;
    cursor: pointer;
    transition: transform 0.2s ease;
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.instagram-emoji-btn:hover {
    transform: scale(1.4);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateX(-50%) translateY(10px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

/* Styles pour les réactions */
.message-reactions {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 5px;
}

.reaction-bubble {
    background-color: rgba(240, 242, 245, 0.85);
    border-radius: 16px;
    padding: 2px 8px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    backdrop-filter: blur(4px);
}

.reaction-bubble:hover {
    background-color: rgba(228, 230, 235, 0.95);
    transform: scale(1.05);
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

.reaction-count {
    font-size: 11px;
    margin-left: 4px;
    color: #65676b;
    font-weight: bold;
    background-color: rgba(255,255,255,0.6);
    border-radius: 10px;
    padding: 1px 5px;
    min-width: 16px;
    text-align: center;
}

/* Style pour la réaction de l'utilisateur actuel */
.user-reaction {
    background-color: rgba(220, 242, 255, 0.9);
    border: 1px solid rgba(0, 132, 255, 0.2);
    box-shadow: 0 1px 4px rgba(0, 132, 255, 0.15);
}

.user-reaction:hover {
    background-color: rgba(200, 232, 255, 0.95);
}

/* Animation pour l'apparition des réactions */
.reaction-appear {
    animation: reactionAppear 0.3s ease-out;
}

@keyframes reactionAppear {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    50% {
        transform: scale(1.1);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
