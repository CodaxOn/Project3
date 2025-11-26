<?php
// Paramètres de connexion
$host = 'localhost';
// Assurez-vous que ce nom correspond EXACTEMENT à votre base de données dans phpMyAdmin
$dbname = 'project 3'; 
$username = 'root';
$password = ''; // Par défaut sur XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Affiche l'erreur de connexion si la base n'est pas trouvée
    die("Erreur de connexion BDD : " . $e->getMessage());
}
?>