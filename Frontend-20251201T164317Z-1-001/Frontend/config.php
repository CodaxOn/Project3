<?php
// Fichier : config.php
session_start(); // Démarre la session pour tout le site

// Paramètres de connexion (Attention à l'espace dans 'projet 3')
$host = 'localhost';
$dbname = 'project_3'; 
$user = 'root';
$pass = '';

try {
    // Connexion avec gestion des accents (utf8)
    $password = getenv('MYSQL_SECURE_PASSWORD');
    $conn = new mysqli($servername, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion BDD : " . $e->getMessage());
}
?>


