<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- DEBUG BAR (Optionnel) -->
    <div style="background: #f3f2f1; color: #2d2d2d; padding: 8px; text-align:center; font-size: 12px; border-bottom:1px solid #e4e2e0;">
        <?php if(isset($_SESSION['user_id'])): ?>
            Connecté : <strong><?= ucfirst($_SESSION['role']) ?></strong> (<?= htmlspecialchars($_SESSION['nom']) ?>)
        <?php else: ?>
            Non connecté
        <?php endif; ?>
    </div>

    <nav class="navbar">
        <div class="logo">Stage<span class="highlight">Board</span></div>
        <ul class="nav-links">
            <li><a href="#" class="active" onclick="showSection('accueil')">Accueil</a></li>
            <li><a href="#" onclick="showSection('offres')">Nos Offres</a></li>
            <li><a href="#" onclick="showSection('partenaires')">Entreprises</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'candidat'): ?>
                    <li><a href="#" onclick="showSection('candidatures')">Mes Candidatures</a></li>
                <?php endif; ?>

                <li>
                    <a href="dashboard.php" class="btn-login" style="background-color: #2557a7; border:none;">
                        <i class="fa-solid fa-gauge"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="auth.php?logout=true" class="btn-login" style="background-color: #d93025; padding: 8px 15px;">
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
            <h1>Trouvez votre prochain emploi</h1>
            <p>Des milliers d'offres d'emploi, de stage et d'alternance vous attendent.</p>
            <button class="cta-btn" onclick="showSection('offres')" style="background-color:#2557a7;">Voir les offres</button>
        </div>
    </section>

    <section id="offres" class="section">
        
        <!-- MOTEUR DE RECHERCHE SQL -->
        <div class="search-bar-container" style="max-width: 1000px; margin: 0 auto 20px auto;">
            <form action="index.php#offres" method="GET" style="display:flex; gap:10px; width:100%; flex-wrap:wrap;">
                <input type="text" name="q" class="search-input" 
                       placeholder="Intitulé du poste, mots-clés..." 
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" 
                       style="flex:2; min-width:200px;">
                
                <select name="loc" class="filter-select" style="flex:1; min-width:150px;">
                    <option value="">Toute la France</option>
                    <option value="Paris" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Paris') ? 'selected' : '' ?>>Paris</option>
                    <option value="Lyon" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Lyon') ? 'selected' : '' ?>>Lyon</option>
                    <option value="Marseille" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Marseille') ? 'selected' : '' ?>>Marseille</option>
                    <option value="Télétravail" <?= (isset($_GET['loc']) && $_GET['loc'] == 'Télétravail') ? 'selected' : '' ?>>Télétravail</option>
                </select>
                
                <button type="submit" class="search-btn" style="background-color:#2557a7; flex:0.5; min-width:100px;">
                    Rechercher
                </button>
                
                <?php if(isset($_GET['q']) || isset($_GET['loc'])): ?>
                    <a href="index.php#offres" class="search-btn" style="background-color:#7f8c8d; text-align:center; display:flex; align-items:center; justify-content:center; text-decoration:none;">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="offers-container">
            <!-- LISTE DES OFFRES (GAUCHE) -->
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
                    <!-- CARTE OFFRE -->
                    <div class="offer-card" 
                         onclick="showOfferDetails('offer<?= $monID ?>')" 
                         data-offer-id="offer<?= $monID ?>"
                         style="border:1px solid #e4e2e0; border-radius:8px; padding:16px; margin-bottom:16px; cursor:pointer; transition:box-shadow 0.2s;">
                        
                        <div class="header-job" style="display:flex; justify-content:space-between; align-items:start;">
                            <div>
                                <h3 style="font-size:18px; color:#2d2d2d; margin-bottom:4px;">
                                    <?= htmlspecialchars($offre['title']) ?>
                                </h3>
                                <div style="font-size:14px; color:#2d2d2d; margin-bottom:8px;">
                                    <?= htmlspecialchars($offre['company_name']) ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($offre['logo'])): ?>
                                <img src="uploads/<?= htmlspecialchars($offre['logo']) ?>" 
                                     alt="Logo" 
                                     style="width:50px; height:50px; object-fit:contain; border-radius:4px; border:1px solid #eee;">
                            <?php endif; ?>
                        </div>
                        
                        <div style="font-size:14px; color:#595959; margin-bottom:8px;">
                            <?= htmlspecialchars($offre['location']) ?>
                        </div>
                        
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <span style="background:#f3f2f1; padding:4px 8px; border-radius:4px; font-size:12px; color:#595959;">
                                <?= htmlspecialchars($offre['salary'] ?? 'Salaire non affiché') ?>
                            </span>
                            <span style="background:#f3f2f1; padding:4px 8px; border-radius:4px; font-size:12px; color:#595959;">
                                <?= htmlspecialchars($offre['contract_type']) ?>
                            </span>
                        </div>
                        
                        <div style="margin-top:12px; font-size:12px; color:#767676;">
                            <i class="fa-solid fa-paper-plane"></i> Candidature simplifiée
                        </div>

                        <!-- CONTENU CACHÉ -->
                        <div id="details-offer<?= $monID ?>" style="display:none;">
                            <div style="border-bottom: 1px solid #e4e2e0; padding-bottom: 16px; margin-bottom: 16px;">
                                <div style="display:flex; justify-content:space-between;">
                                    <h2 style="font-size: 22px; font-weight: 700; color: #2d2d2d; margin-bottom: 8px;">
                                        <?= htmlspecialchars($offre['title']) ?>
                                    </h2>
                                    <?php if (!empty($offre['logo'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($offre['logo']) ?>" style="width:60px; height:60px; object-fit:contain;">
                                    <?php endif; ?>
                                </div>

                                <div style="font-size: 16px; color: #2d2d2d; margin-bottom: 4px;">
                                    <a href="#" style="color: #2d2d2d; text-decoration: underline; font-weight: 600;">
                                        <?= htmlspecialchars($offre['company_name']) ?>
                                    </a>
                                </div>
                                <div style="font-size: 14px; color: #595959; margin-bottom: 16px;">
                                    <?= htmlspecialchars($offre['location']) ?>
                                </div>
                                
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'candidat'): ?>
                                    <form action="apply.php" method="POST">
                                        <input type="hidden" name="job_id" value="<?= $monID ?>">
                                        <button type="submit" 
                                            style="background-color: #2557a7; color: white; font-weight: 700; padding: 12px 24px; border-radius: 8px; border: none; font-size: 16px; cursor: pointer; width: 100%; max-width: 300px;">
                                            Postuler maintenant
                                        </button>
                                    </form>
                                <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'recruteur'): ?>
                                    <div style="background:#f3f2f1; padding:10px; border-radius:4px; color:#595959; font-size:14px;">
                                        <i class="fa-solid fa-info-circle"></i> Mode aperçu (Recruteur)
                                    </div>
                                <?php else: ?>
                                    <button onclick="showSection('connexion')" 
                                        style="background-color: #2557a7; color: white; font-weight: 700; padding: 12px 24px; border-radius: 8px; border: none; font-size: 16px; cursor: pointer; width: 100%; max-width: 300px;">
                                        Postuler maintenant
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div style="color: #2d2d2d; font-size: 14px; line-height: 1.6;">
                                <h3 style="font-size: 18px; font-weight: 700; margin-top: 0; margin-bottom: 12px;">Détails de l'emploi</h3>
                                <div style="margin-bottom: 16px;">
                                    <div style="margin-bottom: 8px;">
                                        <i class="fa-solid fa-sack-dollar" style="color: #595959; width: 20px;"></i>
                                        <span style="font-weight: 600;">Salaire:</span> <?= htmlspecialchars($offre['salary'] ?? 'Non précisé') ?>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-briefcase" style="color: #595959; width: 20px;"></i>
                                        <span style="font-weight: 600;">Type:</span> <?= htmlspecialchars($offre['contract_type']) ?>
                                    </div>
                                </div>
                                <hr style="border: 0; border-top: 1px solid #e4e2e0; margin: 20px 0;">
                                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">Description du poste</h3>
                                <div style="white-space: pre-line; color: #2d2d2d;">
                                    <?= strip_tags($offre['description']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                        endforeach; 
                    else: 
                ?>
                    <div style="padding:40px; text-align:center; background:white; border-radius:8px;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size:40px; color:#ccc; margin-bottom:10px;"></i>
                        <p>Aucune offre trouvée.</p>
                        <a href="index.php#offres" style="color:#2557a7;">Voir toutes les offres</a>
                    </div>
                <?php endif; 
                } catch (Exception $e) { echo "Erreur SQL : " . $e->getMessage(); }
                ?>
            </div>

            <!-- PANNEAU DE DROITE -->
            <div class="offer-details-panel" style="background:white; border:1px solid #e4e2e0; border-radius:8px; padding:24px; height:calc(100vh - 150px); overflow-y:auto; position:sticky; top:20px;">
                <div id="offer-details-content">
                    <div class="empty-state" style="text-align:center; padding-top:50px; color:#595959;">
                        <i class="fa-solid fa-arrow-left" style="font-size:24px; margin-bottom:10px;"></i>
                        <p>Sélectionnez une offre pour voir les détails.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="candidatures" class="section">
        <h2>Suivi de vos candidatures</h2>
        <div class="table-container">
             <p>Connectez-vous à votre <a href="dashboard.php">Dashboard</a> pour voir le suivi.</p>
        </div>
    </section>

    <section id="partenaires" class="section">
        <h2>Nos Entreprises Partenaires</h2>
        <div class="partners-grid">
            <div class="partner-card"><i class="fa-brands fa-google"></i><p>Google</p></div>
            <div class="partner-card"><i class="fa-brands fa-amazon"></i><p>Amazon</p></div>
        </div>
    </section>

    <section id="connexion" class="section">
        <div class="auth-container">
            <h2>Espace Authentification</h2>
            <div class="auth-tabs">
                <button class="tab-btn active" onclick="switchAuthSection('candidat', 'login')">Candidat</button>
                <button class="tab-btn" onclick="switchAuthSection('recruteur', 'login')">Entreprise</button>
            </div>

            <div id="candidat-forms" class="auth-group active-group">
                <div class="sub-tabs">
                    <button class="sub-tab-btn active" onclick="switchAuthForm('candidat', 'login')">Login</button>
                    <button class="sub-tab-btn" onclick="switchAuthForm('candidat', 'register')">Register</button>
                </div>
                <form id="form-candidat-login" class="auth-form active-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="login">
                    <h3>Candidat - Login</h3>
                    <div class="input-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    
                    <!-- LIEN MOT DE PASSE OUBLIÉ -->
                    <div style="text-align:right; margin-bottom:10px;">
                        <a href="#" onclick="alert('Fonctionnalité bientôt disponible. Contactez l\'admin : admin@stageboard.com')" style="color:#2557a7; font-size:12px;">Mot de passe oublié ?</a>
                    </div>
                    
                    <button type="submit" class="btn-submit">Se connecter</button>
                </form>
                <form id="form-candidat-register" class="auth-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="role" value="candidat">
                    <h3>Candidat - Register</h3>
                    <div class="input-group"><label>Nom d'utilisateur</label><input type="text" name="nom" required></div>
                    <div class="input-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <button type="submit" class="btn-submit">Créer le compte</button>
                </form>
            </div>

            <div id="recruteur-forms" class="auth-group">
                <div class="sub-tabs">
                    <button class="sub-tab-btn active" onclick="switchAuthForm('recruteur', 'login')">Login</button>
                    <button class="sub-tab-btn" onclick="switchAuthForm('recruteur', 'register')">Register</button>
                </div>
                <form id="form-recruteur-login" class="auth-form active-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="login">
                    <h3>Recruteur - Login</h3>
                    <div class="input-group"><label>Email pro</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>

                    <!-- LIEN MOT DE PASSE OUBLIÉ -->
                    <div style="text-align:right; margin-bottom:10px;">
                        <a href="#" onclick="alert('Contactez le support : admin@stageboard.com')" style="color:#2557a7; font-size:12px;">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn-submit">Se connecter</button>
                </form>
                <form id="form-recruteur-register" class="auth-form" method="POST" action="auth.php">
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" name="role" value="recruteur">
                    <h3>Recruteur - Register</h3>
                    <div class="input-group"><label>Nom entreprise</label><input type="text" name="entreprise" required></div>
                    <div class="input-group"><label>SIRET</label><input type="text" name="siret" required></div>
                    <div class="input-group"><label>Email pro</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <button type="submit" class="btn-submit">Créer le compte</button>
                </form>
            </div>
        </div>
    </section>

    <!-- FOOTER MIS À JOUR -->
    <footer style="background:#333; color:white; padding:40px 20px; margin-top:50px;">
        <div style="max-width:1000px; margin:0 auto; display:flex; justify-content:space-between; flex-wrap:wrap; gap:20px;">
            <div>
                <h3>Stage<span style="color:#3498db;">Board</span></h3>
                <p style="font-size:14px; color:#ccc;">La plateforme n°1 pour trouver votre stage.</p>
            </div>
            <div>
                <h4>Liens utiles</h4>
                <ul style="list-style:none; padding:0; font-size:14px;">
                    <li><a href="#" onclick="showSection('accueil')" style="color:#ccc; text-decoration:none;">Accueil</a></li>
                    <li><a href="#" onclick="showSection('offres')" style="color:#ccc; text-decoration:none;">Offres</a></li>
                    <li><a href="mailto:contact@stageboard.com" style="color:#ccc; text-decoration:none;">Nous contacter</a></li>
                </ul>
            </div>
            <div>
                <h4>Légal</h4>
                <ul style="list-style:none; padding:0; font-size:14px;">
                    <!-- LIENS ACTIFS VERS VOS NOUVELLES PAGES -->
                    <li><a href="legal.php" style="color:#ccc; text-decoration:none;">Mentions légales</a></li>
                    <li><a href="privacy.php" style="color:#ccc; text-decoration:none;">Confidentialité</a></li>
                </ul>
            </div>
        </div>
        <div style="text-align:center; margin-top:30px; border-top:1px solid #444; padding-top:20px; font-size:12px; color:#777;">
            © 2025 StageBoard - Tous droits réservés.
        </div>
    </footer>

    <script src="script.js"></script>

</body>
</html>
