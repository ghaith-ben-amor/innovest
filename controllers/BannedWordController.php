<?php
class BannedWordController {
    private $bannedWordModel;

    public function __construct() {
        $this->bannedWordModel = new BannedWordModel();
    }

    // Afficher la page de gestion des mots interdits
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        $bannedWords = $this->bannedWordModel->getAllBannedWords();
        require_once 'views/backoffice/banned_words.php';
    }

    // Ajouter un nouveau mot interdit
    public function addBannedWord() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $word = trim($_POST['word']);
            $severity = $_POST['severity'];

            if (empty($word)) {
                $error = "Le mot ne peut pas être vide";
            } else {
                try {
                    $this->bannedWordModel->addBannedWord($word, $severity);
                    $success = "Mot interdit ajouté avec succès";
                } catch (Exception $e) {
                    $error = "Erreur lors de l'ajout du mot : " . $e->getMessage();
                }
            }
        }

        $bannedWords = $this->bannedWordModel->getAllBannedWords();
        require_once 'views/backoffice/banned_words.php';
    }

    // Supprimer un mot interdit
    public function deleteBannedWord() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                $this->bannedWordModel->deleteBannedWord($_POST['id']);
                $success = "Mot interdit supprimé avec succès";
            } catch (Exception $e) {
                $error = "Erreur lors de la suppression du mot : " . $e->getMessage();
            }
        }

        $bannedWords = $this->bannedWordModel->getAllBannedWords();
        require_once 'views/backoffice/banned_words.php';
    }

    // API pour récupérer la liste des mots bannis
    public function getBannedWords() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['username'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Utilisateur non connecté']);
            exit;
        }

        $bannedWords = $this->bannedWordModel->getAllBannedWords();

        // Retourner la liste au format JSON
        header('Content-Type: application/json');
        echo json_encode($bannedWords);
        exit;
    }
}