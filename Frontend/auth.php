<?php
// Fichier : auth.php
require 'config.php';

// =========================================================
// 1. DÉCONNEXION (LOGOUT)
// =========================================================
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// =========================================================
// 2. SÉCURITÉ : ON N'ACCEPTE QUE LES POST ICI
// =========================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$action = $_POST['action'] ?? '';

// =========================================================
// 3. INSCRIPTION (REGISTER)
// =========================================================
if ($action === 'register') {
    $role     = $_POST['role']     ?? '';
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Vérification simple
    if (empty($role) || empty($email) || empty($password)) {
        header("Location: index.php?section=connexion&msg=register_missing");
        exit;
    }

    // Hachage sécurisé du mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Inscription CANDIDAT
        if ($role === 'candidat') {
            $nom = trim($_POST['nom'] ?? '');
            if (empty($nom)) { header("Location: index.php?section=connexion&msg=register_missing"); exit; }

            $stmt = $pdo->prepare("INSERT INTO candidates (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $hash]);
           
            // Connexion automatique
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role']    = 'candidat';
            $_SESSION['nom']     = $nom;

        // Inscription RECRUTEUR
        } elseif ($role === 'recruteur') {
            $entreprise = trim($_POST['entreprise'] ?? '');
            $siret      = trim($_POST['siret'] ?? '');
           
            if (empty($entreprise) || empty($siret)) { header("Location: index.php?section=connexion&msg=register_missing"); exit; }

            $stmt = $pdo->prepare("INSERT INTO companies (company_name, siret_number, professional_email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$entreprise, $siret, $email, $hash]);

            // Connexion automatique
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role']    = 'recruteur';
            $_SESSION['nom']     = $entreprise;
        }

        header("Location: dashboard.php");
        exit;

    } catch (PDOException $e) {
        // Souvent dû à un email déjà pris
        error_log("Erreur inscription : " . $e->getMessage());
        header("Location: index.php?section=connexion&msg=register_error");
        exit;
    }
}

// =========================================================
// 4. CONNEXION (LOGIN)
// =========================================================
if ($action === 'login') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: index.php?section=connexion&msg=login_missing");
        exit;
    }

    $user = null;
    $role_detecte = '';

    // Etape A : On cherche d'abord dans les CANDIDATS
    $stmt = $pdo->prepare("SELECT * FROM candidates WHERE email = ?");
    $stmt->execute([$email]);
    $candidat = $stmt->fetch();

    if ($candidat) {
        $user = $candidat;
        // Vérification spéciale admin
        $role_detecte = ($email === 'admin@stageboard.com') ? 'admin' : 'candidat';
    } else {
        // Etape B : Si pas candidat, on cherche dans les ENTREPRISES
        $stmt = $pdo->prepare("SELECT * FROM companies WHERE professional_email = ?");
        $stmt->execute([$email]);
        $recruteur = $stmt->fetch();

        if ($recruteur) {
            $user = $recruteur;
            $role_detecte = 'recruteur';
        }
    }

    // Etape C : Si on a trouvé quelqu'un, on vérifie le mot de passe
    if ($user && password_verify($password, $user['password'])) {
       
        // 1. Vérification du BANNISSEMENT
        if (isset($user['is_banned']) && (int)$user['is_banned'] === 1) {
            // Page d'erreur stylisée pour les bannis
            die("<!DOCTYPE html>
            <html>
            <body style='background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;'>
                <div style='background:white; padding:40px; border-radius:10px; text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.1);'>
                    <h1 style='color:#e74c3c; font-size:50px; margin:0;'>🚫</h1>
                    <h2 style='color:#333;'>Accès Suspendu</h2>
                    <p style='color:#666;'>Votre compte a été bloqué par l'administrateur.</p>
                    <a href='index.php' style='color:#3498db; text-decoration:none; font-weight:bold;'>Retour à l'accueil</a>
                </div>
            </body>
            </html>");
        }

        // 2. Création de la SESSION (Connexion réussie)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $role_detecte;
       
        // On récupère le bon nom selon le type de compte
        if ($role_detecte === 'recruteur') {
            $_SESSION['nom'] = $user['company_name'];
        } else {
            $_SESSION['nom'] = $user['username'];
        }

        // 3. Redirection
        if ($role_detecte === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    }

    // Si on arrive ici, c'est que le login a échoué
    header("Location: index.php?section=connexion&msg=login_error");
    exit;
}
?>

