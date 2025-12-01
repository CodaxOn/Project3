<?php 
use 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conseils & Aide - StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar_partial.php'; ?> 
    <section class="section active-section" style="padding-top: 100px;">
        <div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
            <h2>🎓 Conseils & Ressources Carrières</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                
                <div class="card" style="border-radius: 12px;">
                    <h3 style="color: #0c57e5;"><i class="fa-solid fa-file-lines"></i> Rédaction de CV</h3>
                    <p>Découvrez nos guides pour optimiser la structure, le contenu et l'impact de votre CV.</p>
                    <a href="#" class="cta-btn" style="background:#0c57e5; padding:10px 15px; border-radius:8px; display:inline-block; margin-top:15px; font-size:0.9rem;">
                        Lire le guide
                    </a>
                </div>

                <div class="card" style="border-radius: 12px;">
                    <h3 style="color: #0c57e5;"><i class="fa-solid fa-microphone"></i> Réussir son Entretien</h3>
                    <p>Les questions types, comment se préparer, et les erreurs à éviter absolument.</p>
                    <a href="#" class="cta-btn" style="background:#0c57e5; padding:10px 15px; border-radius:8px; display:inline-block; margin-top:15px; font-size:0.9rem;">
                        Conseils Entretien
                    </a>
                </div>

                <div class="card" style="border-radius: 12px;">
                    <h3 style="color: #0c57e5;"><i class="fa-solid fa-user-tie"></i> Trouver un Stage / Alternance</h3>
                    <p>Méthodes de recherche efficaces et lettres de motivation percutantes.</p>
                    <a href="#" class="cta-btn" style="background:#0c57e5; padding:10px 15px; border-radius:8px; display:inline-block; margin-top:15px; font-size:0.9rem;">
                        Nos méthodes
                    </a>
                </div>
            </div>
        </div>
    </section>

    </body>
</html>
