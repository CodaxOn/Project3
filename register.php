<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $type = $_POST['account_type'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
    // 1. Vérification basique du mot de passe
    if ($password !== $password_confirm) {
        die("Erreur d'inscription : Les mots de passe ne correspondent pas.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($type == 'candidate') {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);

        $sql = "INSERT INTO candidates (username, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$username, $email, $hashed_password]);
            echo "Succès : Compte Candidat créé ! <a href='index.php?section=connexion'>Se connecter</a>";
        } catch (PDOException $e) {
            die("Erreur d'inscription : Cet email est peut-être déjà utilisé.");
        }

    } elseif ($type == 'company') {
        // Récupération des données entreprise
        $company_name = htmlspecialchars($_POST['company_name']);
        $legal_status = htmlspecialchars($_POST['legal_status']);
        $siret = htmlspecialchars($_POST['siret_number']);
        $email_pro = htmlspecialchars($_POST['professional_email']);
        
        // Note: address et manager_name ne sont pas dans votre nouveau HTML,
        // mais sont conservés pour correspondre à votre BDD.
        $address = ""; 
        $manager = "";

        $sql = "INSERT INTO companies (company_name, legal_status, siret_number, professional_email, password, address, manager_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$company_name, $legal_status, $siret, $email_pro, $hashed_password, $address, $manager]);
            echo "Succès : Compte Entreprise créé ! <a href='index.php?section=connexion'>Se connecter</a>";
        } catch (PDOException $e) {
            die("Erreur d'inscription : Cet email professionnel est peut-être déjà utilisé.");
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
