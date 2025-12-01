<?php
require 'config.php';

if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    // Vérification du token
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ?");
    $stmt->execute([$email, $token]);
    
    if ($stmt->rowCount() > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Mise à jour du mot de passe (on essaie les deux tables)
            $pdo->prepare("UPDATE candidates SET password = ? WHERE email = ?")->execute([$new_pass, $email]);
            $pdo->prepare("UPDATE companies SET password = ? WHERE professional_email = ?")->execute([$new_pass, $email]);
            
            // On supprime le token utilisé
            $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
            
            echo "Mot de passe modifié ! <a href='index.php'>Se connecter</a>";
        }
    } else {
        die("Lien invalide ou expiré.");
    }
}
?>

<!-- Formulaire de nouveau mot de passe -->
<form method="POST">
    <input type="password" name="password" placeholder="Nouveau mot de passe" required>
    <button type="submit">Valider</button>
</form>
