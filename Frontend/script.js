// 1. Navigation entre les sections
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active-section'));
    document.querySelectorAll('.nav-links a').forEach(link => link.classList.remove('active'));

    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active-section');

    // Activer le lien dans le menu
    const activeLink = document.querySelector(`.nav-links a[onclick*="'${sectionId}'"]`);
    if (activeLink) activeLink.classList.add('active');
}

// 2. Menu Burger (Mobile)
function toggleBurgerMenu() {
    document.querySelector('.nav-links').classList.toggle('nav-active');
}

// 3. Authentification (Onglets)
function switchAuthSection(role, type) {
    // Onglet Candidat/Recruteur
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    const btn = document.querySelector(`.tab-btn[onclick*="'${role}'"]`);
    if (btn) btn.classList.add('active');

    // Afficher le groupe
    document.querySelectorAll('.auth-group').forEach(g => g.classList.remove('active-group'));
    document.getElementById(role + '-forms').classList.add('active-group');

    switchAuthForm(role, type);
}

function switchAuthForm(role, type) {
    const group = document.getElementById(role + '-forms');
    group.querySelectorAll('.sub-tab-btn').forEach(b => b.classList.remove('active'));
    
    const subBtn = group.querySelector(`.sub-tab-btn[onclick*="'${type}'"]`);
    if (subBtn) subBtn.classList.add('active');

    group.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active-form'));
    document.getElementById(`form-${role}-${type}`).classList.add('active-form');
}

// ---------------------------------------------------------
// 4. AFFICHER LES DÉTAILS DE L'OFFRE (C'est ici la correction !)
// ---------------------------------------------------------
function showOfferDetails(offerId) {
    // Désélectionner tout
    document.querySelectorAll('.offer-card').forEach(c => c.classList.remove('active'));

    // Sélectionner la carte cliquée
    const card = document.querySelector(`.offer-card[data-offer-id="${offerId}"]`);
    if (card) card.classList.add('active');

    // Récupérer le contenu caché généré par PHP
    const hiddenContentDiv = document.getElementById('details-' + offerId);
    const detailsPanel = document.getElementById('offer-details-content');

    if (hiddenContentDiv && detailsPanel) {
        // On copie le HTML caché vers le panneau visible
        detailsPanel.innerHTML = hiddenContentDiv.innerHTML;
        
        // Sur mobile, on affiche le panneau
        if (window.innerWidth <= 768) {
            document.querySelector('.offer-details-panel').classList.add('active');
        }
    } else {
        console.error("Impossible de trouver les détails pour l'offre : " + offerId);
    }
}

// 5. Filtre de recherche
function filterOffers() {
    const term = document.querySelector('.search-input').value.toLowerCase();
    document.querySelectorAll('.offer-card').forEach(card => {
        const keys = card.getAttribute('data-keywords').toLowerCase();
        card.style.display = keys.includes(term) ? 'block' : 'none';
    });
}

// 6. Initialisation (Ouvrir la 1ère offre au chargement)
document.addEventListener('DOMContentLoaded', () => {
<<<<<<< HEAD
    const firstCard = document.querySelector('.offer-card');
    if (firstCard) {
        const id = firstCard.getAttribute('data-offer-id');
        showOfferDetails(id);
=======
    // Si on est sur un grand écran, on ouvre la première offre par défaut
    if (window.innerWidth > 900) {
        const firstCard = document.querySelector('.offer-card');
        if (firstCard) {
            const id = firstCard.dataset('data-offer-id');
            showOfferDetails(id);
        }
>>>>>>> faa2123b984eedfea4faee50de0378251508ee81
    }
});
