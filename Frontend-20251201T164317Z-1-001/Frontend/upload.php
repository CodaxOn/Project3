<?php
// Fichier : upload.php
require 'config.php';

if (!isset($_SESSION['user_id'])) { die("Accès interdit"); }

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Dossier où on stocke les fichiers
$target_dir = "uploads/";

if (isset($_POST['upload_type'])) {
    
    // --- CAS 1 : UPLOAD DE LOGO (RECRUTEUR) ---
    if ($_POST['upload_type'] == 'logo' && $role == 'recruteur') {
        $file = $_FILES['logo_file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        // On renomme le fichier pour éviter les doublons : logo_ID.jpg
        $filename = "logo_" . $user_id . "." . $ext;
        $target_file = $target_dir . $filename;

        // Vérifier si c'est une image
        $check = getimagesize($file['tmp_name']);
        if($check !== false) {
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Mise à jour BDD
                $stmt = $pdo->prepare("UPDATE companies SET logo = ? WHERE id = ?");
                $stmt->execute([$filename, $user_id]);
                
                header("Location: dashboard.php?msg=logo_ok");
                exit;
            }
        } else {
            die("Ce n'est pas une image valide.");
        }
    }

    // --- CAS 2 : UPLOAD DE CV (CANDIDAT) ---
    if ($_POST['upload_type'] == 'cv' && $role == 'candidat') {
        $file = $_FILES['cv_file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        // On renomme : cv_ID.pdf
        $filename = "cv_" . $user_id . "." . $ext;
        $target_file = $target_dir . $filename;

        // On accepte seulement PDF
        if (strtolower($ext) == 'pdf') {
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Mise à jour BDD
                $stmt = $pdo->prepare("UPDATE candidates SET cv = ? WHERE id = ?");
                $stmt->execute([$filename, $user_id]);
                
                header("Location: dashboard.php?msg=cv_ok");
                exit;
            }
        } else {
            die("Seuls les fichiers PDF sont acceptés pour le CV.");
        }
    }
}
?>
