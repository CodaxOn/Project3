<?php
// Fichier : admin.php (Version Design Pro)
require 'config.php';

// 1. SÉCURITÉ : Vérifier si l'utilisateur est ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

<<<<<<< HEAD
$msg = "";

// 2. ACTIONS D'ADMINISTRATION

// Supprimer un utilisateur (Candidat ou Recruteur)
if (isset($_GET['delete_user']) && isset($_GET['type'])) {
    $id = intval($_GET['delete_user']);
    $type = $_GET['type'];
    
    if ($type === 'candidate') {
        $pdo->prepare("DELETE FROM candidates WHERE id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM applications WHERE candidate_id = ?")->execute([$id]);
    } elseif ($type === 'company') {
        $pdo->prepare("DELETE FROM companies WHERE id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM jobs WHERE company_id = ?")->execute([$id]);
    }
    $msg = "Utilisateur supprimé avec succès.";
=======
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
>>>>>>> faa2123b984eedfea4faee50de0378251508ee81
}

// Bannir / Débannir un utilisateur
if (isset($_GET['ban_user']) && isset($_GET['type'])) {
    $id = intval($_GET['ban_user']);
    $type = $_GET['type'];
    $action = (isset($_GET['action']) && $_GET['action'] === 'unban') ? 0 : 1; 

    $table = ($type === 'candidate') ? 'candidates' : 'companies';
    $sql = "UPDATE $table SET is_banned = ? WHERE id = ?";
    $pdo->prepare($sql)->execute([$action, $id]);
    
    $msg = ($action === 1) ? "Utilisateur banni." : "Utilisateur réactivé.";
}

// Supprimer une offre
if (isset($_GET['delete_job'])) {
    $id = intval($_GET['delete_job']);
    $pdo->prepare("DELETE FROM jobs WHERE id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM applications WHERE job_id = ?")->execute([$id]);
    $msg = "Offre supprimée.";
}

// 3. RÉCUPÉRATION DES DONNÉES
$candidats = $pdo->query("SELECT * FROM candidates ORDER BY created_at DESC")->fetchAll();
$companies = $pdo->query("SELECT * FROM companies ORDER BY created_at DESC")->fetchAll();
$jobs      = $pdo->query("SELECT jobs.*, companies.company_name FROM jobs JOIN companies ON jobs.company_id = companies.id ORDER BY jobs.created_at DESC")->fetchAll();

// Stats rapides
$nbCandidats = count($candidats);
$nbCompanies = count($companies);
$nbJobs      = count($jobs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - StageBoard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0c57e5;
            --danger: #e74c3c;
            --success: #27ae60;
            --dark: #1a1a1a;
            --light: #f5f6f9;
            --border: #e5e7eb;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: var(--light); color: var(--dark); }
        
        /* Header Admin */
        .admin-header {
            background: white;
            padding: 15px 40px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 100;
        }
        .logo { font-size: 1.2rem; font-weight: 800; color: var(--dark); text-decoration: none; }
        .logo span { color: var(--primary); }
        .btn-logout { background: #fff0f0; color: var(--danger); padding: 8px 15px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; font-weight: 600; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        /* Cartes Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px; border: 1px solid var(--border); }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .icon-blue { background: #eef2ff; color: var(--primary); }
        .icon-green { background: #ecfdf5; color: var(--success); }
        .icon-purple { background: #f3e8ff; color: #9333ea; }
        .stat-info h3 { font-size: 1.8rem; font-weight: 800; margin-bottom: 5px; }
        .stat-info p { color: #666; font-size: 0.9rem; font-weight: 500; }

        /* Sections */
        .section-title { margin-bottom: 20px; font-size: 1.2rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 40px; border: 1px solid var(--border); }
        
        /* Tableaux */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9fafb; padding: 15px 20px; text-align: left; font-size: 0.85rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
        td { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; font-size: 0.95rem; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f8f9fa; }

        /* Badges & Actions */
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge-banned { background: #fee2e2; color: var(--danger); }
        .badge-active { background: #dcfce7; color: var(--success); }
        
        .actions { display: flex; gap: 8px; }
        .btn-action { padding: 6px 10px; border-radius: 6px; font-size: 0.85rem; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .btn-ban { background: #fff1f2; color: var(--danger); border-color: #fecdd3; }
        .btn-ban:hover { background: var(--danger); color: white; }
        .btn-unban { background: #ecfdf5; color: var(--success); border-color: #a7f3d0; }
        .btn-delete { background: white; color: #666; border-color: #e5e7eb; }
        .btn-delete:hover { border-color: var(--danger); color: var(--danger); }

        /* Alertes */
        .alert { padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-weight: 500; }
        .alert-success { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>

<!-- Header -->
<nav class="admin-header">
    <a href="index.php" class="logo">Stage<span>Board</span> <span style="font-weight:400; font-size:0.9rem; color:#666; margin-left:10px;">Administration</span></a>
    <div style="display:flex; gap:15px; align-items:center;">
        <a href="index.php" style="color:#666; text-decoration:none; font-size:0.9rem;"><i class="fa-solid fa-arrow-left"></i> Retour au site</a>
        <a href="auth.php?logout=true" class="btn-logout"><i class="fa-solid fa-power-off"></i> Déconnexion</a>
    </div>
</nav>

<div class="container">

    <?php if($msg): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-check-circle"></i> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- STATISTIQUES -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-blue"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="stat-info">
                <h3><?= $nbCandidats ?></h3>
                <p>Candidats inscrits</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-purple"><i class="fa-solid fa-building"></i></div>
            <div class="stat-info">
                <h3><?= $nbCompanies ?></h3>
                <p>Entreprises partenaires</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-briefcase"></i></div>
            <div class="stat-info">
                <h3><?= $nbJobs ?></h3>
                <p>Offres actives</p>
            </div>
        </div>
    </div>

    <!-- GESTION CANDIDATS -->
    <h2 class="section-title"><i class="fa-solid fa-users"></i> Gestion des Candidats</h2>
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($candidats as $u): 
                        $isBanned = isset($u['is_banned']) && $u['is_banned'] == 1;
                    ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?php if($isBanned): ?>
                                <span class="badge badge-banned">Banni</span>
                            <?php else: ?>
                                <span class="badge badge-active">Actif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <div class="actions">
                                <?php if($isBanned): ?>
                                    <a href="admin.php?ban_user=<?= $u['id'] ?>&type=candidate&action=unban" class="btn-action btn-unban" title="Débannir"><i class="fa-solid fa-unlock"></i></a>
                                <?php else: ?>
                                    <a href="admin.php?ban_user=<?= $u['id'] ?>&type=candidate&action=ban" class="btn-action btn-ban" title="Bannir"><i class="fa-solid fa-ban"></i></a>
                                <?php endif; ?>
                                <a href="admin.php?delete_user=<?= $u['id'] ?>&type=candidate" onclick="return confirm('Supprimer ce candidat ?')" class="btn-action btn-delete" title="Supprimer"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- GESTION ENTREPRISES -->
    <h2 class="section-title"><i class="fa-solid fa-building-user"></i> Gestion des Entreprises</h2>
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Entreprise</th>
                        <th>Email Pro</th>
                        <th>SIRET</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($companies as $c): 
                         $isBanned = isset($c['is_banned']) && $c['is_banned'] == 1;
                    ?>
                    <tr>
                        <td>#<?= $c['id'] ?></td>
                        <td><strong><?= htmlspecialchars($c['company_name']) ?></strong></td>
                        <td><?= htmlspecialchars($c['professional_email']) ?></td>
                        <td><?= htmlspecialchars($c['siret_number']) ?></td>
                        <td>
                            <?php if($isBanned): ?>
                                <span class="badge badge-banned">Banni</span>
                            <?php else: ?>
                                <span class="badge badge-active">Actif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                                <?php if($isBanned): ?>
                                    <a href="admin.php?ban_user=<?= $c['id'] ?>&type=company&action=unban" class="btn-action btn-unban" title="Débannir"><i class="fa-solid fa-unlock"></i></a>
                                <?php else: ?>
                                    <a href="admin.php?ban_user=<?= $c['id'] ?>&type=company&action=ban" class="btn-action btn-ban" title="Bannir"><i class="fa-solid fa-ban"></i></a>
                                <?php endif; ?>
                                <a href="admin.php?delete_user=<?= $c['id'] ?>&type=company" onclick="return confirm('Supprimer cette entreprise ?')" class="btn-action btn-delete" title="Supprimer"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- GESTION OFFRES -->
    <h2 class="section-title"><i class="fa-solid fa-file-lines"></i> Dernières Offres Publiées</h2>
    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Poste</th>
                        <th>Entreprise</th>
                        <th>Lieu</th>
                        <th>Type</th>
                        <th>Publié le</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($jobs as $j): ?>
                    <tr>
                        <td>#<?= $j['id'] ?></td>
                        <td><strong><?= htmlspecialchars($j['title']) ?></strong></td>
                        <td><?= htmlspecialchars($j['company_name']) ?></td>
                        <td><?= htmlspecialchars($j['location']) ?></td>
                        <td><span style="background:#f3f4f6; padding:4px 8px; border-radius:4px; font-size:0.8rem;"><?= htmlspecialchars($j['contract_type']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($j['created_at'])) ?></td>
                        <td>
                            <a href="admin.php?delete_job=<?= $j['id'] ?>" onclick="return confirm('Supprimer cette offre ?')" class="btn-action btn-delete" title="Supprimer l'offre"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
