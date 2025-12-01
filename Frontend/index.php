<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageBoard - Emplois et Stages</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div style="background: #2d2d2d; color: white; padding: 5px; text-align:center; font-size: 11px;">
        <?php if(isset($_SESSION['user_id'])): ?>
            Connecté : <strong><?= ucfirst($_SESSION['role']) ?></strong> (<?= htmlspecialchars($_SESSION['nom']) ?>)
        <?php else: ?>
            Mode visiteur (Non connecté)
        <?php endif; ?>
    </div>

    <nav class="navbar">
        <div class="logo">Stage<span class="highlight">Board</span></div>
<ul class="nav-links">
            <li><a href="#" class="active" onclick="showSection('accueil')">Accueil</a></li>
            <li><a href="#" onclick="showSection('offres')">Nos Offres</a></li>
            <li><a href="#" onclick="showSection('partenaires')">Entreprises</a></li>
            
            <li><a href="#" onclick="showSection('conseils')">Conseils & Aide</a></li> 
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'candidat'): ?>
                    <li><a href="#" onclick="showSection('candidatures')">Mes Candidatures</a></li>
                <?php endif; ?>

                <li>
                    <a href="dashboard.php" class="btn-login">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="auth.php?logout=true" style="color: #ff5e57;">
                        <i class="fa-solid fa-power-off"></i>
                    </a>
                </li>
            <?php else: ?>
                <li><a href="#" onclick="showSection('connexion')" class="btn-login"><i class="fa-solid fa-user"></i> Connexion</a></li>
            <?php endif; ?>
        </ul>
        <div class="burger" onclick="toggleBurgerMenu()">
            <i class="fa-solid fa-bars"></i>
        </div>
    </nav>

    <section id="accueil" class="section active-section">
        <div class="hero">
            <h1>Trouvez le job <br>qui vous correspond vraiment</h1>
            <p>Plus de 10 000 offres d'emploi, de stage et d'alternance disponibles dès maintenant sur StageBoard.</p>
        </div>
    </section>

    <section id="offres" class="section">
        
        <div class="search-bar-container">
            <form action="index.php#offres" method="GET">
                <div style="flex: 2; position: relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:20px; top:18px; color:#999;"></i>
                    <input type="text" name="q" class="search-input" 
                           placeholder="Métier, mots-clés ou entreprise" 
                           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" 
                           style="padding-left: 50px !important;">
                </div>
                
                <div style="flex: 1; position: relative; border-left: 1px solid #eee;">
                    <i class="fa-solid fa-location-dot" style="position:absolute; left:20px; top:18px; color:#999;"></i>
                    <select name="loc" class="filter-select" style="padding-left: 45px !important;">
                        <option value="">Toute la France</option>
                        <option value="Paris" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Paris') ? 'selected' : '' ?>>Paris</option>
                        <option value="Lyon" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Lyon') ? 'selected' : '' ?>>Lyon</option>
                        <option value="Marseille" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Marseille') ? 'selected' : '' ?>>Marseille</option>
                        <option value="Télétravail" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Télétravail') ? 'selected' : '' ?>>Télétravail</option>
                    </select>
                </div>
                
                <button type="submit" class="search-btn">
                    Rechercher
                </button>
                
                <?php if(isset($_GET['q']) || isset($_GET['loc'])): ?>
                    <a href="index.php#offres" class="search-btn" style="background: #eee !important; color: #333 !important; padding: 14px 20px !important; margin-left: 5px;">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="offers-container">
            <div class="offers-list">
                <?php
                try {
                    $sql = "SELECT jobs.id as id_job, jobs.*, companies.company_name, companies.logo 
                            FROM jobs 
                            JOIN companies ON jobs.company_id = companies.id 
                            WHERE 1=1";
                    
                    $params = [];

                    if (!empty($_GET['q'])) {
                        $sql .= " AND (jobs.title LIKE ? OR jobs.description LIKE ? OR jobs.keywords LIKE ?)";
                        $searchTerm = "%" . $_GET['q'] . "%";
                        $params[] = $searchTerm;
                        $params[] = $searchTerm;
                        $params[] = $searchTerm;
                    }

                    if (!empty($_GET['loc'])) {
                        $sql .= " AND jobs.location LIKE ?";
                        $params[] = "%" . $_GET['loc'] . "%";
                    }

                    $sql .= " ORDER BY jobs.created_at DESC LIMIT 20";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($offres) > 0):
                        foreach ($offres as $offre): 
                            $monID = $offre['id_job']; 
                ?>
                    <div class="offer-card" 
                         onclick="showOfferDetails('offer<?= $monID ?>')" 
                         data-offer-id="offer<?= $monID ?>">
                        
                        <div class="header-job" style="display:flex; justify-content:space-between; align-items:start;">
                            <div>
                                <h3><?= htmlspecialchars($offre['title']) ?></h3>
                                <div class="company"><?= htmlspecialchars($offre['company_name']) ?></div>
                            </div>
                            
                            <?php if (!empty($offre['logo'])): ?>
                                <img src="uploads/<?= htmlspecialchars($offre['logo']) ?>" 
                                     alt="Logo" 
                                     style="width:50px; height:50px; object-fit:contain; border-radius:8px; border:1px solid #f0f0f0;">
                            <?php endif; ?>
                        </div>
                        
                        <div style="font-size:0.9rem; color:#595959; margin: 10px 0;">
                            <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($offre['location']) ?>
                        </div>
                        
                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top: 10px;">
                            <span style="background:#f3f2f1; padding:4px 8px; border-radius:4px; font-size:12px; color:#595959;">
                                <?= htmlspecialchars($offre['salary'] ?? 'Salaire non affiché') ?>
                            </span>
                            <span style="background:#f3f2f1; padding:4px 8px; border-radius:4px; font-size:12px; color:#595959;">
                                <?= htmlspecialchars($offre['contract_type']) ?>
                            </span>
                        </div>
                        
                        <div style="margin-top:15px; font-size:0.8rem; color:#0c57e5; font-weight: 600;">
                             Candidature facile
                        </div>

                        <div id="details-offer<?= $monID ?>" style="display:none;">
                            <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                                <div style="display:flex; justify-content:space-between; align-items: center;">
                                    <h2 style="font-size: 1.5rem; margin-bottom: 5px;"><?= htmlspecialchars($offre['title']) ?></h2>
                                    <?php if (!empty($offre['logo'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($offre['logo']) ?>" style="width:60px; height:60px; object-fit:contain;">
                                    <?php endif; ?>
                                </div>

                                <div style="font-size: 1.1rem; color: #333; margin-bottom: 5px; font-weight: 600;">
                                    <?= htmlspecialchars($offre['company_name']) ?>
                                </div>
                                <div style="font-size: 0.95rem; color: #666; margin-bottom: 20px;">
                                    <?= htmlspecialchars($offre['location']) ?> • <?= htmlspecialchars($offre['contract_type']) ?>
                                </div>
                                
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'candidat'): ?>
                                    <button onclick="openModal(<?= $monID ?>)" style="background-color: #2557a7; color: white; font-weight: 700; padding: 12px 24px; border-radius: 8px; border: none; font-size: 16px; cursor: pointer; width: 100%; max-width: 300px;">
                                        Postuler maintenant
                                    </button>
                                <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'recruteur'): ?>
                                    <div style="background:#f0f2f5; padding:10px; border-radius:8px; color:#666; font-size:14px; text-align:center;">
                                        <i class="fa-solid fa-eye"></i> Aperçu Recruteur
                                    </div>
                                <?php else: ?>
                                    <button onclick="showSection('connexion')" style="background-color: #2557a7; color: white; font-weight: 700; padding: 12px 24px; border-radius: 8px; border: none; font-size: 16px; cursor: pointer; width: 100%; max-width: 300px;">
                                        Postuler maintenant
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div style="color: #333; font-size: 1rem; line-height: 1.7;">
                                <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;">Détails du poste</h3>
                                <div style="margin-bottom: 20px; display: flex; gap: 20px;">
                                    <div><i class="fa-solid fa-money-bill-wave" style="color:#999;"></i> <?= htmlspecialchars($offre['salary'] ?? 'Non précisé') ?></div>
                                    <div><i class="fa-solid fa-briefcase" style="color:#999;"></i> <?= htmlspecialchars($offre['contract_type']) ?></div>
                                </div>
                                <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 15px;">Description</h3>
                                <div style="white-space: pre-line;">
                                    <?= strip_tags($offre['description']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                        endforeach; 
                    else: 
                ?>
                    <div style="padding:60px; text-align:center; background:white; border-radius:16px; border: 1px solid #eee;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size:40px; color:#ddd; margin-bottom:20px;"></i>
                        <h3 style="color:#333; margin-bottom:10px;">Aucune offre trouvée</h3>
                        <p style="color:#666; margin-bottom:20px;">Essayez d'élargir votre recherche.</p>
                        <a href="index.php#offres" style="color:#0c57e5; font-weight:600;">Voir toutes les offres</a>
                    </div>
                <?php endif; 
                } catch (Exception $e) { echo "Erreur système."; }
                ?>
            </div>

            <div class="offer-details-panel">
                <div id="offer-details-content">
                    <div class="empty-state" style="text-align:center; padding-top:100px; color:#999;">
                        <i class="fa-regular fa-file-lines" style="font-size:40px; margin-bottom:20px; opacity: 0.5;"></i>
                        <p style="font-size: 1.1rem;">Sélectionnez une offre à gauche <br>pour afficher les détails ici.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="candidatures" class="section">
        <h2>Suivi de vos candidatures</h2>
        <div class="table-container" style="background: white; padding: 40px; text-align: center;">
             <i class="fa-solid fa-chart-line" style="font-size: 3rem; color: #ddd; margin-bottom: 20px;"></i>
             <p>Pour un suivi détaillé, rendez-vous sur votre <a href="dashboard.php" style="color:#0c57e5; font-weight:600;">Tableau de bord</a>.</p>
        </div>
    </section>

    <section id="partenaires" class="section">
        <h2>Ils nous font confiance</h2>
        <div class="partners-grid">
            <div class="partner-card"><i class="fa-brands fa-google" style="color: #DB4437;"></i><p>Google</p></div>
            <div class="partner-card"><i class="fa-brands fa-amazon" style="color: #FF9900;"></i><p>Amazon</p></div>
            <div class="partner-card"><i class="fa-brands fa-microsoft" style="color: #00A4EF;"></i><p>Microsoft</p></div>
            <div class="partner-card"><i class="fa-brands fa-spotify" style="color: #1DB954;"></i><p>Spotify</p></div>
        </div>
    </section>

    <section id="connexion" class="section">
        <div class="auth-container">
            <h2>Bienvenue sur StageBoard</h2>
            <div class="auth-tabs">
                <button class="tab-btn active" onclick="switchAuthSection('candidat')">Candidat</button>
                <button class="tab-btn" onclick="switchAuthSection('recruteur')">Entreprise</button>
            </div>

            <div id="candidat-forms" class="auth-group active-group">
                <div class="sub-tabs">
                    <button class="sub-tab-btn active" onclick="switchAuthForm('candidat', 'login')">Se connecter</button>
                    <button class="sub-tab-btn" onclick="switchAuthForm('candidat', 'register')">Créer un compte</button>
                </div>
                
                <form id="form-candidat-login" class="auth-form active-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="login">
                    <div class="input-group">
                        <label for="candidat-login-email">Email</label>
                        <input type="email" id="candidat-login-email" name="email" placeholder="exemple@mail.com" required>
                    </div>
                    <div class="input-group">
                        <label for="candidat-login-password">Mot de passe</label>
                        <input type="password" id="candidat-login-password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="text-right">
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>
                    <button type="submit" class="btn-submit">Me connecter</button>
                </form>

                <form id="form-candidat-register" class="auth-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="role" value="candidat">
                    <div class="input-group">
                        <label for="candidat-register-nom">Nom complet</label>
                        <input type="text" id="candidat-register-nom" name="nom" required>
                    </div>
                    <div class="input-group">
                        <label for="candidat-register-email">Email</label>
                        <input type="email" id="candidat-register-email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="candidat-register-password">Mot de passe</label>
                        <input type="password" id="candidat-register-password" name="password" required>
                    </div>
                    <button type="submit" class="btn-submit">S'inscrire</button>
                </form>
            </div>

            <div id="recruteur-forms" class="auth-group">
                <div class="sub-tabs">
                    <button class="sub-tab-btn active" onclick="switchAuthForm('recruteur', 'login')">Connexion RH</button>
                    <button class="sub-tab-btn" onclick="switchAuthForm('recruteur', 'register')">Inscription Entreprise</button>
                </div>

                <form id="form-recruteur-login" class="auth-form active-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="login">
                    <div class="input-group">
                        <label for="recruteur-login-email">Email professionnel</label>
                        <input type="email" id="recruteur-login-email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="recruteur-login-password">Mot de passe</label>
                        <input type="password" id="recruteur-login-password" name="password" required>
                    </div>
                    <button type="submit" class="btn-submit">Accès Recruteur</button>
                </form>

                <form id="form-recruteur-register" class="auth-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="role" value="recruteur">
                    <div class="input-group">
                        <label for="recruteur-register-entreprise">Nom de l'entreprise</label>
                        <input type="text" id="recruteur-register-entreprise" name="entreprise" required>
                    </div>
                    <div class="input-group">
                        <label for="recruteur-register-siret">Numéro SIRET</label>
                        <input type="text" id="recruteur-register-siret" name="siret" required>
                    </div>
                    <div class="input-group">
                        <label for="recruteur-register-email">Email pro</label>
                        <input type="email" id="recruteur-register-email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="recruteur-register-password">Mot de passe</label>
                        <input type="password" id="recruteur-register-password" name="password" required>
                    </div>
                    <button type="submit" class="btn-submit">Créer mon espace RH</button>
                </form>
            </div>
        </div>
    </section>

    <footer style="background: white; border-top: 1px solid #eee; color: #333; padding: 60px 20px; margin-top: 80px;">
        <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:40px;">
            <div>
                <h3 style="font-size: 1.5rem; margin-bottom: 15px;">Stage<span style="color:#0c57e5;">Board</span></h3>
                <p style="color:#666; font-size: 0.9rem;">La plateforme de référence pour l'emploi et le recrutement.</p>
            </div>
            <div>
                <h4 style="margin-bottom: 15px;">Candidats</h4>
                <ul style="color:#666; line-height: 2;">
                    <li><a href="#" onclick="showSection('offres')">Toutes les offres</a></li>
                    <li><a href="#">Mon espace</a></li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom: 15px;">Entreprises</h4>
                <ul style="color:#666; line-height: 2;">
                    <li><a href="#">Poster une annonce</a></li>
                    <li><a href="#">Solutions RH</a></li>
                </ul>
            </div>
        </div>
        <div style="text-align:center; margin-top:50px; padding-top:20px; border-top:1px solid #f0f0f0; color:#999; font-size: 0.8rem;">
            © 2025 StageBoard - Tous droits réservés.
        </div>
    </footer>
    
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            
            <div class="form-header">
                <h1 style="font-size: 2rem; color: var(--text-main); margin-bottom: 5px;">Déposer ma candidature</h1>
                <p style="color: var(--text-muted); margin-bottom: 20px;">Complétez les champs ci-dessous et joignez votre CV pour cette offre.</p>
            </div>

            <form id="applyForm" action="apply.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="job_id" id="modal_job_id" value="">
                
                <div class="form-grid">
                    <div class="input-group">
                        <label for="modal_prenom">Prénom *</label>
                        <input type="text" id="modal_prenom" name="prenom" required>
                    </div>
                    <div class="input-group">
                        <label for="modal_nom">Nom *</label>
                        <input type="text" id="modal_nom" name="nom" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="modal_email">Email *</label>
                        <input type="email" id="modal_email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="modal_phone">Téléphone *</label>
                        <input type="tel" id="modal_phone" name="phone" required>
                    </div>

                    <div class="input-group full-width">
                        <label for="modal_cv">Joindre mon CV (PDF/DOCX) *</label>
                        <input type="file" id="modal_cv" name="cv_file" required accept=".pdf,.doc,.docx" style="padding: 15px; background: #f0f2f5;">
                    </div>

                    <div class="input-group full-width">
                        <label for="modal_message">Message / Lettre de motivation (Facultatif)</label>
                        <textarea id="modal_message" name="message" rows="4" placeholder="Décrivez votre motivation ou vos questions..." maxlength="1000"></textarea>
                        <div class="char-count">0/1000 caractères</div>
                    </div>

                    <div class="input-group full-width" style="margin-top: 10px;">
                        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                             <input type="checkbox" id="captcha" required style="width: auto; margin-right: 10px;">
                             <label for="captcha" style="margin-bottom: 0; font-weight: normal;">Je ne suis pas un robot</label>
                             <img src="https://via.placeholder.com/100x40?text=CAPTCHA" alt="Captcha" style="border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    </div>

                    <div class="input-group full-width">
                        <button type="submit" class="btn-submit-modal">
                            Envoyer ma candidature <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        // JS pour initialiser la vue de connexion après la correction HTML
        document.addEventListener('DOMContentLoaded', () => {
            const currentHash = window.location.hash.substring(1);
            if (currentHash && document.getElementById(currentHash)) {
                // Si l'URL a un #section, on l'affiche
                showSection(currentHash);
            } else {
                // Sinon, on affiche l'accueil par défaut
                showSection('accueil');
            }

            // Pour que les onglets de connexion s'initialisent correctement si la section est visible
            if (document.getElementById('connexion')) {
                // On active le candidat par défaut 
                if (typeof switchAuthSection === 'function') {
                    switchAuthSection('candidat'); 
                }
            }
        });
    </script>

</body>
</html>