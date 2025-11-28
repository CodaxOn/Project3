// 1. GESTION DE LA NAVIGATION
function showSection(sectionId) {
    // Masquer toutes les sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.remove('active-section');
    });

    // Enlever la classe active des liens nav
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        link.classList.remove('active');
    });

    // Afficher la section demandée
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active-section');
    }

    // Mettre le lien en surbrillance (optionnel, simple)
    // Ici on suppose que le clic vient du menu
}

// 3. GESTION DU MODAL DE CANDIDATURE
const modal = document.getElementById('applyModal');
const messageInput = document.getElementById('message');
const currentCountSpan = document.getElementById('currentCount');

// Ouvrir le modal
function openApplyModal() {
    modal.style.display = "block";
}

// Fermer le modal
function closeApplyModal() {
    modal.style.display = "none";
}

// Fermer si on clique en dehors du contenu
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Compteur de caractères pour le message
if(messageInput) {
    messageInput.addEventListener('input', function() {
        currentCountSpan.textContent = messageInput.value.length;
    });
}

// Simulation d'envoi du formulaire modal
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert("Votre candidature a bien été envoyée !");
    closeApplyModal();
});

// Données des offres pour le panneau de détails
const offersData = {
    'offer1': `
        <header class="detail-section">
            <h3>Ingénieur Développeur Backend H/F</h3>
            <p class="company">Boli Care • France • Télétravail</p>
            <div class="job-info" style="margin-top: 15px;">
                <span class="salary"><i class="fa-solid fa-euro-sign"></i> De 40 000 € à 55 000 € par an</span>
                <span class="contract-type"><i class="fa-solid fa-briefcase"></i> CDI, Temps plein</span>
            </div>
            <button class="btn-detail-postuler" onclick="openApplyModal()">Postuler Maintenant</button>
        </header>

        <div class="detail-section">
            <h4>Détails de l'emploi</h4>
            <p><strong>Type de poste :</strong> CDI, Temps plein</p>
            <p><strong>Salaire :</strong> 40 000 € à 55 000 € par an (selon profil et expérience)</p>
        </div>

        <div class="detail-section">
            <h4>Description du poste :</h4>
            <p>Ingénieur Backend PHP Symfony / MongoDB / RabbitMQ – Logiciels Dispositifs Médicaux (H/F). Au sein de Boli Care, éditeur de logiciels dispositifs médicaux, nous renforçons notre équipe technique pour concevoir des solutions backend robustes, scalables et sécurisées.</p>

            <h4>Missions principales :</h4>
            <ul>
                <li>Concevoir, développer et maintenir des APIs et services backend en PHP avec le framework Symfony.</li>
                <li>Modéliser et optimiser les bases de données MongoDB.</li>
                <li>Intégrer et administrer des flux asynchrones via RabbitMQ.</li>
                <li>Automatiser les tests (unitaires, d’intégration) et documenter les composants.</li>
                <li>Garantir la sécurité et la conformité des données (ISO 13485, RGPD).</li>
            </ul>

            <h4>Profil recherché :</h4>
            <p>Ingénieur Bac+5. Expérience confirmée (5 ans minimum) en développement backend avec PHP 8+ et Symfony 7. Maîtrise de MongoDB et expérience significative avec RabbitMQ.</p>
        </div>
        
        <button class="btn-detail-postuler" onclick="openApplyModal()">Postuler Maintenant</button>
    `,
    'offer2': `
        <header class="detail-section">
            <h3>Développeur Web Fullstack</h3>
            <p class="company">Tech Solutions SAS • Paris (75)</p>
            <div class="job-info" style="margin-top: 15px;">
                <span class="salary"><i class="fa-solid fa-euro-sign"></i> Salaire selon profil</span>
                <span class="contract-type"><i class="fa-solid fa-briefcase"></i> Alternance</span>
            </div>
            <button class="btn-detail-postuler" onclick="openApplyModal()">Postuler Maintenant</button>
        </header>

        <div class="detail-section">
            <h4>Description du poste :</h4>
            <p>Nous recherchons un(e) alternant(e) Développeur Web Fullstack pour rejoindre notre équipe agile. Vous travaillerez sur l'ensemble de notre stack technique : React pour le front-end et Node.js pour le back-end.</p>

            <h4>Missions :</h4>
            <ul>
                <li>Développement de nouvelles fonctionnalités sur notre plateforme.</li>
                <li>Maintenance et amélioration de l'API REST.</li>
                <li>Participation aux revues de code et aux daily scrums.</li>
            </ul>

            <h4>Compétences souhaitées :</h4>
            <p>Expérience avec JavaScript, React, Node.js, Express, et MongoDB ou PostgreSQL.</p>
        </div>
    `,
    'offer3': `
        <header class="detail-section">
            <h3>Data Analyst Junior</h3>
            <p class="company">BigData Corp • Lyon (69)</p>
            <div class="job-info" style="margin-top: 15px;">
                <span class="salary"><i class="fa-solid fa-euro-sign"></i> 1200 € / mois (Stage)</span>
                <span class="contract-type"><i class="fa-solid fa-briefcase"></i> Stage (6 mois)</span>
            </div>
            <button class="btn-detail-postuler" onclick="openApplyModal()">Postuler Maintenant</button>
        </header>

        <div class="detail-section">
            <h4>Description :</h4>
            <p>Stage de 6 mois pour un profil Junior en Data Analyse. Vous serez responsable de la transformation de données brutes en informations exploitables pour les équipes marketing et produit.</p>
            <h4>Technos :</h4>
            <p>SQL, Python (Pandas/NumPy), Tableau/PowerBI.</p>
        </div>
    `
};

/**
 * Affiche les détails d'une offre spécifique dans le panneau de droite.
 * @param {string} offerId - L'ID de l'offre à afficher (ex: 'offer1').
 */
function showOfferDetails(offerId) {
    const detailPanel = document.getElementById('offer-details-content');
    const allCards = document.querySelectorAll('.offer-card');
    
    // 1. Réinitialiser la classe 'active' sur toutes les cartes
    allCards.forEach(card => card.classList.remove('active'));

    // 2. Ajouter la classe 'active' à la carte cliquée
    const activeCard = document.querySelector(`.offer-card[data-offer-id="${offerId}"]`);
    if (activeCard) {
        activeCard.classList.add('active');
    }

    // 3. Charger le contenu des détails
    const content = offersData[offerId];
    if (content) {
        detailPanel.innerHTML = content;
        detailPanel.scrollTop = 0; // Remonter en haut des détails
    } else {
        detailPanel.innerHTML = `
            <div class="empty-state">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p>Détails non trouvés pour cette offre.</p>
            </div>
        `;
    }
}

// Assurez-vous que cette fonction est toujours présente dans votre script.js
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active-section');
    });
    document.getElementById(sectionId).classList.add('active-section');
    
    // Gérer l'état actif dans la barre de navigation (si vous voulez)
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.classList.remove('active');
    });
    // Trouver et activer le lien de navigation correspondant
    document.querySelector(`.nav-links a[onclick*="showSection('${sectionId}')"]`).classList.add('active');
}

// Et les fonctions pour la modale si elles n'existent pas encore
function openApplyModal() {
    document.getElementById('applyModal').style.display = 'block';
}

function closeApplyModal() {
    document.getElementById('applyModal').style.display = 'none';
}

// Fonction pour gérer les onglets de connexion
function switchAuth(formId) {
    document.querySelectorAll('.auth-form').forEach(form => form.classList.remove('active-form'));
    document.getElementById(`form-${formId}`).classList.add('active-form');

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.tab-btn[onclick*="switchAuth('${formId}')"]`).classList.add('active');
}

// Gérer l'affichage des détails par défaut au chargement (optionnel)
document.addEventListener('DOMContentLoaded', () => {
    // Affiche les détails de la première offre au chargement de la page si la section 'offres' est visible
    if (document.getElementById('offres').classList.contains('active-section')) {
           showOfferDetails('offer1');
    }
});

/**
 * Filtre les cartes d'offres en fonction de la saisie dans la barre de recherche.
 */
function filterOffers() {
    const searchInput = document.querySelector('.search-bar-container .search-input');
    const filterTerm = searchInput.value.toLowerCase().trim();
    const offerCards = document.querySelectorAll('.offer-card');

    offerCards.forEach(card => {
        const keywords = card.getAttribute('data-keywords').toLowerCase();
        
        if (keywords.includes(filterTerm)) {
            card.style.display = 'block'; // Affiche la carte
        } else {
            card.style.display = 'none'; // Cache la carte
        }
    });
}

// Assurez-vous d'appeler showOfferDetails('offer1') au chargement si la section 'offres' est active
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('offres').classList.contains('active-section')) {
           showOfferDetails('offer1');
    }
});

/**
 * Bascule entre les grands groupes d'authentification (Candidat vs. Recruteur).
 * @param {string} sectionId - 'candidat' ou 'recruteur'.
 * @param {string} defaultForm - Le formulaire par défaut à afficher ('login' ou 'register').
 */
function switchAuthSection(sectionId, defaultForm) {
    // 1. Gérer les onglets principaux (Candidat/Entreprise)
    document.querySelectorAll('.auth-tabs .tab-btn').forEach(btn => btn.classList.remove('active'));
    // Sélectionne le bon bouton en utilisant l'ID de la section dans la fonction onclick
    document.querySelector(`.auth-tabs .tab-btn[onclick*="switchAuthSection('${sectionId}')"]`).classList.add('active');

    // 2. Gérer les groupes de formulaires (Candidat-forms / Recruteur-forms)
    document.querySelectorAll('.auth-group').forEach(group => group.classList.remove('active-group'));
    document.getElementById(`${sectionId}-forms`).classList.add('active-group');

    // 3. Passer au formulaire par défaut (Login ou Register)
    switchAuthForm(sectionId, defaultForm);
}

/**
 * Gère l'affichage des sections principales de la page.
 * @param {string} sectionId - L'ID de la section à afficher (ex: 'accueil', 'offres', 'connexion').
 */
function showSection(sectionId) {
    // Masquer toutes les sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active-section');
    });

    // Afficher la section demandée
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.classList.add('active-section');
    }
    
    // Gérer l'état actif dans la NavBar
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('onclick') && link.getAttribute('onclick').includes(sectionId)) {
             link.classList.add('active');
        }
    });

    // Cas spécial : Initialiser les formulaires de connexion/inscription lors de l'affichage de cette section
    if (sectionId === 'connexion') {
        // Initialiser par défaut sur Candidat > Connexion
        switchAuthSection('candidat', 'login');
    }
}


/**
 * Bascule entre les grands groupes d'authentification (Candidat vs. Recruteur).
 * @param {string} sectionId - 'candidat' ou 'recruteur'.
 * @param {string} defaultForm - Le formulaire par défaut à afficher ('login' ou 'register').
 */
function switchAuthSection(sectionId, defaultForm) {
    // 1. Gérer les onglets principaux (Candidat/Entreprise)
    document.querySelectorAll('.auth-tabs .tab-btn').forEach(btn => btn.classList.remove('active'));
    // Utilisation d'un attribut data ou d'un sélecteur plus fiable pour le bouton
    const targetButton = document.querySelector(`.auth-tabs .tab-btn[onclick*="switchAuthSection('${sectionId}')"]`);
    if (targetButton) {
        targetButton.classList.add('active');
    }

    // 2. Gérer les groupes de formulaires (Candidat-forms / Recruteur-forms)
    document.querySelectorAll('.auth-group').forEach(group => group.classList.remove('active-group'));
    const targetGroup = document.getElementById(`${sectionId}-forms`);
    if (targetGroup) {
        targetGroup.classList.add('active-group');
    }

    // 3. Passer au formulaire par défaut (Login ou Register)
    switchAuthForm(sectionId, defaultForm);
}

/**
 * Bascule entre les formulaires Connexion et Inscription dans un groupe donné.
 * @param {string} sectionId - 'candidat' ou 'recruteur'.
 * @param {string} formType - 'login' ou 'register'.
 */
function switchAuthForm(sectionId, formType) {
    const formId = `form-${sectionId}-${formType}`;

    // 1. Gérer l'affichage des formulaires
    document.querySelectorAll(`#${sectionId}-forms .auth-form`).forEach(form => form.classList.remove('active-form'));
    const targetForm = document.getElementById(formId);
    if (targetForm) {
        targetForm.classList.add('active-form');
    }

    // 2. Gérer les sous-onglets
    document.querySelectorAll(`#${sectionId}-forms .sub-tab-btn`).forEach(btn => btn.classList.remove('active'));
    const targetSubButton = document.querySelector(`#${sectionId}-forms .sub-tab-btn[onclick*="switchAuthForm('${sectionId}', '${formType}')"]`);
    if (targetSubButton) {
        targetSubButton.classList.add('active');
    }
}

// --- OFFRES : Logique de la section des offres ---

/**
 * Fonction de simulation pour afficher les détails d'une offre
 * (à remplacer par une requête AJAX/fetch en production).
 * @param {string} offerId - L'ID de l'offre.
 */
function showOfferDetails(offerId) {
    // 1. Désactiver la carte précédemment active et activer la nouvelle
    document.querySelectorAll('.offer-card').forEach(card => card.classList.remove('active'));
    const selected = document.querySelector(`.offer-card[data-offer-id="${offerId}"]`);
    if (selected) {
        selected.classList.add('active');
    }

    // 2. Mettre à jour le contenu du panneau de détails
    const detailsPanel = document.getElementById('offer-details-content');
    
    // Contenu simplifié
    const detailsContent = {
        'offer1': `
            <div class="detail-section">
                <h3>Ingénieur Développeur Backend H/F</h3>
                <p class="company">Boli Care • CDI, Temps plein</p>
                <div class="job-info" style="margin-bottom: 20px;">
                    <span class="location"><i class="fa-solid fa-location-dot"></i> France • Télétravail</span>
                    <span class="salary"><i class="fa-solid fa-euro-sign"></i> 40 000 € - 55 000 € / an</span>
                </div>
                <button class="btn-detail-postuler">Postuler Facile (Connexion requise)</button>
                
                <h4>Description du Poste</h4>
                <p>Au sein d'une équipe agile, vous êtes responsable de la conception et du développement de notre plateforme SaaS dans un environnement PHP/Symfony.</p>
                
                <h4>Vos Missions</h4>
                <ul>
                    <li>Développer de nouvelles fonctionnalités robustes.</li>
                    <li>Maintenir et optimiser les performances des APIs existantes.</li>
                    <li>Participer à la revue de code et aux choix techniques.</li>
                </ul>

                <h4>Profil Recherché</h4>
                <ul>
                    <li>Minimum 3 ans d'expérience en développement Backend.</li>
                    <li>Maîtrise de PHP 8 et du framework Symfony.</li>
                    <li>Bonnes connaissances des bases de données SQL (PostgreSQL).</li>
                </ul>
            </div>
        `,
        'offer2': `
            <div class="detail-section">
                <h3>Développeur Web Fullstack</h3>
                <p class="company">Tech Solutions SAS • Alternance</p>
                <button class="btn-detail-postuler">Postuler (Ouverture de formulaire)</button>
                <h4>Description</h4>
                <p>Poste en alternance pour le développement d'interfaces React et de services Node.js. Idéal pour un étudiant en Master 1/2.</p>
            </div>
        `,
        'offer3': `
            <div class="detail-section">
                <h3>Data Analyst Junior</h3>
                <p class="company">BigData Corp • Stage (6 mois)</p>
                <button class="btn-detail-postuler">Postuler (Ouverture de formulaire)</button>
                <h4>Description</h4>
                <p>Stage de 6 mois axé sur l'analyse de données de performance et la création de dashboards BI (Python, SQL).</p>
            </div>
        `
    };
    
    // Afficher le contenu correspondant ou un message d'erreur
    detailsPanel.innerHTML = detailsContent[offerId] || `<p style="text-align:center; color:red;">Détails de l'offre non trouvés.</p>`;
}

/**
 * Filtre les cartes d'offres en fonction de la saisie dans la barre de recherche.
 * (Fonctionnalité ajoutée précédemment)
 */
function filterOffers() {
    const searchInput = document.querySelector('.search-bar-container .search-input');
    const filterTerm = searchInput.value.toLowerCase().trim();
    const offerCards = document.querySelectorAll('.offer-card');

    offerCards.forEach(card => {
        const keywords = card.getAttribute('data-keywords').toLowerCase();
        
        if (keywords.includes(filterTerm)) {
            card.style.display = 'block'; // Affiche la carte
        } else {
            card.style.display = 'none'; // Cache la carte
        }
    });
}