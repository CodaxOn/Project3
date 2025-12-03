<?php
use'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];          // Donnée contrôlée par l'utilisateur
    $token = bin2hex(random_bytes(50));

    // Vérifier si l'email existe (candidats OU recruteurs)
    $stmt = $pdo->prepare("
        SELECT id FROM candidates WHERE email = ?
        UNION
        SELECT id FROM companies WHERE professional_email = ?
    ");
    $stmt->execute([$email, $email]);

    if ($stmt->rowCount() > 0) {
        // Enregistrer le token
        $insert = $pdo->prepare("
            INSERT INTO password_resets (email, token) VALUES (?, ?)
        ");
        $insert->execute([$email, $token]);

        // ==== CORRECTION SONARQUBE : ne pas refléter des données non nettoyées ====
        // On échappe tout ce qui peut venir de l'utilisateur avant de le mettre dans le HTML
        $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $safeToken = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');

        $link = "http://localhost/votre_dossier/reset.php?token={$safeToken}&email={$safeEmail}";

        echo "<div style='background:#4dedda; padding:20px;'>
                <strong>Simulation d'email :</strong><br>
                Bonjour,<br>
                Cliquez ici pour réinitialiser :
                <a href=\"{$link}\">{$link}</a>
              </div>";
    } else {
        echo 'Aucun compte trouvé avec cet email.';
    }
}
?>

<form method="POST">
    <h2>Mot de passe oublié</h2>
    <input type="email" name="email" placeholder="Votre email" required>
    <button type="submit">Envoyer le lien</button>
</form>
