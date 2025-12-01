<?php
// Fichier : ban_user.php
require 'config.php';

// SÉCURITÉ ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Accès refusé.");
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type']; // 'candidat' ou 'recruteur'
    
    // On bannit (met à 1)
    $valeur = 1; 
    
    // Optionnel : Si vous voulez pouvoir "Débannir", on peut inverser l'état actuel
    // Mais pour l'instant, faisons simple : Bannir = 1.

    try {
        if ($type == 'candidat') {
            $stmt = $pdo->prepare("UPDATE candidates SET is_banned = ? WHERE id = ?");
        } elseif ($type == 'recruteur') {
            $stmt = $pdo->prepare("UPDATE companies SET is_banned = ? WHERE id = ?");
        }
        
        $stmt->execute([$valeur, $id]);
        
        header("Location: admin.php?msg=banned");
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
