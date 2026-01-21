<?php
/**
 * Admin - Impostazioni Sito
 */
session_start();
if (!isset($_SESSION['admin_logged'])) { header('Location: index.php'); exit; }

require_once '../config.php';

$message = '';

// Le impostazioni sono salvate in config.php
// Qui mostriamo solo la visualizzazione/modifica delle credenziali admin
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f0; }
        .admin-header { background: linear-gradient(135deg, #2d5016 0%, #4a7c23 100%); color: white; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 24px; }
        .admin-header a { color: white; text-decoration: none; margin-left: 20px; }
        .admin-nav { background: white; padding: 15px 40px; border-bottom: 3px solid #f4d03f; display: flex; gap: 10px; }
        .admin-nav a { padding: 12px 25px; background: #f5f5f0; color: #2d5016; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .admin-nav a:hover, .admin-nav a.active { background: #4a7c23; color: white; }
        main { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h2 { color: #2d5016; margin-bottom: 30px; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .card h3 { color: #2d5016; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f4d03f; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border: none; }
        .info-label { color: #888; }
        .info-value { font-weight: 600; color: #2d5016; }
        .alert { background: #fff3cd; color: #856404; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .alert strong { display: block; margin-bottom: 10px; }
        code { background: #f5f5f0; padding: 3px 8px; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>🌿 Pannello Amministrazione</h1>
        <div><span>👤 <?php echo $_SESSION['admin_user']; ?></span> <a href="logout.php">🚪 Esci</a></div>
    </div>
    
    <nav class="admin-nav">
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="prodotti.php">📦 Prodotti</a>
        <a href="categorie.php">📁 Categorie</a>
        <a href="slider.php">🖼️ Slider</a>
        <a href="fornitori.php">🏭 Fornitori</a>
        <a href="impostazioni.php" class="active">⚙️ Impostazioni</a>
    </nav>
    
    <main>
        <h2>⚙️ Impostazioni</h2>
        
        <div class="alert">
            <strong>ℹ️ Come modificare le impostazioni</strong>
            Le impostazioni del sito si trovano nel file <code>config.php</code>.<br>
            Le credenziali admin si trovano in <code>admin/index.php</code>.
        </div>
        
        <div class="card">
            <h3>🏪 Informazioni Sito</h3>
            <div class="info-row">
                <span class="info-label">Nome sito</span>
                <span class="info-value"><?php echo $sito['nome']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Titolare</span>
                <span class="info-value"><?php echo $sito['titolare']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Indirizzo</span>
                <span class="info-value"><?php echo $sito['indirizzo']; ?>, <?php echo $sito['citta']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Telefono</span>
                <span class="info-value"><?php echo $sito['telefono']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value"><?php echo $sito['email']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">P.IVA</span>
                <span class="info-value"><?php echo $sito['piva']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Spedizione gratuita da</span>
                <span class="info-value">€<?php echo $sito['spedizione_gratis']; ?></span>
            </div>
        </div>
        
        <div class="card">
            <h3>🗄️ Database</h3>
            <div class="info-row">
                <span class="info-label">Host</span>
                <span class="info-value"><?php echo DB_HOST; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Database</span>
                <span class="info-value"><?php echo DB_NAME; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Utente</span>
                <span class="info-value"><?php echo DB_USER; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Stato connessione</span>
                <span class="info-value" style="color: #27ae60;">✅ Connesso</span>
            </div>
        </div>
        
        <div class="card">
            <h3>🔐 Credenziali Admin</h3>
            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value">admin</span>
            </div>
            <div class="info-row">
                <span class="info-label">Password</span>
                <span class="info-value">••••••••</span>
            </div>
            <p style="margin-top: 15px; color: #888; font-size: 13px;">
                Per cambiare le credenziali, modifica il file <code>admin/index.php</code>
            </p>
        </div>
        
        <div class="card">
            <h3>📂 File di configurazione</h3>
            <div class="info-row">
                <span class="info-label">Impostazioni sito</span>
                <span class="info-value"><code>config.php</code></span>
            </div>
            <div class="info-row">
                <span class="info-label">Credenziali admin</span>
                <span class="info-value"><code>admin/index.php</code></span>
            </div>
            <div class="info-row">
                <span class="info-label">Database SQL</span>
                <span class="info-value"><code>database.sql</code></span>
            </div>
        </div>
    </main>
</body>
</html>
