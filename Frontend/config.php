<?php
// Fichier : config.php (version sécurisée pour base projet_3)

// 1. Paramètres de connexion
$host = 'localhost';
$dbname = 'project_3';   // nom exact de ta base dans phpMyAdmin
$username = 'root';
$password = '';

// 2. Connexion PDO avec gestion d'erreurs propre
try {
<<<<<<< HEAD
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );
    // Mode erreurs en exceptions
=======
    // Connexion avec gestion des accents (utf8)
   $password = getenv('MYSQL_SECURE_PASSWORD');
$conn = new mysqli($servername, $username, $password);
>>>>>>> faa2123b984eedfea4faee50de0378251508ee81
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch par défaut en tableau associatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // On log l'erreur côté serveur, mais on ne la montre pas au visiteur
    error_log('Erreur PDO : ' . $e->getMessage());
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// 3. Sécurisation basique des cookies de session
ini_set('session.cookie_httponly', 1); // Empêche JavaScript de lire le cookie

// 4. Démarrage de la session (si pas déjà démarrée)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
