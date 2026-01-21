<?php
/**
 * Admin - Gestione Slider
 */
session_start();
if (!isset($_SESSION['admin_logged'])) { header('Location: index.php'); exit; }

require_once '../config.php';

$message = '';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM slider WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = '✅ Slide eliminata';
}

if ($_POST) {
    $data = [
        $_POST['titolo'],
        $_POST['sottotitolo'],
        $_POST['colore_bg'],
        $_POST['link'],
        $_POST['ordine'],
        isset($_POST['attivo']) ? 1 : 0
    ];
    
    if ($_POST['id']) {
        $data[] = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE slider SET titolo=?, sottotitolo=?, colore_bg=?, link=?, ordine=?, attivo=? WHERE id=?");
        $stmt->execute($data);
        $message = '✅ Slide aggiornata';
    } else {
        $stmt = $pdo->prepare("INSERT INTO slider (titolo, sottotitolo, colore_bg, link, ordine, attivo) VALUES (?,?,?,?,?,?)");
        $stmt->execute($data);
        $message = '✅ Slide creata';
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM slider WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

$showForm = isset($_GET['action']) && $_GET['action'] === 'new';
$slides = $pdo->query("SELECT * FROM slider ORDER BY ordine")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f0; }
        .admin-header { background: linear-gradient(135deg, #2d5016 0%, #4a7c23 100%); color: white; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 24px; }
        .admin-header a { color: white; text-decoration: none; margin-left: 20px; }
        .admin-nav { background: white; padding: 15px 40px; border-bottom: 3px solid #f4d03f; display: flex; gap: 10px; }
        .admin-nav a { padding: 12px 25px; background: #f5f5f0; color: #2d5016; text-decoration: none; border-radius: 8px; font-weight: 600; }
        .admin-nav a:hover, .admin-nav a.active { background: #4a7c23; color: white; }
        main { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h2 { color: #2d5016; }
        .btn-new { background: #4a7c23; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .message { background: #d4edda; color: #155724; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; }
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #2d5016; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #4a7c23; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .color-presets { display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; }
        .color-preset { width: 40px; height: 40px; border-radius: 8px; cursor: pointer; border: 3px solid transparent; }
        .color-preset:hover, .color-preset.active { border-color: #2d5016; }
        .form-actions { display: flex; gap: 15px; margin-top: 20px; }
        .btn { padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: #4a7c23; color: white; }
        .btn-secondary { background: #ddd; color: #333; text-decoration: none; display: inline-block; }
        .slides-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .slide-card { border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .slide-preview { height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; text-align: center; padding: 20px; }
        .slide-preview h3 { font-size: 22px; margin-bottom: 5px; }
        .slide-info { padding: 15px; background: white; }
        .slide-info .status { font-size: 13px; margin-bottom: 10px; }
        .slide-info .actions { display: flex; gap: 10px; }
        .slide-info .actions a { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 13px; }
        .slide-info .edit { background: #f4d03f; color: #2d5016; }
        .slide-info .delete { background: #e74c3c; color: white; }
        .status-active { color: #27ae60; }
        .status-inactive { color: #999; }
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
        <a href="slider.php" class="active">🖼️ Slider</a>
        <a href="fornitori.php">🏭 Fornitori</a>
        <a href="impostazioni.php">⚙️ Impostazioni</a>
    </nav>
    
    <main>
        <div class="page-header">
            <h2>🖼️ Gestione Slider</h2>
            <a href="slider.php?action=new" class="btn-new">➕ Nuova Slide</a>
        </div>
        
        <?php if ($message): ?><div class="message"><?php echo $message; ?></div><?php endif; ?>
        
        <?php if ($edit || $showForm): ?>
        <div class="card">
            <h3 style="color: #2d5016; margin-bottom: 20px;"><?php echo $edit ? '✏️ Modifica' : '➕ Nuova'; ?> Slide</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                
                <div class="form-group">
                    <label>Titolo</label>
                    <input type="text" name="titolo" value="<?php echo $edit['titolo'] ?? ''; ?>" placeholder="Benvenuti nella Natura">
                </div>
                
                <div class="form-group">
                    <label>Sottotitolo</label>
                    <input type="text" name="sottotitolo" value="<?php echo $edit['sottotitolo'] ?? ''; ?>" placeholder="Scopri i nostri prodotti">
                </div>
                
                <div class="form-group">
                    <label>Colore/Gradiente sfondo</label>
                    <input type="text" name="colore_bg" id="colorBg" value="<?php echo $edit['colore_bg'] ?? 'linear-gradient(135deg, #4a7c23 0%, #8fbc3b 100%)'; ?>">
                    <div class="color-presets">
                        <div class="color-preset" style="background: linear-gradient(135deg, #4a7c23 0%, #8fbc3b 100%);" onclick="setColor('linear-gradient(135deg, #4a7c23 0%, #8fbc3b 100%)')"></div>
                        <div class="color-preset" style="background: linear-gradient(135deg, #f4d03f 0%, #c5b358 100%);" onclick="setColor('linear-gradient(135deg, #f4d03f 0%, #c5b358 100%)')"></div>
                        <div class="color-preset" style="background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);" onclick="setColor('linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%)')"></div>
                        <div class="color-preset" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);" onclick="setColor('linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)')"></div>
                        <div class="color-preset" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);" onclick="setColor('linear-gradient(135deg, #3498db 0%, #2980b9 100%)')"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Link (opzionale)</label>
                        <input type="text" name="link" value="<?php echo $edit['link'] ?? ''; ?>" placeholder="offerte.html">
                    </div>
                    <div class="form-group">
                        <label>Ordine</label>
                        <input type="number" name="ordine" value="<?php echo $edit['ordine'] ?? 0; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><input type="checkbox" name="attivo" <?php echo ($edit['attivo'] ?? 1) ? 'checked' : ''; ?>> Attiva</label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Salva</button>
                    <a href="slider.php" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <div class="slides-grid">
            <?php foreach ($slides as $s): ?>
            <div class="slide-card">
                <div class="slide-preview" style="background: <?php echo $s['colore_bg']; ?>;">
                    <h3><?php echo $s['titolo'] ?: '(Senza titolo)'; ?></h3>
                    <p><?php echo $s['sottotitolo']; ?></p>
                </div>
                <div class="slide-info">
                    <div class="status <?php echo $s['attivo'] ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo $s['attivo'] ? '✅ Attiva' : '❌ Disattivata'; ?> • Ordine: <?php echo $s['ordine']; ?>
                    </div>
                    <div class="actions">
                        <a href="slider.php?edit=<?php echo $s['id']; ?>" class="edit">✏️ Modifica</a>
                        <a href="slider.php?delete=<?php echo $s['id']; ?>" class="delete" onclick="return confirm('Eliminare?')">🗑️</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    
    <script>
    function setColor(color) {
        document.getElementById('colorBg').value = color;
    }
    </script>
</body>
</html>
