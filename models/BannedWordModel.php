<?php
class BannedWordModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Récupérer tous les mots interdits
    public function getAllBannedWords() {
        $sql = "SELECT * FROM banned_words ORDER BY severity DESC, word ASC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function logFilteredMessage($message, $detectedWords, $severity) {
        $logEntry = date('Y-m-d H:i:s') . " | ";
        $logEntry .= "Message: " . $message . " | ";
        $logEntry .= "Detected Words: " . implode(', ', $detectedWords) . " | ";
        $logEntry .= "Severity: " . $severity . "\n";
        
        $logFile = __DIR__ . '/../logs/filtered_messages.log';
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }

    // Vérifier si un message contient des mots interdits
    public function checkMessage($message) {
        error_log("Vérification du message: " . $message);
        $bannedWords = $this->getAllBannedWords();
        error_log("Liste des mots interdits: " . print_r($bannedWords, true));
        
        $detectedWords = [];
        $message = mb_strtolower($message); // Convertir en minuscules pour la comparaison
        
        foreach ($bannedWords as $banned) {
            $pattern = '/\b' . preg_quote(mb_strtolower($banned['word']), '/') . '\b/u';
            error_log("Recherche du motif: " . $pattern);
            
            if (preg_match($pattern, $message)) {
                error_log("Mot interdit trouvé: " . $banned['word']);
                $detectedWords[] = [
                    'word' => $banned['word'],
                    'severity' => $banned['severity']
                ];
            }
        }
        
        error_log("Mots détectés: " . print_r($detectedWords, true));
        
        if (!empty($detectedWords)) {
            $severity = $this->getHighestSeverity($detectedWords);
            $this->logFilteredMessage($message, $detectedWords, $severity);
        }
        
        return $detectedWords;
    }

    // Obtenir le niveau de sévérité le plus élevé dans un message
    public function getHighestSeverity($detectedWords) {
        $severityLevels = ['low' => 1, 'medium' => 2, 'high' => 3];
        $highestSeverity = 'low';
        
        foreach ($detectedWords as $word) {
            if ($severityLevels[$word['severity']] > $severityLevels[$highestSeverity]) {
                $highestSeverity = $word['severity'];
            }
        }
        
        error_log("Sévérité la plus élevée: " . $highestSeverity);
        return $highestSeverity;
    }

    // Ajouter un nouveau mot interdit
    public function addBannedWord($word, $severity) {
        $sql = "INSERT INTO banned_words (word, severity) VALUES (?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$word, $severity]);
    }

    // Supprimer un mot interdit
    public function deleteBannedWord($id) {
        $sql = "DELETE FROM banned_words WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
} 