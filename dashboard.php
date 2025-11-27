<?php
session_start(); // Toujours mettre ça au débuth

// Si l'utilisateur n'est pas connecté, on le vire !
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Tableau de bord</title></head>
<body>
    <h1>Bienvenue dans votre espace privé !</h1>
    
    <p>Vous êtes connecté en tant que : <strong><?php echo $_SESSION['user_type']; ?></strong></p>
    
    <?php if ($_SESSION['user_type'] == 'candidate'): ?>
        <p>Bonjour <?php echo htmlspecialchars($_SESSION['username']); ?> ! Prêt à postuler ?</p>
    <?php else: ?>
        <p>Bonjour l'équipe <?php echo htmlspecialchars($_SESSION['company_name']); ?> ! Prêt à recruter ?</p>
    <?php endif; ?>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>