<?php
// Fichier : auth.php (Version Finale corrigée : SIRET_NUMBER & PROFESSIONAL_EMAIL)
require 'config.php';

// 1. DÉCONNEXION
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// 2. TRAITEMENT DES FORMULAIRES
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $action = $_POST['action']; // 'login' ou 'register'

    // ---------------------------------------------------------
    // INSCRIPTION (REGISTER)
    // ---------------------------------------------------------
    if ($action == 'register') {
        $role = $_POST['role'];
        $email = $_POST['email']; // C'est la valeur entrée par l'utilisateur
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            // --- 1. Détermination de la table ---
            if ($role == 'candidat') {
                $table = 'candidates';
            } elseif ($role == 'recruteur') {
                $table = 'companies';
            } else {
                die("Rôle inconnu.");
            }

            // --- 2. Vérification de l'existence de l'email avant insertion ---
            // On vérifie la colonne correspondante dans la table
            $email_column = ($role == 'recruteur') ? 'professional_email' : 'email';
            
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $email_column = ?");
            $checkStmt->execute([$email]);
            if ($checkStmt->fetchColumn() > 0) {
                // Redirection avec un message d'alerte plus propre
                echo "<script>alert('Cet email est déjà utilisé.'); window.location.href='index.php#connexion';</script>";
                exit;
            }
            // -------------------------------------------------

            if ($role == 'candidat') {
                $nom = $_POST['nom'];
                $sql = "INSERT INTO candidates (username, email, password) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $email, $password]);
                
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['role'] = 'candidat';
                $_SESSION['nom'] = $nom;

            } elseif ($role == 'recruteur') {
                $entreprise = $_POST['entreprise'];
                $siret = $_POST['siret'];
                
                // CORRECTION APPLIQUÉE : Utilisation de SIRET_NUMBER et PROFESSIONAL_EMAIL
                $sql = "INSERT INTO companies (company_name, siret_number, professional_email, password) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$entreprise, $siret, $email, $password]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['role'] = 'recruteur';
                $_SESSION['nom'] = $entreprise;
            }

            header("Location: dashboard.php");
            exit;

        } catch (PDOException $e) {
            // Message générique pour l'erreur 42S22 (colonne manquante) ou autre erreur critique
            // Si cette erreur apparaît, cela signifie qu'une colonne (autre que siret_number ou professional_email) est manquante dans votre BD.
            die("Erreur critique lors de l'inscription. (Code: " . $e->getCode() . ")");
        }
    }

    // ---------------------------------------------------------
    // CONNEXION (LOGIN)
    // ---------------------------------------------------------
    elseif ($action == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // A. Essai connexion CANDIDAT
        $stmt = $pdo->prepare("SELECT * FROM candidates WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            
            // --- VERIFICATION BANNISSEMENT ---
            if (isset($user['is_banned']) && $user['is_banned'] == 1) {
                die("<!DOCTYPE html><html><body style='background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;'><div style='background:white; padding:40px; border-radius:10px; text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.1);'><h1 style='color:#e74c3c; font-size:50px; margin:0;'>🚫</h1><h2 style='color:#333;'>Compte Suspendu</h2><p>Votre compte a été banni par l'administrateur.</p><a href='index.php' style='color:#3498db; text-decoration:none;'>Retour à l'accueil</a></div></body></html>");
            }
            // ---------------------------------

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['username'];

            // Hack Admin
            if ($email == 'admin@stageboard.com') {
                $_SESSION['role'] = 'admin';
                header("Location: admin.php");
            } else {
                $_SESSION['role'] = 'candidat';
                header("Location: dashboard.php");
            }
            exit;
        }

        // B. Essai connexion RECRUTEUR
        // CORRECTION APPLIQUÉE : Utilisation de professional_email pour la connexion
        $stmt = $pdo->prepare("SELECT * FROM companies WHERE professional_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            // --- VERIFICATION BANNISSEMENT ---
            if (isset($user['is_banned']) && $user['is_banned'] == 1) {
                die("<!DOCTYPE html><html><body style='background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;'><div style='background:white; padding:40px; border-radius:10px; text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.1);'><h1 style='color:#e74c3c; font-size:50px; margin:0;'>🚫</h1><h2 style='color:#333;'>Entreprise Bloquée</h2><p>L'accès de votre entreprise a été suspendu.</p><a href='index.php' style='color:#3498db; text-decoration:none;'>Retour à l'accueil</a></div></body></html>");
            }
            // ---------------------------------

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = 'recruteur';
            $_SESSION['nom'] = $user['company_name'];
            header("Location: dashboard.php");
            exit;
        }

        // Si échec
        echo "<script>alert('Email ou mot de passe incorrect.'); window.location.href='index.php';</script>";
    }
}
?>