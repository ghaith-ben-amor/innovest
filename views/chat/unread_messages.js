// Script pour mettre à jour le badge de messages non lus

// Fonction pour mettre à jour le badge de messages non lus
function updateUnreadMessagesBadge() {
    fetch('unread_messages_badge.php')
        .then(response => response.text())
        .then(data => {
            // Mettre à jour tous les badges de messages non lus sur la page
            const badges = document.querySelectorAll('.unread-messages-badge');
            badges.forEach(badge => {
                badge.innerHTML = data;
                // Afficher une alerte si le nombre de messages non lus est supérieur à zéro
                const match = data.match(/>(\\d+)</);
                if (match && parseInt(match[1]) > 0) {
                    if (!document.getElementById('unread-alert')) {
                        const alertDiv = document.createElement('div');
                        alertDiv.id = 'unread-alert';
                        alertDiv.className = 'alert alert-info fixed-top text-center';
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = 'Vous avez ' + match[1] + ' message(s) non lu(s) !';
                        document.body.appendChild(alertDiv);
                        setTimeout(() => {
                            if (alertDiv) alertDiv.remove();
                        }, 4000);
                    }
                } else {
                    const existingAlert = document.getElementById('unread-alert');
                    if (existingAlert) existingAlert.remove();
                }
            });
        })
        .catch(error => console.error('Erreur lors de la mise à jour du badge:', error));
}

// Mettre à jour le badge toutes les 30 secondes
setInterval(updateUnreadMessagesBadge, 30000);

// Mettre à jour le badge au chargement de la page
document.addEventListener('DOMContentLoaded', updateUnreadMessagesBadge);

// Fonction pour marquer les messages comme lus lorsqu'une conversation est ouverte
function markMessagesAsRead(otherUserId) {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    if (!userId) return;
    
    fetch('mark_messages_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}&other_user_id=${otherUserId}`
    })
    .then(() => {
        // Mettre à jour le badge après avoir marqué les messages comme lus
        updateUnreadMessagesBadge();
    })
    .catch(error => console.error('Erreur lors du marquage des messages comme lus:', error));
}