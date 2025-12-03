<?php 
use 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conseils Carrière - StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f9fafb; }
        .conseil-container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .article-card { background: white; border-radius: 16px; padding: 40px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .article-header { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .article-title { font-size: 2rem; color: #1a1a1a; margin-bottom: 10px; }
        .article-meta { color: #666; font-size: 0.9rem; }
        .article-content { font-size: 1.1rem; color: #444; line-height: 1.8; }
        .article-content h3 { color: #0c57e5; margin-top: 30px; margin-bottom: 15px; }
        .article-content ul { padding-left: 20px; margin-bottom: 20px; }
        .article-content li { margin-bottom: 10px; list-style-type: disc; }
        .back-btn { display: inline-flex; align-items: center; gap: 10px; color: #666; text-decoration: none; font-weight: 600; margin-bottom: 20px; }
        .back-btn:hover { color: #0c57e5; }
    </style>
</head>
<body>

    <!-- Navbar simplifiée -->
    <nav class="navbar">
        <a href="index.php" class="logo">Stage<span>Board</span></a>
        <a href="index.php" class="btn-login">Retour au site</a>
    </nav>

    <div class="conseil-container">
        <a href="index.php#conseils" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Retour aux conseils</a>

        <!-- Article 1 : CV -->
        <div id="cv" class="article-card">
            <div class="article-header">
                <h1 class="article-title"><i class="fa-solid fa-file-lines" style="color:#0c57e5;"></i> Le Guide du CV Parfait</h1>
                <p class="article-meta">Lecture : 5 min • Mis à jour récemment</p>
            </div>
            <div class="article-content">
                <p>Un CV efficace doit être clair, concis et adapté à l'offre. Voici les règles d'or pour 2025 :</p>
                
                <h3>1. La structure idéale</h3>
                <ul>
                    <li><strong>En-tête :</strong> Nom, contact, lien LinkedIn/Portfolio.</li>
                    <li><strong>Titre du poste :</strong> Adaptez-le à l'annonce visée !</li>
                    <li><strong>Expériences :</strong> De la plus récente à la plus ancienne. Utilisez des verbes d'action.</li>
                    <li><strong>Compétences :</strong> Listez vos hard skills (outils) et soft skills (qualités).</li>
                </ul>

                <h3>2. Les erreurs à éviter</h3>
                <p>Ne mettez pas de photo floue, évitez les jauges de compétences (ex: Anglais 80%), et relisez-vous pour éviter les fautes d'orthographe qui sont rédhibitoires.</p>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Article 2 : Entretien -->
        <div id="entretien" class="article-card">
            <div class="article-header">
                <h1 class="article-title"><i class="fa-solid fa-microphone" style="color:#0c57e5;"></i> Réussir son Entretien</h1>
                <p class="article-meta">Lecture : 4 min • Top conseils RH</p>
            </div>
            <div class="article-content">
                <p>L'entretien est l'étape décisive. La préparation est la clé de la réussite.</p>

                <h3>Les questions classiques</h3>
                <ul>
                    <li>"Parlez-moi de vous" : Préparez un pitch de 2 minutes résumant votre parcours.</li>
                    <li>"Pourquoi notre entreprise ?" : Montrez que vous vous êtes renseigné sur eux.</li>
                    <li>"Vos défauts" : Citez de vrais défauts mais expliquez comment vous les travaillez.</li>
                </ul>

                <h3>L'attitude gagnante</h3>
                <p>Arrivez 5 minutes en avance, souriez, regardez vos interlocuteurs dans les yeux et posez des questions à la fin pour montrer votre intérêt.</p>
            </div>
        </div>

        <!-- Article 3 : Recherche -->
        <div id="recherche" class="article-card">
            <div class="article-header">
                <h1 class="article-title"><i class="fa-solid fa-user-tie" style="color:#0c57e5;"></i> Stratégie de Recherche</h1>
                <p class="article-meta">Lecture : 3 min • Méthodologie</p>
            </div>
            <div class="article-content">
                <p>Ne postulez pas au hasard. Une recherche structurée donne de meilleurs résultats.</p>

                <h3>La méthode entonnoir</h3>
                <ul>
                    <li>Ciblez 10 entreprises de rêve et faites des candidatures ultra-personnalisées.</li>
                    <li>Utilisez LinkedIn pour contacter directement les managers (pas seulement les RH).</li>
                    <li>Relancez toujours votre candidature après 7-10 jours sans réponse.</li>
                </ul>
            </div>
        </div>

    </div>

</body>
=======
    </body>
>>>>>>> faa2123b984eedfea4faee50de0378251508ee81
</html>
