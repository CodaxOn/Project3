<?php
// Fichier : delete_job.php (Complet avec support Admin)
require 'config.php';

// Sécurité de base : Il faut être connecté
if (!isset($_SESSION['user_id'])) {
    die("Accès interdit. Vous devez être connecté.");
}

// Vérification du rôle
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');
$isRecruteur = (isset($_SESSION['role']) && $_SESSION['role'] == 'recruteur');

// Si ce n'est ni un admin ni un recruteur, dehors
if (!$isAdmin && !$isRecruteur) {
    die("Accès interdit. Rôle insuffisant.");
}

// Traitement de la suppression
if (isset($_GET['id'])) {
    $job_id = intval($_GET['id']); // On sécurise l'ID en forçant un entier

    try {
        if ($isAdmin) {
            // CAS 1 : L'ADMIN supprime (il a tous les droits)
            // Il peut supprimer n'importe quelle offre via son ID
            $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
            $stmt->execute([$job_id]);
            
            // Retour vers le panel admin
            header("Location: admin.php?msg=deleted_admin");
            exit;

        } else {
            // CAS 2 : Le RECRUTEUR supprime (droits limités)
            // Il ne peut supprimer que SI l'offre lui appartient (company_id)
            $company_id = $_SESSION['user_id'];
            
            $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ? AND company_id = ?");
            $stmt->execute([$job_id, $company_id]);
            
            // Retour vers le dashboard classique
            header("Location: dashboard.php?msg=deleted");
            exit;
        }

    } catch (PDOException $e) {
        die("Erreur SQL lors de la suppression : " . $e->getMessage());
    }

} else {
    // Si pas d'ID fourni
    header("Location: index.php");
    exit;
}
?>
