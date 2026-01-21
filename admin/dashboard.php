<?php
/**
 * Admin Dashboard
 */
session_start();
if (!isset($_SESSION['admin_logged']) || !$_SESSION['admin_logged']) {
    header('Location: index.php');
    exit;
}

require_once '../config.php';

// Statistiche
$tot_prodotti = $pdo->query("SELECT COUNT(*) FROM prodotti")->fetchColumn();
$tot_categorie = $pdo->query("SELECT COUNT(*) FROM categorie")->fetchColumn();
$tot_fornitori = $pdo->query("SELECT COUNT(*) FROM fornitori")->fetchColumn();
$prodotti_evidenza = $pdo->query("SELECT COUNT(*) FROM prodotti WHERE in_evidenza = TRUE")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f0; }
        
        .admin-header {
            background: linear-gradient(135deg, #2d5016 0%, #4a7c23 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 { font-size: 24px; }
        .admin-header a { color: white; text-decoration: none; margin-left: 20px; }
        
        .admin-nav {
            background: white;
            padding: 15px 40px;
            border-bottom: 3px solid #f4d03f;
            display: flex;
            gap: 10px;
        }
        .admin-nav a {
            padding: 12px 25px;
            background: #f5f5f0;
            color: #2d5016;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .admin-nav a:hover, .admin-nav a.active { background: #4a7c23; color: white; }
        
        main { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        h2 { color: #2d5016; margin-bottom: 30px; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-icon { font-size: 50px; margin-bottom: 15px; }
        .stat-number { font-size: 36px; font-weight: bold; color: #2d5016; }
        .stat-label { color: #888; margin-top: 5px; }
        
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .quick-actions h3 { color: #2d5016; margin-bottom: 20px; }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px;
            background: #f5f5f0;
            border-radius: 12px;
            text-decoration: none;
            color: #2d5016;
            transition: all 0.3s;
        }
        .action-btn:hover { background: #f4d03f; transform: translateY(-3px); }
        .action-btn span:first-child { font-size: 35px; margin-bottom: 10px; }
        
        @media (max-width: 900px) {
            .stats-grid, .actions-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>🌿 Pannello Amministrazione</h1>
        <div>
            <span>👤 <?php echo $_SESSION['admin_user']; ?></span>
            <a href="logout.php">🚪 Esci</a>
        </div>
    </div>
    
    <nav class="admin-nav">
        <a href="dashboard.php" class="active">📊 Dashboard</a>
        <a href="prodotti.php">📦 Prodotti</a>
        <a href="categorie.php">📁 Categorie</a>
        <a href="slider.php">🖼️ Slider</a>
        <a href="fornitori.php">🏭 Fornitori</a>
        <a href="impostazioni.php">⚙️ Impostazioni</a>
    </nav>
    
    <main>
        <h2>📊 Dashboard</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-number"><?php echo $tot_prodotti; ?></div>
                <div class="stat-label">Prodotti totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-number"><?php echo $prodotti_evidenza; ?></div>
                <div class="stat-label">In evidenza</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📁</div>
                <div class="stat-number"><?php echo $tot_categorie; ?></div>
                <div class="stat-label">Categorie</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏭</div>
                <div class="stat-number"><?php echo $tot_fornitori; ?></div>
                <div class="stat-label">Fornitori</div>
            </div>
        </div>
        
        <div class="quick-actions">
            <h3>⚡ Azioni Rapide</h3>
            <div class="actions-grid">
                <a href="prodotti.php?action=new" class="action-btn">
                    <span>➕</span>
                    <span>Nuovo Prodotto</span>
                </a>
                <a href="slider.php?action=new" class="action-btn">
                    <span>🖼️</span>
                    <span>Nuova Slide</span>
                </a>
                <a href="fornitori.php?action=new" class="action-btn">
                    <span>🏭</span>
                    <span>Nuovo Fornitore</span>
                </a>
                <a href="../index.php" target="_blank" class="action-btn">
                    <span>👁️</span>
                    <span>Vedi Sito</span>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
