<?php
// Fichier : dashboard.php (Version Design HelloWork)
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StageBoard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- STYLES SPÉCIFIQUES AU DASHBOARD (Overlaps style.css) --- */
        body { 
            background-color: #f5f6f9; 
            font-family: 'Inter', sans-serif;
            padding-top: 0; /* On gère la nav différemment */
        }

        /* Navbar simplifiée pour le Dashboard */
        .dash-nav {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            position: sticky; top: 0; z-index: 100;
        }
        .dash-logo { font-size: 1.4rem; font-weight: 800; color: #18181b; text-decoration: none; }
        .dash-logo span { color: #0c57e5; }

        .dashboard-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }

        /* Cartes modernes */
        .card { 
            background: white; 
            padding: 30px; 
            border-radius: 20px; /* Arrondis HelloWork */
            margin-bottom: 30px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.06); 
            border: 1px solid #f0f0f0;
        }
        
        .card h2, .card h3 { color: #1a1a1a; margin-bottom: 20px; font-weight: 700; border: none; }
        .card h2 i, .card h3 i { color: #0c57e5; margin-right: 10px; }

        /* Header du dashboard */
        .header-dash { 
            display: flex; justify-content: space-between; align-items: flex-end; 
            margin-bottom: 40px; 
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }
        .header-dash h1 { font-size: 1.8rem; margin-bottom: 5px; color: #1a1a1a; }
        .header-dash p { color: #666; font-size: 1rem; }

        /* Boutons d'action Header */
        .btn-action { 
            background: #f1f3f5; color: #333; 
            padding: 10px 18px; 
            border-radius: 50px; 
            font-size: 0.9rem; font-weight: 600; 
            display:inline-flex; align-items:center; gap:8px; 
            transition: all 0.2s;
        }
        .btn-action:hover { background: #e9ecef; transform: translateY(-2px); }
        .btn-logout { background: #fff0f0; color: #e74c3c; }
        .btn-logout:hover { background: #fee2e2; }

        /* Formulaires Modernes */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; font-size: 0.95rem; }
        
        /* Inputs style HelloWork (Gris clair, sans bordure forte) */
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; 
            padding: 14px 16px; 
            background-color: #f9fafb;
            border: 1px solid #e5e7eb; 
            border-radius: 12px; 
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            background-color: white;
            border-color: #0c57e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 87, 229, 0.1);
        }

        /* Boutons principaux */
        .cta-btn {
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            transition: transform 0.2s;
        }
        .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

        /* Tableaux Modernes */
        .table-responsive { overflow-x: auto; border-radius: 12px; border: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th { background-color: #f9fafb; padding: 15px; text-align: left; font-weight: 600; color: #666; font-size: 0.9rem; }
        td { padding: 15px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; color: #333; }
        tr:last-child td { border-bottom: none; }
        
        /* Badges de statut */
        .status-badge { padding: 6px 12px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; }
        
        /* Alertes */
        .alert-success { background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #d1fae5; font-weight: 500; display: flex; align-items: center; gap: 10px; }

        /* Input file stylisé */
        input[type="file"] { background: white; padding: 10px; }
    </style>
</head>
<body>

<nav class="dash-nav">
    <a href="index.php" class="dash-logo">Stage<span>Board</span></a>
    <div style="display:flex; gap:10px; align-items:center;">
        <span style="font-size:0.9rem; color:#666; margin-right:10px;">
            <i class="fa-solid fa-circle-user"></i> <?= htmlspecialchars($nom) ?>
        </span>
    </div>
</nav>

<div class="dashboard-container">
    
    <?php if($msg): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="header-dash">
        <div>
            <h1>Tableau de Bord</h1>
            <p>Gérez vos informations et suivez votre activité.</p>
        </div>
        
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="index.php" class="btn-action">
                <i class="fa-solid fa-globe"></i> Voir le site
            </a>
            <a href="settings.php" class="btn-action" style="background:#eef2ff; color:#0c57e5;">
                <i class="fa-solid fa-gear"></i> Paramètres
            </a>
            <a href="auth.php?logout=true" class="btn-action btn-logout">
                <i class="fa-solid fa-power-off"></i> Déconnexion
            </a>
        </div>
    </div>

    <?php if ($role == 'recruteur'): ?>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            
            <div>
                <div class="card" style="text-align: center;">
                    <h3><i class="fa-solid fa-image"></i> Logo</h3>
                    <p style="font-size:0.9rem; color:#666; margin-bottom: 20px;">Personnalisez vos offres.</p>
                    
                    <form action="upload.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="upload_type" value="logo">
                        <input type="file" name="logo_file" required accept="image/*" style="width:100%; margin-bottom:10px; font-size:0.9rem;">
                        <button type="submit" class="cta-btn" style="width:100%; background:#0c57e5;">Mettre à jour</button>
                    </form>
                </div>

                <div class="card" style="background: #0c57e5; color: white;">
                    <h3 style="color:white;"><i class="fa-solid fa-chart-simple" style="color:white;"></i> Stats</h3>
                    <p style="color:#e0e7ff;">Optimisez vos recrutements en publiant régulièrement.</p>
                </div>
            </div>

            <div>
                <div class="card">
                    <h2><i class="fa-solid fa-pen-to-square"></i> Publier une nouvelle offre</h2>
                    <form action="post_job.php" method="POST">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Titre du poste</label>
                                <input type="text" name="title" placeholder="Ex: Développeur Fullstack" required>
                            </div>
                            <div class="form-group">
                                <label>Lieu</label>
                                <input type="text" name="location" placeholder="Ex: Paris (75)" required>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Type de contrat</label>
                                <select name="contract_type" required>
                                    <option value="CDI">CDI</option>
                                    <option value="CDD">CDD</option>
                                    <option value="Alternance">Alternance</option>
                                    <option value="Stage">Stage</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Salaire (Optionnel)</label>
                                <input type="text" name="salary" placeholder="Ex: 35k - 45k">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Mots-clés (Compétences)</label>
                            <input type="text" name="keywords" placeholder="Ex: PHP, MySQL, React, Junior">
                        </div>

                        <div class="form-group">
                            <label>Description du poste</label>
                            <textarea name="description" rows="6" required placeholder="Décrivez les missions et le profil recherché..."></textarea>
                        </div>

                        <button type="submit" class="cta-btn" style="width:100%; background:#1a1a1a;">Publier l'annonce</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h2 style="margin:0;"><i class="fa-solid fa-briefcase"></i> Mes offres en ligne</h2>
            </div>
            
            <?php
            $stmt = $pdo->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            $mesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="table-responsive">
                <?php if(count($mesOffres) > 0): ?>
                <table>
                    <thead><tr><th>Titre du poste</th><th>Lieu</th><th>Publié le</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach($mesOffres as $job): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($job['title']) ?></strong></td>
                            <td><span style="background:#f0f2f5; padding:4px 8px; border-radius:4px; font-size:0.85rem; color:#666;"><?= htmlspecialchars($job['location']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($job['created_at'])) ?></td>
                            <td>
                                <a href="delete_job.php?id=<?= $job['id'] ?>" onclick="return confirm('Supprimer ?');" style="color:#e74c3c; font-weight:600; text-decoration:none; font-size:0.9rem;">
                                   <i class="fa-regular fa-trash-can"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="padding:30px; text-align:center; color:#666;">Aucune offre publiée pour le moment.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h2><i class="fa-solid fa-users-viewfinder"></i> Candidatures Reçues</h2>
            <?php
            $sql = "SELECT applications.*, candidates.username, candidates.email, candidates.cv, jobs.title 
                    FROM applications
                    JOIN jobs ON applications.job_id = jobs.id
                    JOIN candidates ON applications.candidate_id = candidates.id
                    WHERE jobs.company_id = ? ORDER BY applications.applied_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="table-responsive">
                <?php if(count($candidatures) > 0): ?>
                <table>
                    <thead><tr><th>Candidat</th><th>Pour l'offre</th><th>CV</th><th>Statut</th><th>Décision</th></tr></thead>
                    <tbody>
                        <?php foreach($candidatures as $app): ?>
                        <tr>
                            <td>
                                <div style="font-weight:700; color:#1a1a1a;"><?= htmlspecialchars($app['username']) ?></div>
                                <div style="font-size:0.85rem; color:#666;"><?= htmlspecialchars($app['email']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($app['title']) ?></td>
                            <td>
                                <?php if($app['cv']): ?>
                                    <a href="uploads/<?= htmlspecialchars($app['cv']) ?>" target="_blank" style="color:#0c57e5; font-weight:600; text-decoration:none; background:#eef2ff; padding:6px 12px; border-radius:30px; font-size:0.85rem;">
                                        <i class="fa-solid fa-eye"></i> Voir CV
                                    </a>
                                <?php else: ?>
                                    <span style="color:#ccc;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($app['status'] == 'en_attente'): ?>
                                    <span class="status-badge" style="background:#fff7ed; color:#c2410c;">En attente</span>
                                <?php elseif($app['status'] == 'accepte'): ?>
                                    <span class="status-badge" style="background:#ecfdf5; color:#047857;">Accepté</span>
                                <?php elseif($app['status'] == 'refuse'): ?>
                                    <span class="status-badge" style="background:#fef2f2; color:#b91c1c;">Refusé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($app['status'] == 'en_attente'): ?>
                                    <div style="display:flex; gap:10px;">
                                        <a href="update_status.php?id=<?= $app['id'] ?>&status=accepte" style="background:#ecfdf5; color:#047857; width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:50%; text-decoration:none;"><i class="fa-solid fa-check"></i></a>
                                        <a href="update_status.php?id=<?= $app['id'] ?>&status=refuse" onclick="return confirm('Refuser ?');" style="background:#fef2f2; color:#b91c1c; width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:50%; text-decoration:none;"><i class="fa-solid fa-xmark"></i></a>
                                    </div>
                                <?php else: ?>
                                    <span style="color:#ccc; font-size:0.85rem;"><i class="fa-solid fa-lock"></i> Clos</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div style="padding:30px; text-align:center; color:#666;">Aucune candidature reçue.</div>
                <?php endif; ?>
            </div>
        </div>

    <?php elseif ($role == 'candidat'): ?>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            
            <div class="card">
                <h3><i class="fa-solid fa-file-pdf"></i> Mon CV</h3>
                <p style="color:#666; font-size:0.9rem; margin-bottom:20px;">Ajoutez votre CV pour que les recruteurs puissent le consulter.</p>
                
                <?php 
                $stmt = $pdo->prepare("SELECT cv FROM candidates WHERE id = ?");
                $stmt->execute([$user_id]);
                $monCV = $stmt->fetchColumn();
                ?>

                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="upload_type" value="cv">
                    <input type="file" name="cv_file" required accept=".pdf" style="width:100%; margin-bottom:15px;">
                    <button type="submit" class="cta-btn" style="width:100%; background:#0c57e5;">
                        <?= ($monCV) ? "Remplacer mon CV" : "Envoyer mon CV" ?>
                    </button>
                </form>

                <?php if($monCV): ?>
                    <div style="margin-top:20px; border-top:1px solid #eee; padding-top:15px; display:flex; flex-direction:column; gap:10px;">
                        <a href="uploads/<?= htmlspecialchars($monCV) ?>" target="_blank" style="color:#1a1a1a; font-weight:600; font-size:0.9rem; text-decoration:none; display:flex; align-items:center; gap:8px;">
                            <i class="fa-regular fa-file-lines"></i> Voir mon CV actuel
                        </a>
                        <a href="delete_cv.php" onclick="return confirm('Supprimer ?')" style="color:#e74c3c; font-weight:600; font-size:0.9rem; text-decoration:none; display:flex; align-items:center; gap:8px;">
                            <i class="fa-solid fa-trash"></i> Supprimer le fichier
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <h2><i class="fa-solid fa-paper-plane"></i> Mes Candidatures</h2>
                <?php
                $sql = "SELECT applications.*, jobs.title, companies.company_name, companies.logo
                        FROM applications
                        JOIN jobs ON applications.job_id = jobs.id
                        JOIN companies ON jobs.company_id = companies.id
                        WHERE applications.candidate_id = ? ORDER BY applications.applied_at DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);
                $mesCandidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="table-responsive">
                    <?php if(count($mesCandidatures) > 0): ?>
                    <table>
                        <thead><tr><th>Entreprise</th><th>Poste</th><th>Date</th><th>Statut</th></tr></thead>
                        <tbody>
                            <?php foreach($mesCandidatures as $app): ?>
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <?php if(!empty($app['logo'])): ?>
                                            <img src="uploads/<?= htmlspecialchars($app['logo']) ?>" style="width:30px; height:30px; object-fit:contain; border-radius:4px;">
                                        <?php endif; ?>
                                        <span style="font-weight:600;"><?= htmlspecialchars($app['company_name']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($app['title']) ?></td>
                                <td><?= date('d/m/Y', strtotime($app['applied_at'])) ?></td>
                                <td>
                                    <?php if($app['status'] == 'en_attente'): ?>
                                        <span class="status-badge" style="background:#fff7ed; color:#c2410c;"><i class="fa-regular fa-clock"></i> En attente</span>
                                    <?php elseif($app['status'] == 'accepte'): ?>
                                        <span class="status-badge" style="background:#ecfdf5; color:#047857;"><i class="fa-solid fa-check"></i> Accepté</span>
                                    <?php elseif($app['status'] == 'refuse'): ?>
                                        <span class="status-badge" style="background:#fef2f2; color:#b91c1c;"><i class="fa-solid fa-xmark"></i> Refusé</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <div style="padding:40px; text-align:center;">
                            <i class="fa-regular fa-folder-open" style="font-size:2rem; color:#ddd; margin-bottom:15px;"></i>
                            <p style="color:#666;">Vous n'avez pas encore postulé.</p>
                            <a href="index.php#offres" style="color:#0c57e5; font-weight:600; text-decoration:none;">Trouver une offre</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

</body>
</html>