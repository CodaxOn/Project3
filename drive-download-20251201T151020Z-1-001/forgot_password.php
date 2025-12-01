<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Génère un code aléatoire unique

    // On vérifie si l'email existe chez les candidats OU recruteurs
    $stmt = $pdo->prepare("SELECT id FROM candidates WHERE email = ? UNION SELECT id FROM companies WHERE professional_email = ?");
    $stmt->execute([$email, $email]);
    
    if ($stmt->rowCount() > 0) {
        // On enregistre le token
        $insert = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $insert->execute([$email, $token]);

        // Simulation d'envoi d'email (car mail() ne marche pas sur localhost sans config)
        $link = "http://localhost/votre_dossier/reset.php?token=" . $token . "&email=" . $email;
        echo "<div style='background:#d4edda; padding:20px;'>
                <strong>Simulation d'email :</strong><br>
                Bonjour,<br>Cliquez ici pour réinitialiser : <a href='$link'>$link</a>
              </div>";
    } else {
        echo "Aucun compte trouvé avec cet email.";
    }
}
?>

<form method="POST">
    <h2>Mot de passe oublié</h2>
    <input type="email" name="email" placeholder="Votre email" required>
    <button type="submit">Envoyer le lien</button>
</form>
