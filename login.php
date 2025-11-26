<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $type = $_POST['account_type'];

    $user = null;
    $email_field = ($type == 'candidate') ? 'email' : 'professional_email';
    $table = ($type == 'candidate') ? 'candidates' : 'companies';

    // 1. Définir les colonnes à sélectionner en fonction du type d'utilisateur
    $select_cols = "id, password";
    if ($type == 'candidate') {
        $select_cols .= ", username";
    } else {
        $select_cols .= ", company_name";
    }

    // 2. Recherche de l'utilisateur avec la bonne liste de champs
    $sql = "SELECT $select_cols FROM $table WHERE $email_field = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérification du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        
        // SUCCÈS : Démarrer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $type;
        
        if ($type == 'candidate') {
            $_SESSION['username'] = $user['username'];
        } else {
            $_SESSION['company_name'] = $user['company_name'];
        }
        
        // Redirection vers l'accueil (où le message de succès s'affichera)
        header("Location: index.php"); 
        exit();
        
    } else {
        // Échec : Redirection avec un message d'erreur (si possible, ou affiche un message simple)
        die("Erreur de connexion : Email ou mot de passe incorrect pour le compte $type.");
    }
} else {
    header("Location: index.php");
    exit();
}
?>