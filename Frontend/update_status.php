<?php
// Fichier : update_status.php
use 'config.php';

// Sécurité : Seul un recruteur peut faire ça
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recruteur') {
    die("Accès interdit.");
}

// On vérifie qu'on a bien l'ID de la candidature et le nouveau statut
if (isset($_GET['id']) && isset($_GET['status'])) {
    $app_id = intval($_GET['id']);
    $new_status = $_GET['status'];
    $recruteur_id = $_SESSION['user_id'];

    // Sécurité : Vérifier que cette candidature concerne bien une offre de CE recruteur
    // On fait une jointure pour vérifier que l'offre (jobs) appartient à l'entreprise connectée (companies)
    $checkSql = "SELECT applications.id 
                 FROM applications 
                 JOIN jobs ON applications.job_id = jobs.id 
                 WHERE applications.id = ? AND jobs.company_id = ?";
    
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([$app_id, $recruteur_id]);
    
    if ($stmt->fetch()) {
        // C'est bon, on met à jour
        // On n'autorise que 'accepte' ou 'refuse' pour éviter les hacks
        if (in_array($new_status, ['accepte', 'refuse'])) {
            $update = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
            $update->execute([$new_status, $app_id]);
        }
    }
}

// Retour au dashboard
header("Location: dashboard.php");
exit;
?>
