<?php
// Fichier : post_job.php
require 'config.php';

// 1. Sécurité : Seul un recruteur connecté peut poster
// On vérifie si l'utilisateur est connecté ET s'il a le rôle 'recruteur'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recruteur') {
    // Si non, on arrête tout
    die("Accès refusé : Vous devez être connecté en tant qu'entreprise.");
}

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire (avec une petite protection basique)
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $contract = $_POST['contract_type'];
    $salary = trim($_POST['salary']);
    $keywords = trim($_POST['keywords']);
    $desc = trim($_POST['description']);
    
    // L'ID de l'entreprise est récupéré directement depuis la session (plus sûr)
    $company_id = $_SESSION['user_id']; 

    try {
        // Préparation de la requête SQL
        $sql = "INSERT INTO jobs (company_id, title, description, location, contract_type, salary, keywords) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // Exécution de la requête avec les vraies valeurs
        $stmt->execute([$company_id, $title, $desc, $location, $contract, $salary, $keywords]);

        // Si tout s'est bien passé, on redirige vers le dashboard avec un message
        echo "<script>
            alert('Votre offre a été publiée avec succès !'); 
            window.location.href='dashboard.php';
        </script>";

    } catch (PDOException $e) {
        // En cas d'erreur SQL, on l'affiche
        die("Erreur lors de la publication : " . $e->getMessage());
    }
} else {
    // Si quelqu'un essaie d'accéder à cette page sans envoyer de formulaire
    header("Location: dashboard.php");
    exit;
}
?>
