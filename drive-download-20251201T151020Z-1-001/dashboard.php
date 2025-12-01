<?php
// Fichier : dashboard.php (Version Finale avec bouton Paramètres)
require 'config.php';

// Sécurité
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$role = $_SESSION['role'];
$nom = $_SESSION['nom'];
$user_id = $_SESSION['user_id'];

// Récupération des messages de succès
$msg = "";
if(isset($_GET['msg'])) {
    if($_GET['msg'] == 'logo_ok') $msg = "✅ Logo mis à jour avec succès !";
    if($_GET['msg'] == 'cv_ok') $msg = "✅ CV envoyé avec succès !";
    if($_GET['msg'] == 'cv_deleted') $msg = "🗑️ CV supprimé avec succès.";
    if($_GET['msg'] == 'deleted') $msg = "🗑️ Offre supprimée.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f4f4; padding-top: 80px; }
        .dashboard-container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .card { background: white; padding: 25px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .header-dash { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-logout { background: #e74c3c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 14px; display:inline-flex; align-items:center; gap:5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; color: #555; }
        .status-badge { padding: 6px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; display: inline-block; }
        .btn-delete { color: #e74c3c; text-decoration: none; font-weight: bold; }
        .btn-delete:hover { text-decoration: underline; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="dashboard-container">
    
    <?php if($msg): ?>
        <div class="alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="header-dash">
        <div>
            <h1>Tableau de Bord</h1>
            <p>Bienvenue, <strong><?= htmlspecialchars($nom) ?></strong> (<?= ucfirst($role) ?>)</p>
        </div>
        
        <div style="display:flex; gap:10px;">
            <!-- BOUTON PARAMÈTRES (NOUVEAU) -->
            <a href="settings.php" class="btn-logout" style="background:#3498db;">
                <i class="fa-solid fa-gear"></i> Paramètres
            </a>

            <a href="index.php" class="btn-logout" style="background:#333;">
                <i class="fa-solid fa-house"></i> Accueil
            </a>
            
            <a href="auth.php?logout=true" class="btn-logout">
                <i class="fa-solid fa-power-off"></i> Déconnexion
            </a>
        </div>
    </div>

    <!-- ============================
         VUE RECRUTEUR
    ============================= -->
    <?php if ($role == 'recruteur'): ?>
        
        <!-- 0. UPLOAD LOGO -->
        <div class="card" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <h3><i class="fa-solid fa-image"></i> Mon Logo Entreprise</h3>
                <p style="font-size:0.9rem; color:#666;">Mettez à jour votre logo pour qu'il apparaisse sur vos offres.</p>
            </div>
            <form action="upload.php" method="POST" enctype="multipart/form-data" style="display:flex; gap:10px; align-items:center;">
                <input type="hidden" name="upload_type" value="logo">
                <input type="file" name="logo_file" required accept="image/*" style="border:1px solid #ddd; padding:5px;">
                <button type="submit" class="cta-btn" style="padding:8px 15px; font-size:0.9rem; background:#3498db;">Mettre à jour</button>
            </form>
        </div>

        <!-- 1. Formulaire de publication -->
        <div class="card">
            <h2><i class="fa-solid fa-pen-to-square"></i> Publier une offre</h2>
            <form action="post_job.php" method="POST">
                <div class="form-group"><label>Titre du poste</label><input type="text" name="title" placeholder="Ex: Développeur Web Junior" required></div>
                <div class="form-group"><label>Lieu</label><input type="text" name="location" placeholder="Ex: Paris (75) ou Télétravail" required></div>
                <div class="form-group"><label>Type de contrat</label>
                    <select name="contract_type" required>
                        <option value="CDI">CDI</option><option value="CDD">CDD</option><option value="Alternance">Alternance</option><option value="Stage">Stage</option>
                    </select>
                </div>
                <div class="form-group"><label>Salaire (Optionnel)</label><input type="text" name="salary" placeholder="Ex: 35k - 45k"></div>
                <div class="form-group"><label>Mots-clés</label><input type="text" name="keywords" placeholder="Ex: PHP, MySQL, Junior"></div>
                <div class="form-group"><label>Description détaillée</label><textarea name="description" rows="5" required placeholder="Détails du poste..."></textarea></div>
                <button type="submit" class="cta-btn" style="width:100%; background:#27ae60;">Publier l'offre</button>
            </form>
        </div>

        <!-- 2. LISTE DE MES OFFRES -->
        <div class="card">
            <h2><i class="fa-solid fa-list"></i> Mes offres en ligne</h2>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            $mesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($mesOffres) > 0): ?>
                <table>
                    <thead><tr><th>Titre</th><th>Date</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach($mesOffres as $job): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($job['title']) ?></strong></td>
                            <td><?= date('d/m/Y', strtotime($job['created_at'])) ?></td>
                            <td>
                                <a href="delete_job.php?id=<?= $job['id'] ?>" class="btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?');">
                                   <i class="fa-solid fa-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:#777;">Vous n'avez aucune offre en ligne.</p>
            <?php endif; ?>
        </div>

        <!-- 3. CANDIDATURES REÇUES -->
        <div class="card">
            <h2><i class="fa-solid fa-users"></i> Candidatures Reçues</h2>
            <?php
            $sql = "SELECT applications.*, candidates.username, candidates.email, candidates.cv, jobs.title 
                    FROM applications
                    JOIN jobs ON applications.job_id = jobs.id
                    JOIN candidates ON applications.candidate_id = candidates.id
                    WHERE jobs.company_id = ? ORDER BY applications.applied_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($candidatures) > 0): ?>
                <table>
                    <thead><tr><th>Candidat</th><th>Offre</th><th>CV</th><th>Statut</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach($candidatures as $app): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($app['username']) ?></strong><br>
                                <small><a href="mailto:<?= htmlspecialchars($app['email']) ?>"><?= htmlspecialchars($app['email']) ?></a></small>
                            </td>
                            <td><?= htmlspecialchars($app['title']) ?></td>
                            <td>
                                <?php if($app['cv']): ?>
                                    <a href="uploads/<?= htmlspecialchars($app['cv']) ?>" target="_blank" style="color:#e67e22; font-weight:bold;">
                                        <i class="fa-solid fa-file-pdf"></i> Voir CV
                                    </a>
                                <?php else: ?>
                                    <span style="color:#ccc;">Pas de CV</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($app['status'] == 'en_attente'): ?>
                                    <span class="status-badge" style="background:#fff3cd; color:#856404;">En attente</span>
                                <?php elseif($app['status'] == 'accepte'): ?>
                                    <span class="status-badge" style="background:#d4edda; color:#155724;">Accepté</span>
                                <?php elseif($app['status'] == 'refuse'): ?>
                                    <span class="status-badge" style="background:#f8d7da; color:#721c24;">Refusé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($app['status'] == 'en_attente'): ?>
                                    <a href="update_status.php?id=<?= $app['id'] ?>&status=accepte" title="Accepter" style="color:green; margin-right:10px; font-size:1.2rem;"><i class="fa-solid fa-check-circle"></i></a>
                                    <a href="update_status.php?id=<?= $app['id'] ?>&status=refuse" title="Refuser" style="color:red; font-size:1.2rem;" onclick="return confirm('Refuser ce candidat ?');"><i class="fa-solid fa-times-circle"></i></a>
                                <?php else: ?>
                                    <span style="color:#aaa; font-size:0.8rem;"><i class="fa-solid fa-lock"></i> Traité</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:#777;">Aucune candidature reçue pour le moment.</p>
            <?php endif; ?>
        </div>

    <!-- ============================
         VUE CANDIDAT
    ============================= -->
    <?php elseif ($role == 'candidat'): ?>
        
        <!-- 0. GESTION DU CV -->
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                <div>
                    <h3><i class="fa-solid fa-file-pdf"></i> Mon CV (PDF)</h3>
                    <p style="font-size:0.9rem; color:#666;">Ajoutez votre CV pour les recruteurs.</p>
                </div>
                
                <?php 
                // Vérification du CV existant
                $stmt = $pdo->prepare("SELECT cv FROM candidates WHERE id = ?");
                $stmt->execute([$user_id]);
                $monCV = $stmt->fetchColumn();
                
                if($monCV): 
                ?>
                    <div style="text-align:right; margin-top:10px;">
                        <a href="uploads/<?= htmlspecialchars($monCV) ?>" target="_blank" style="color:#2ecc71; font-weight:bold; margin-right:15px; text-decoration:none;">
                            <i class="fa-solid fa-eye"></i> Voir mon CV
                        </a>
                        <a href="delete_cv.php" onclick="return confirm('Supprimer définitivement votre CV ?')" 
                           style="color:#e74c3c; font-weight:bold; text-decoration:none;">
                            <i class="fa-solid fa-trash"></i> Supprimer
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <form action="upload.php" method="POST" enctype="multipart/form-data" style="margin-top:15px; display:flex; gap:10px; align-items:center;">
                <input type="hidden" name="upload_type" value="cv">
                <input type="file" name="cv_file" required accept=".pdf" style="border:1px solid #ddd; padding:5px;">
                <button type="submit" class="cta-btn" style="padding:8px 15px; font-size:0.9rem; background:#9b59b6;">
                    <?= ($monCV) ? "Remplacer mon CV" : "Envoyer mon CV" ?>
                </button>
            </form>
        </div>

        <div class="card">
            <h2><i class="fa-solid fa-briefcase"></i> Mes Candidatures envoyées</h2>
            <?php
            $sql = "SELECT applications.*, jobs.title, companies.company_name 
                    FROM applications
                    JOIN jobs ON applications.job_id = jobs.id
                    JOIN companies ON jobs.company_id = companies.id
                    WHERE applications.candidate_id = ? ORDER BY applications.applied_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $mesCandidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($mesCandidatures) > 0): ?>
                <table>
                    <thead><tr><th>Poste</th><th>Entreprise</th><th>Date</th><th>Statut</th></tr></thead>
                    <tbody>
                        <?php foreach($mesCandidatures as $app): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($app['title']) ?></strong></td>
                            <td><?= htmlspecialchars($app['company_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($app['applied_at'])) ?></td>
                            <td>
                                <?php if($app['status'] == 'en_attente'): ?>
                                    <span class="status-badge" style="background:#fff3cd; color:#856404;"><i class="fa-regular fa-clock"></i> En attente</span>
                                <?php elseif($app['status'] == 'accepte'): ?>
                                    <span class="status-badge" style="background:#d4edda; color:#155724;"><i class="fa-solid fa-check"></i> Félicitations !</span>
                                <?php elseif($app['status'] == 'refuse'): ?>
                                    <span class="status-badge" style="background:#f8d7da; color:#721c24;"><i class="fa-solid fa-xmark"></i> Refusé</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:#777;">Vous n'avez postulé à aucune offre.</p>
                <button class="cta-btn" onclick="window.location.href='index.php#offres'">Voir les offres</button>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
