/* =========================================
   1. NAVIGATION & SECTIONS
   ========================================= */
function showSection(sectionId) {
    // Masquer toutes les sections
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active-section'));
    document.querySelectorAll('.nav-links a').forEach(link => link.classList.remove('active'));

    // Afficher la section demandée
    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.add('active-section');
        window.scrollTo(0, 0); // Remonter en haut de page
    }

    // Mettre à jour le lien actif dans le menu
    const activeLink = document.querySelector(`.nav-links a[onclick*="'${sectionId}'"]`);
    if (activeLink) activeLink.classList.add('active');

    // Fermer le menu burger sur mobile si ouvert
    const navLinks = document.querySelector('.nav-links');
    if (navLinks.classList.contains('nav-active')) {
        navLinks.classList.remove('nav-active');
    }
}

/* =========================================
   2. MENU BURGER (MOBILE)
   ========================================= */
function toggleBurgerMenu() {
    const nav = document.querySelector('.nav-links');
    nav.classList.toggle('nav-active');
}

/* =========================================
   3. AUTHENTIFICATION (ONGLETS)
   ========================================= */
function switchAuthSection(role, type) {
    // Gestion des boutons principaux (Candidat / Entreprise)
    document.querySelectorAll('.auth-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    const mainBtn = document.querySelector(`.tab-btn[onclick*="'${role}'"]`);
    if (mainBtn) mainBtn.classList.add('active');

    // Afficher le bon groupe de formulaires
    document.querySelectorAll('.auth-group').forEach(g => g.classList.remove('active-group'));
    document.getElementById(role + '-forms').classList.add('active-group');

    // Réinitialiser vers le login par défaut
    switchAuthForm(role, type);
}

function switchAuthForm(role, type) {
    const group = document.getElementById(role + '-forms');
    
    // Gestion des sous-boutons (Login / Register)
    group.querySelectorAll('.sub-tab-btn').forEach(b => b.classList.remove('active'));
    const subBtn = group.querySelector(`.sub-tab-btn[onclick*="'${type}'"]`);
    if (subBtn) subBtn.classList.add('active');

    // Afficher le bon formulaire
    group.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active-form'));
    document.getElementById(`form-${role}-${type}`).classList.add('active-form');
}

/* =========================================
   4. GESTION DES OFFRES (DÉTAILS)
   ========================================= */
function showOfferDetails(offerId) {
    // 1. Gestion visuelle de la liste (Bordure bleue active)
    document.querySelectorAll('.offer-card').forEach(c => c.classList.remove('active'));
    const card = document.querySelector(`.offer-card[data-offer-id="${offerId}"]`);
    if (card) card.classList.add('active');

    // 2. Récupération du contenu
    const hiddenContentDiv = document.getElementById('details-' + offerId);
    const detailsPanel = document.getElementById('offer-details-content');
    const panelContainer = document.querySelector('.offer-details-panel');

    if (hiddenContentDiv && detailsPanel) {
        // Copier le contenu
        detailsPanel.innerHTML = hiddenContentDiv.innerHTML;

        // 3. GESTION MOBILE (Afficher le panneau en plein écran)
        if (window.innerWidth <= 900) {
            panelContainer.classList.add('mobile-visible');
            
            // Ajouter un bouton "Retour" dynamiquement sur mobile
            if (!detailsPanel.querySelector('.btn-back-mobile')) {
                const backBtn = document.createElement('button');
                backBtn.className = 'btn-back-mobile';
                backBtn.innerHTML = '<i class="fa-solid fa-arrow-left"></i> Retour aux offres';
                backBtn.onclick = closeOfferDetailsMobile;
                
                // Style rapide pour ce bouton injecté
                backBtn.style.cssText = "background:none; border:none; color:#666; font-weight:600; cursor:pointer; margin-bottom:15px; display:flex; align-items:center; gap:5px; font-size:0.9rem;";
                
                detailsPanel.insertBefore(backBtn, detailsPanel.firstChild);
            }
        }
    }
}

// Fonction pour fermer le panneau sur mobile
function closeOfferDetailsMobile() {
    const panelContainer = document.querySelector('.offer-details-panel');
    panelContainer.classList.remove('mobile-visible');
}


/* =========================================
   5. INITIALISATION
   ========================================= */
document.addEventListener('DOMContentLoaded', () => {
    // Si on est sur un grand écran, on ouvre la première offre par défaut
    if (window.innerWidth > 900) {
        const firstCard = document.querySelector('.offer-card');
        if (firstCard) {
            const id = firstCard.getAttribute('data-offer-id');
            showOfferDetails(id);
        }
    }
});