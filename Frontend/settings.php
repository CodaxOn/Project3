<?php
// Fichier : settings.php
use 'config.php';

if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$msg = "";

// 1. TRAITEMENT DE LA MISE À JOUR
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Changement d'infos de base
    if (isset($_POST['update_info'])) {
        $new_email = $_POST['email'];
        
        if ($role == 'candidat') {
            $new_name = $_POST['username'];
            $stmt = $pdo->prepare("UPDATE candidates SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$new_name, $new_email, $user_id]);
            $_SESSION['nom'] = $new_name; // On met à jour la session
        } elseif ($role == 'recruteur') {
            $new_name = $_POST['company_name'];
            $stmt = $pdo->prepare("UPDATE companies SET company_name = ?, professional_email = ? WHERE id = ?");
            $stmt->execute([$new_name, $new_email, $user_id]);
            $_SESSION['nom'] = $new_name;
        }
        $msg = "✅ Informations mises à jour !";
    }

    // Changement de mot de passe
    if (isset($_POST['update_password'])) {
        $pass = $_POST['password'];
        if (!empty($pass)) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            
            if ($role == 'candidat') {
                $stmt = $pdo->prepare("UPDATE candidates SET password = ? WHERE id = ?");
            } else {
                $stmt = $pdo->prepare("UPDATE companies SET password = ? WHERE id = ?");
            }
            $stmt->execute([$hash, $user_id]);
            $msg = "🔒 Mot de passe modifié avec succès !";
        }
    }
}

// 2. RÉCUPÉRATION DES INFOS ACTUELLES
if ($role == 'candidat') {
    $stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $nom_actuel = $user['username'];
    $email_actuel = $user['email'];
} else {
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $nom_actuel = $user['company_name'];
    $email_actuel = $user['professional_email']; // On utilise le vrai nom de colonne
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres - StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f4f4; padding-top: 80px; }
        .settings-container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        h2 { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; color:#333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color:#555; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #555; text-decoration: none; }
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="settings-container">
    <a href="dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Retour au Dashboard</a>
    
    <?php if($msg): ?>
        <div class="alert"><?= $msg ?></div>
    <?php endif; ?>

    <div class="card">
        <h2><i class="fa-solid fa-user-pen"></i> Mes Informations</h2>
        <form method="POST">
            <div class="form-group">
                <label>
  <%= (@role == 'recruteur') ? "Nom de l'entreprise" : "Nom d'utilisateur" %>
  <input type="text" name="dynamic_name" />
</label>
                <input type="text" name="<?= ($role == 'recruteur') ? 'company_name' : 'username' ?>" value="<?= htmlspecialchars($nom_actuel) ?>" required>
            </div>
            <div class="form-group">
               <label>
  Adresse Email
  <input type="email" name="email" />
</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email_actuel) ?>" required>
            </div>
            <button type="submit" name="update_info" class="btn-save">Enregistrer les modifications</button>
        </form>
    </div>

    <div class="card">
        <h2><i class="fa-solid fa-lock"></i> Sécurité</h2>
        <form method="POST">
            <div class="form-group">
               <label>
  Nouveau mot de passe
  <input type="password" name="new_password" />
</label>
                <input type="password" name="password" placeholder="Laissez vide si vous ne voulez pas changer">
            </div>
            <button type="submit" name="update_password" class="btn-save" style="background:#e67e22;">Changer le mot de passe</button>
        </form>
    </div>
</div>

</body>
</html>
