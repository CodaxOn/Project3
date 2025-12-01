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
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion BDD : " . $e->getMessage());
}
?>
