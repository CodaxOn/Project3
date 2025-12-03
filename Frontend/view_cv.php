<?php
use 'config.php';

// Vérifier connexion
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé.");
}

$target_id = $_SESSION['user_id'];

// Si c'est un recruteur qui demande à voir un CV spécifique (via ?id=...)
if ($_SESSION['role'] == 'recruteur' && isset($_GET['id'])) {
    $target_id = intval($_GET['id']);
}

// Récupérer le nom du fichier CV
$stmt = $pdo->prepare("SELECT cv FROM candidates WHERE id = ?");
$stmt->execute([$target_id]);
$cv = $stmt->fetchColumn();

if (!$cv) {
    die("Aucun CV trouvé pour cet utilisateur.");
}

$filePath = __DIR__ . '/uploads/' . $cv;

if (file_exists($filePath)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $cv . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
} else {
    die("Fichier introuvable sur le serveur.");
}
?>
