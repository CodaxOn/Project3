<?php
// Fichier : delete_cv.php
require 'config.php';

// Sécurité
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'candidat') {
    die("Accès interdit.");
}

$user_id = $_SESSION['user_id'];

// 1. Récupérer le nom du fichier actuel pour le supprimer du dossier
$stmt = $pdo->prepare("SELECT cv FROM candidates WHERE id = ?");
$stmt->execute([$user_id]);
$candidat = $stmt->fetch();

if ($candidat && $candidat['cv']) {
    $fichier = "uploads/" . $candidat['cv'];
    if (file_exists($fichier)) {
        unlink($fichier); // Supprime le fichier physique
    }
}

// 2. Mettre à jour la base de données (mettre NULL)
$update = $pdo->prepare("UPDATE candidates SET cv = NULL WHERE id = ?");
$update->execute([$user_id]);

header("Location: dashboard.php?msg=cv_deleted");
exit;
?>
