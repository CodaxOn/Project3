<?php
// Démarre la session PHP au tout début de la page
// OBLIGATOIRE pour vérifier si l'utilisateur est connecté.
session_start();

// Fonction de vérification de session simple pour la barre de navigation
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
?>
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

    <nav class="navbar">
        <div class="logo">Stage<span class="highlight">Board</span></div>
        <ul class="nav-links">
            <li><a href="#" class="active" onclick="showSection('accueil')">Accueil</a></li>
            <li><a href="#" onclick="showSection('offres')">Nos Offres</a></li>
            <li><a href="#" onclick="showSection('candidatures')">Vos Candidatures</a></li>
            <li><a href="#" onclick="showSection('partenaires')">Entreprises</a></li>
            <li><a href="#" onclick="showSection('conseils')">Conseils</a></li>
            
            <!-- LIEN DE CONNEXION / DÉCONNEXION DYNAMIQUE -->
            <?php if (is_logged_in()): ?>
                 <li><a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></li>
            <?php else: ?>
                 <li><a href="#" onclick="showSection('connexion')" class="btn-login"><i class="fa-solid fa-user"></i> Login / Register</a></li>
            <?php endif; ?>
            
        </ul>
        <div class="burger" onclick="toggleBurgerMenu()">
            <i class="fa-solid fa-bars"></i>
        </div>
    </nav>

    <!-- Affichage d'un message d'état (pour le débogage) -->
    <?php if (is_logged_in()): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; text-align: center; border-bottom: 2px solid #c3e6cb;">
            ✅ **Connecté(e) !** Bienvenue, vous êtes connecté en tant que **<?php echo htmlspecialchars($_SESSION['user_type']); ?>**.
        </div>
    <?php endif; ?>
    
    <section id="accueil" class="section active-section">
        <div class="hero">
            <h1>Trouvez votre voie professionnelle</h1>
            <p>La plateforme numéro 1 pour mettre en relation étudiants et entreprises. Stages, alternances, premiers emplois : tout commence ici.</p>
            <button class="cta-btn" onclick="showSection('offres')">Voir les offres</button>
        </div>
        <div class="presentation-content">
            <h2>Qui sommes-nous ?</h2>
            <p>Notre mission est de faciliter l'insertion professionnelle des jeunes talents en leur offrant un accès direct aux meilleures entreprises partenaires.</p>
        </div>
    </section>

    <section id="offres" class="section">
        <h2>Nos Dernières Offres</h2>

        <div class="search-bar-container">
            <input type="text" class="search-input" placeholder="Développeur web, Marketing, Data..." onkeyup="filterOffers()">
            <select class="filter-select">
                <option value="">Lieu</option>
                <option value="paris">Paris</option>
                <option value="lyon">Lyon</option>
                <option value="teletravail">Télétravail</option>
            </select>
            <select class="filter-select">
                <option value="">Type de contrat</option>
                <option value="cdi">CDI</option>
                <option value="alternance">Alternance</option>
                <option value="stage">Stage</option>
            </select>
            <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
        <div class="offers-container">
            <div class="offers-list">
                <div class="offer-card" onclick="showOfferDetails('offer1')" data-offer-id="offer1" data-keywords="ingénieur développeur backend php symfony cdi teletravail boli care">
                    <div class="header-job">
                        <h3>Ingénieur Développeur Backend H/F</h3>
                        <p class="company">Boli Care</p>
                    </div>
                    <div class="job-info">
                        <span class="location"><i class="fa-solid fa-location-dot"></i> France • Télétravail</span>
                        <span class="salary"><i class="fa-solid fa-euro-sign"></i> 40 000 € à 55 000 € par an</span>
                        <span class="contract-type"><i class="fa-solid fa-briefcase"></i> CDI, Temps plein</span>
                    </div>
                    <div class="response-status">
                        <i class="fa-solid fa-bolt flash"></i>
                        A répondu à 75 % ou plus des candidatures...
                    </div>
                </div>

                <div class="offer-card" onclick="showOfferDetails('offer2')" data-offer-id="offer2" data-keywords="développeur web fullstack react nodejs alternance paris tech solutions">
                    <div class="header-job">
                        <h3>Développeur Web Fullstack</h3>
                        <p class="company">Tech Solutions SAS</p>
                    </div>
                    <div class="job-info">
                        <span class="location"><i class="fa-solid fa-location-dot"></i> Paris (75)</span>
                        <span class="salary"><i class="fa-solid fa-euro-sign"></i> Selon profil</span>
                        <span class="contract-type"><i class="fa-solid fa-briefcase"></i> Alternance</span>
                    </div>
                    <div class="response-status">
                        <i class="fa-solid fa-bolt flash"></i>
                        A répondu rapidement.
                    </div>
                </div>
                
                <div class="offer-card" onclick="showOfferDetails('offer3')" data-offer-id="offer3" data-keywords="data analyst junior sql python stage lyon bigdata corp">
                    <div class="header-job">
                        <h3>Data Analyst Junior</h3>
                        <p class="company">BigData Corp</p>
                    </div>
                    <div class="job-info">
                        <span class="location"><i class="fa-solid fa-location-dot"></i> Lyon (69)</span>
                        <span class="salary"><i class="fa-solid fa-euro-sign"></i> 1200 € / mois</span>
                        <span class="contract-type"><i class="fa-solid fa-briefcase"></i> Stage (6 mois)</span>
                    </div>
                    <div class="response-status">
                        <i class="fa-solid fa-bolt flash"></i>
                        Nouvelle offre.
                    </div>
                </div>
                
                <a href="#" class="more-offers-link">Voir plus de 1000 offres...</a>
            </div>

            <div class="offer-details-panel">
                <div id="offer-details-content">
                    <div class="empty-state">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <p>Sélectionnez une offre dans la liste pour voir ses détails.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="candidatures" class="section">
        <h2>Suivi de vos candidatures</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Poste</th>
                        <th>Entreprise</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Développeur Front-End</td>
                        <td>WebAgency</td>
                        <td>20 Nov 2024</td>
                        <td><span class="status encours">En cours d'examen</span></td>
                    </tr>
                    <tr>
                        <td>Chargé de Com</td>
                        <td>Publicis</td>
                        <td>15 Nov 2024</td>
                        <td><span class="status refuse">Refusé</span></td>
                    </tr>
                    <tr>
                        <td>Support IT</td>
                        <td>Orange</td>
                        <td>10 Nov 2024</td>
                        <td><span class="status accepte">Entretien proposé</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="partenaires" class="section">
        <h2>Nos Entreprises Partenaires</h2>
        <div class="partners-grid">
            <div class="partner-card"><i class="fa-brands fa-google"></i><p>Google</p></div>
            <div class="partner-card"><i class="fa-brands fa-amazon"></i><p>Amazon</p></div>
            <div class="partner-card"><i class="fa-brands fa-microsoft"></i><p>Microsoft</p></div>
            <div class="partner-card"><i class="fa-solid fa-building"></i><p>Thales</p></div>
            <div class="partner-card"><i class="fa-solid fa-building-columns"></i><p>BNP Paribas</p></div>
        </div>
    </section>

    <section id="conseils" class="section">
        <h2>Espace Conseils</h2>
        <div class="advice-grid">
            <div class="advice-card">
                <i class="fa-solid fa-file-pdf"></i>
                <h3>Création de CV</h3>
                <p>Comment faire un CV percutant en 2024 ? Nos astuces de mise en page.</p>
            </div>
            <div class="advice-card">
                <i class="fa-solid fa-handshake"></i>
                <h3>Réussir l'entretien</h3>
                <p>Les 10 questions pièges et comment y répondre.</p>
            </div>
            <div class="advice-card">
                <i class="fa-solid fa-envelope"></i>
                <h3>Lettre de motivation</h3>
                <p>Modèles gratuits et structures types pour convaincre.</p>
            </div>
        </div>
    </section>

    <!-- SECTION AUTHENTIFICATION MISE À JOUR AVEC ACTIONS PHP -->
    <section id="connexion" class="section">
        <div class="auth-container">
            <h2>Espace Authentification</h2>

            <div class="auth-tabs">
                <button class="tab-btn active" onclick="switchAuthSection('candidat')">Candidat</button>
                <button class="tab-btn" onclick="switchAuthSection('recruteur')">Entreprise</button>
            </div>

            <!-- CANDIDAT GROUP -->
            <div id="candidat-forms" class="auth-group active-group">
                <div class="sub-tabs">
                    <button id="candidat-login-tab" class="sub-tab-btn active" onclick="switchAuthForm('candidat', 'login')">Login</button>
                    <button id="candidat-register-tab" class="sub-tab-btn" onclick="switchAuthForm('candidat', 'register')">Register</button>
                </div>

                <!-- CANDIDAT LOGIN FORM -->
                <form id="form-candidat-login" class="auth-form active-form" action="login.php" method="POST">
                    <h3>Candidat - Login</h3>
                    <input type="hidden" name="account_type" value="candidate">
                    <div class="input-group"><label>Email</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <p class="info-text">Pas encore de compte ? <a href="#" onclick="switchAuthForm('candidat', 'register'); return false;">Inscrivez-vous ici</a>.</p>
                    <button type="submit" class="btn-submit">Se connecter</button>
                </form>

                <!-- CANDIDAT REGISTER FORM -->
                <form id="form-candidat-register" class="auth-form" action="register.php" method="POST">
                    <h3>Candidat - Register</h3>
                    <input type="hidden" name="account_type" value="candidate">
                    <div class="input-group"><label>Nom d'utilisateur</label><input type="text" name="username" required></div>
                    <div class="input-group"><label>Email personnel</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <div class="input-group"><label>Confirmer Mot de passe</label><input type="password" name="password_confirm" required></div>
                    <p class="info-text">💡 Créez votre profil pour postuler et suivre vos candidatures.</p>
                    <button type="submit" class="btn-submit">Créer le compte Candidat</button>
                </form>
            </div>

            <!-- RECRUTEUR GROUP -->
            <div id="recruteur-forms" class="auth-group">
                <div class="sub-tabs">
                    <button id="recruteur-login-tab" class="sub-tab-btn active" onclick="switchAuthForm('recruteur', 'login')">Login</button>
                    <button id="recruteur-register-tab" class="sub-tab-btn" onclick="switchAuthForm('recruteur', 'register')">Register</button>
                </div>

                <!-- RECRUTEUR LOGIN FORM -->
                <form id="form-recruteur-login" class="auth-form active-form" action="login.php" method="POST">
                    <h3>Recruteur - Login</h3>
                    <input type="hidden" name="account_type" value="company">
                    <div class="input-group"><label>Email professionnel</label><input type="email" name="email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <p class="info-text">Première connexion ? <a href="#" onclick="switchAuthForm('recruteur', 'register'); return false;">Créez votre compte entreprise</a>.</p>
                    <button type="submit" class="btn-submit">Se connecter (Entreprise)</button>
                </form>

                <!-- RECRUTEUR REGISTER FORM -->
                <form id="form-recruteur-register" class="auth-form" action="register.php" method="POST">
                    <h3>Recruteur - Register (Création du compte)</h3>
                    <input type="hidden" name="account_type" value="company">
                    <div class="input-group"><label>Nom de l'entreprise</label><input type="text" name="company_name" required></div>
                    <div class="input-group">
                        <label>Statut juridique</label>
                        <select name="legal_status">
                            <option value="SARL">SARL</option>
                            <option value="SAS">SAS</option>
                            <option value="Auto-entrepreneur">Auto-entrepreneur</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="input-group"><label>Numéro SIRET / TVA</label><input type="text" name="siret_number" required></div>
                    <div class="input-group"><label>Email professionnel</label><input type="email" name="professional_email" required></div>
                    <div class="input-group"><label>Mot de passe</label><input type="password" name="password" required></div>
                    <div class="input-group"><label>Confirmer Mot de passe</label><input type="password" name="password_confirm" required></div>
                    <p class="info-text">💡 Le compte est lié à une activité commerciale et sera vérifié.</p>
                    <button type="submit" class="btn-submit">Créer le compte Entreprise</button>
                </form>
            </div>

        </div>
    </section>

    <section id="creation-offre" class="section">
        <div class="auth-container" style="max-width: 800px;">
            <h2>Créer une Nouvelle Offre d'Emploi</h2>
            <p class="info-text">Remplissez les informations ci-dessous pour publier votre offre immédiatement sur StageBoard.</p>

            <form id="form-creer-offre">
                <div class="form-grid">
                    <div class="input-group full-width"><label>Intitulé du Poste *</label><input type="text" placeholder="Ex: Développeur Web Fullstack H/F" required></div>
                    
                    <div class="input-group"><label>Type de Contrat *</label>
                        <select required>
                            <option value="">Sélectionner</option>
                            <option value="cdi">CDI</option>
                            <option value="cdd">CDD</option>
                            <option value="alternance">Alternance</option>
                            <option value="stage">Stage</option>
                        </select>
                    </div>
                    
                    <div class="input-group"><label>Localisation *</label><input type="text" placeholder="Ex: Paris (75) ou Télétravail" required></div>

                    <div class="input-group"><label>Salaire Annuel Brut (Min)</label><input type="number" placeholder="Ex: 35000" min="0"></div>
                    <div class="input-group"><label>Salaire Annuel Brut (Max)</label><input type="number" placeholder="Ex: 45000" min="0"></div>
                    
                    <div class="input-group full-width">
                        <label>Description du Poste *</label>
                        <textarea rows="10" placeholder="Décrivez les missions, le profil recherché et l'environnement de travail..." required></textarea>
                    </div>

                    <div class="input-group full-width">
                        <label>Compétences Clés (Mots-clés pour la recherche)</label>
                        <input type="text" placeholder="Ex: PHP, Symfony, React, Marketing Digital">
                        <small class="help-text">Séparez les mots-clés par des virgules.</small>
                    </div>
                </div>

                <button type="submit" class="btn-submit" style="background: #27ae60;">Publier l'Offre <i class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
    </section>

    <div id="applyModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeApplyModal()">&times;</span>
            
            <header class="form-header">
                <h1>Démarrons ensemble</h1>
                <p>Remplissez ce formulaire et nous vous recontacterons rapidement.</p>
            </header>
            <div class="cookie-banner">
                Pour protéger notre formulaire de contact, veuillez accepter les cookies... <a href="#">Changer mon choix</a>
            </div>
            <form id="contactForm">
                <div class="form-grid">
                    <div class="input-group">
                        <label>Prénom *</label>
                        <div class="input-wrapper"><i class="fa-regular fa-user input-icon"></i><input type="text" placeholder="Jean"></div>
                    </div>
                    <div class="input-group">
                        <label>Nom *</label>
                        <div class="input-wrapper"><i class="fa-regular fa-user input-icon"></i><input type="text" placeholder="Dupont"></div>
                    </div>
                    <div class="input-group">
                        <label>Email *</label>
                        <div class="input-wrapper"><i class="fa-regular fa-envelope input-icon"></i><input type="email" placeholder="email@exemple.com"></div>
                    </div>
                    <div class="input-group">
                        <label>Téléphone *</label>
                        <div class="input-wrapper"><i class="fa-solid fa-phone input-icon"></i><input type="tel" placeholder="+33 6..."></div>
                    </div>
                    <div class="input-group">
                        <label>Service souhaité *</label>
                        <div class="input-wrapper"><i class="fa-solid fa-suitcase input-icon"></i>
                            <select><option>Candidature Offre</option><option>Information</option></select>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Société (Facultatif)</label>
                        <div class="input-wrapper"><i class="fa-solid fa-building input-icon"></i><input type="text" placeholder="Entreprise"></div>
                    </div>
                    <div class="input-group full-width">
                        <label><i class="fa-regular fa-paper-plane"></i> Votre message *</label>
                        <textarea id="message" rows="6" placeholder="Votre motivation..."></textarea>
                        <div class="char-count"><span id="currentCount">0</span>/1000 caractères</div>
                    </div>
                </div>
                <button type="submit" class="btn-submit-modal">Envoyer le message <i class="fa-regular fa-paper-plane"></i></button>
            </form>
            <footer class="form-footer">
                <p class="secure-text"><i class="fa-solid fa-shield-halved"></i> Vos données sont protégées</p>
            </footer>
        </div>
    </div>

    <script src="script.js"></script>

</body>
</html>