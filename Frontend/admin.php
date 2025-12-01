<?php
// Fichier : admin.php (Version Finale Validée)
require 'config.php';

// Sécurité MAXIMALE
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Accès refusé. Réservé aux administrateurs.");
}

// Fonction utilitaire
function get_value_nocase($array, $key) {
    if (!is_array($array)) {
        return 'Erreur';
    }

    foreach ($array as $k => $v) {
        if (strtolower($k) === strtolower($key)) {
            return $v;
        }
    }
   MESSAGE = 'ceci est un doublon' ;

fonction  exécuter () {
  préparer (MESSAGE); // Conforme - la chaîne littérale dupliquée est remplacée par une constante et peut être réutilisée en toute sécurité 
  exécuter (MESSAGE);
   libérer (MESSAGE);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #2c3e50; color: white; font-family: 'Segoe UI', sans-serif; }
        .admin-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stat-card { background: #34495e; padding: 25px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); }
        table { width: 100%; color: white; border-collapse: collapse; margin-top:15px; }
        th, td { padding: 15px; border-bottom: 1px solid #7f8c8d; text-align: left; }
        th { background-color: #2c3e50; font-weight: 600; }
        a { color: #f1c40f; text-decoration: none; font-weight: bold; transition:0.3s; }
        a:hover { color: #f39c12; text-decoration:underline; }
        
        .btn-ban { color: #e74c3c; margin-right: 10px; }
        .btn-ban:hover { color: #c0392b; }
        .btn-delete { color: #e67e22; }
        .btn-delete:hover { color: #d35400; }
        
        .badge-banned { background: #e74c3c; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; margin-left: 5px; }
        .header-title { display:flex; align-items:center; gap:15px; margin-bottom:30px; border-bottom:2px solid #f1c40f; padding-bottom:10px; }
        .stat-row { display:flex; gap:30px; font-size:1.1rem; }
        .stat-item i { color:#f1c40f; margin-right:10px; }
        h2 { border-bottom: 1px solid #7f8c8d; padding-bottom: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="header-title">
        <h1><i class="fa-solid fa-user-shield"></i> Panneau d'Administration</h1>
    </div>
    
    <a href="index.php" style="display:inline-block; margin-bottom:20px;">
        <i class="fa-solid fa-arrow-left"></i> Retour au site
    </a>

    <!-- 1. STATS -->
    <div class="stat-card">
        <h2 style="border:none;"><i class="fa-solid fa-chart-pie"></i> Vue d'ensemble</h2>
        <?php
        $nbCandidats = $pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
        $nbRecruteurs = $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn();
        $nbOffres = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
        ?>
        <div class="stat-row">
            <div class="stat-item"><i class="fa-solid fa-user-graduate"></i> Candidats : <strong><?= $nbCandidats ?></strong></div>
            <div class="stat-item"><i class="fa-solid fa-building"></i> Entreprises : <strong><?= $nbRecruteurs ?></strong></div>
            <div class="stat-item"><i class="fa-solid fa-file-contract"></i> Offres : <strong><?= $nbOffres ?></strong></div>
        </div>
    </div>

    <!-- 2. GESTION DES CANDIDATS -->
    <div class="stat-card">
        <h2><i class="fa-solid fa-users"></i> Gestion des Candidats</h2>
        <table>
            <tr><th>ID</th><th>Nom</th><th>Email</th><th>Actions</th></tr>
            <?php
            $users = $pdo->query("SELECT * FROM candidates ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($users as $u): 
                // Pour les candidats, la colonne s'appelle bien 'email'
                $email = $u['email'] ?? 'Non trouvé';
                if($email == 'admin@stageboard.com') continue;
                $isBanned = (isset($u['is_banned']) && $u['is_banned'] == 1);
            ?>
            <tr>
                <td>#<?= $u['id'] ?></td>
                <td>
                    <?= htmlspecialchars($u['username'] ?? 'Inconnu') ?>
                    <?php if($isBanned) echo '<span class="badge-banned">BANNI</span>'; ?>
                </td>
                <td><?= htmlspecialchars($email) ?></td>
                <td>
                    <?php if(!$isBanned): ?>
                        <a href="ban_user.php?id=<?= $u['id'] ?>&type=candidat" class="btn-ban" onclick="return confirm('Bannir ce compte ?')"><i class="fa-solid fa-ban"></i> Bannir</a>
                    <?php else: ?>
                        <span style="color:#7f8c8d; margin-right:10px;">🚫 Déjà banni</span>
                    <?php endif; ?>
                    <a href="delete_user.php?id=<?= $u['id'] ?>&type=candidat" class="btn-delete" onclick="return confirm('Supprimer définitivement ?')"><i class="fa-solid fa-trash"></i> Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- 3. GESTION DES ENTREPRISES -->
    <div class="stat-card">
        <h2><i class="fa-solid fa-building-user"></i> Gestion des Entreprises</h2>
        <table>
            <tr><th>ID</th><th>Entreprise</th><th>Email</th><th>SIRET</th><th>Actions</th></tr>
            <?php
            $companies = $pdo->query("SELECT * FROM companies ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($companies as $c): 
                $isBanned = (isset($c['is_banned']) && $c['is_banned'] == 1);
            ?>
            <tr>
                <td>#<?= $c['id'] ?></td>
                <td>
                    <?= htmlspecialchars($c['company_name'] ?? 'Inconnue') ?>
                    <?php if($isBanned) echo '<span class="badge-banned">BANNI</span>'; ?>
                </td>
                
                <!-- CORRECTION : Utilisation des vrais noms de colonnes (professional_email / siret_number) -->
                <td><?= htmlspecialchars($c['professional_email'] ?? 'Non trouvé') ?></td>
                <td><?= htmlspecialchars($c['siret_number'] ?? 'Non trouvé') ?></td>
                
                <td>
                    <?php if(!$isBanned): ?>
                        <a href="ban_user.php?id=<?= $c['id'] ?>&type=recruteur" class="btn-ban" onclick="return confirm('Bannir cette entreprise ?')"><i class="fa-solid fa-ban"></i> Bannir</a>
                    <?php else: ?>
                        <span style="color:#7f8c8d; margin-right:10px;">🚫 Déjà bannie</span>
                    <?php endif; ?>
                    <a href="delete_user.php?id=<?= $c['id'] ?>&type=recruteur" class="btn-delete" onclick="return confirm('Supprimer définitivement ?')"><i class="fa-solid fa-trash"></i> Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- 4. GESTION DES OFFRES -->
    <div class="stat-card">
        <h2><i class="fa-solid fa-briefcase"></i> Dernières Offres</h2>
        <table>
            <tr><th>ID</th><th>Titre</th><th>Entreprise</th><th>Action</th></tr>
            <?php
            $offres = $pdo->query("SELECT jobs.id, jobs.title, companies.company_name 
                                   FROM jobs JOIN companies ON jobs.company_id = companies.id 
                                   ORDER BY jobs.created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
            foreach($offres as $job): ?>
            <tr>
                <td>#<?= $job['id'] ?></td>
                <td><strong><?= htmlspecialchars($job['title']) ?></strong></td>
                <td><?= htmlspecialchars($job['company_name']) ?></td>
                <td>
                    <a href="delete_job.php?id=<?= $job['id'] ?>&admin=true" class="btn-delete" style="color:#e74c3c;" onclick="return confirm('Supprimer cette offre ?')"><i class="fa-solid fa-trash-can"></i> Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
