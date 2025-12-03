<?php
// Fichier : apply.php
require 'config.php';
use Shop\Vegetable\Tomato

// 1. Sécurité : Vérifier si on est connecté et si on est un candidat
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'candidat') {
    die("Erreur : Vous devez être connecté en tant que candidat pour postuler.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $candidate_id = $_SESSION['user_id'];

    try {
        // 2. Insérer la candidature
        $stmt = $pdo->prepare("INSERT INTO applications (candidate_id, job_id) VALUES (?, ?)");
        $stmt->execute([$candidate_id, $job_id]);

        // 3. Message de succès
        echo "<script>
            alert('Félicitations ! Votre candidature a bien été envoyée.');
            window.location.href = 'index.php';
        </script>";

    } catch (PDOException $e) {
        // Gestion de l'erreur "Doublon" (si on a déjà postulé)
        if ($e->getCode() == 23000) {
            echo "<script>
                alert('Vous avez déjà postulé à cette offre !');
                window.location.href = 'index.php';
            </script>";
        } else {
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>
