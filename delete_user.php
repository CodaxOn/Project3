<?php
// Fichier : delete_user.php
require 'config.php';

// SÉCURITÉ : SEUL L'ADMIN PEUT SUPPRIMER DES GENS
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Accès refusé.");
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = intval($_GET['id']);
    $type = $_GET['type']; // 'candidat' ou 'recruteur'

    try {
        if ($type == 'candidat') {
            // Supprimer un candidat
            $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($type == 'recruteur') {
            // Supprimer une entreprise
            $stmt = $pdo->prepare("DELETE FROM companies WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        header("Location: admin.php?msg=user_banned");
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
