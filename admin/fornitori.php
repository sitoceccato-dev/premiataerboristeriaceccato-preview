<?php
/**
 * Admin - Gestione Fornitori
 */
session_start();
if (!isset($_SESSION['admin_logged'])) { header('Location: index.php'); exit; }

require_once '../config.php';

$message = '';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM fornitori WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = '✅ Fornitore eliminato';
}

if ($_POST) {
    $data = [
        $_POST['nome'],
        $_POST['icona'],
        $_POST['ordine'],
        isset($_POST['attivo']) ? 1 : 0
    ];
    
    if ($_POST['id']) {
        $data[] = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE fornitori SET nome=?, icona=?, ordine=?, attivo=? WHERE id=?");
        $stmt->execute($data);
        $message = '✅ Fornitore aggiornato';
    } else {
        $stmt = $pdo->prepare("INSERT INTO fornitori (nome, icona, ordine, attivo) VALUES (?,?,?,?)");
        $stmt->execute($data);
        $message = '✅ Fornitore creato';
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM fornitori WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

$showForm = isset($_GET['action']) && $_GET['action'] === 'new';
$fornitori = $pdo->query("SELECT * FROM fornitori ORDER BY ordine")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornitori - Admin</title>
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
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #2d5016; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; }
        .form-group input:focus { outline: none; border-color: #4a7c23; }
        .emoji-picker { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
        .emoji-btn { font-size: 24px; padding: 8px; background: #f5f5f0; border: 2px solid transparent; border-radius: 8px; cursor: pointer; }
        .emoji-btn:hover { border-color: #4a7c23; }
        .form-actions { display: flex; gap: 15px; margin-top: 20px; }
        .btn { padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: #4a7c23; color: white; }
        .btn-secondary { background: #ddd; color: #333; text-decoration: none; display: inline-block; }
        .fornitori-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .fornitore-card { background: white; border: 2px solid #f4d03f; border-radius: 12px; padding: 20px; text-align: center; }
        .fornitore-card .icon { font-size: 40px; margin-bottom: 10px; }
        .fornitore-card .name { font-weight: bold; color: #2d5016; margin-bottom: 10px; }
        .fornitore-card .actions { display: flex; gap: 10px; justify-content: center; }
        .fornitore-card .actions a { padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; }
        .fornitore-card .edit { background: #f4d03f; color: #2d5016; }
        .fornitore-card .delete { background: #e74c3c; color: white; }
        .status-inactive { opacity: 0.5; }
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
        <a href="fornitori.php" class="active">🏭 Fornitori</a>
        <a href="impostazioni.php">⚙️ Impostazioni</a>
    </nav>
    
    <main>
        <div class="page-header">
            <h2>🏭 Gestione Fornitori</h2>
            <a href="fornitori.php?action=new" class="btn-new">➕ Nuovo Fornitore</a>
        </div>
        
        <?php if ($message): ?><div class="message"><?php echo $message; ?></div><?php endif; ?>
        
        <?php if ($edit || $showForm): ?>
        <div class="card">
            <h3 style="color: #2d5016; margin-bottom: 20px;"><?php echo $edit ? '✏️ Modifica' : '➕ Nuovo'; ?> Fornitore</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome *</label>
                        <input type="text" name="nome" value="<?php echo $edit['nome'] ?? ''; ?>" required placeholder="Aboca">
                    </div>
                    <div class="form-group">
                        <label>Icona (emoji)</label>
                        <input type="text" name="icona" id="iconaInput" value="<?php echo $edit['icona'] ?? '🌿'; ?>">
                        <div class="emoji-picker">
                            <span class="emoji-btn" onclick="setEmoji('🌱')">🌱</span>
                            <span class="emoji-btn" onclick="setEmoji('🌿')">🌿</span>
                            <span class="emoji-btn" onclick="setEmoji('🍃')">🍃</span>
                            <span class="emoji-btn" onclick="setEmoji('💊')">💊</span>
                            <span class="emoji-btn" onclick="setEmoji('🧴')">🧴</span>
                            <span class="emoji-btn" onclick="setEmoji('⚗️')">⚗️</span>
                            <span class="emoji-btn" onclick="setEmoji('🍯')">🍯</span>
                            <span class="emoji-btn" onclick="setEmoji('🌻')">🌻</span>
                            <span class="emoji-btn" onclick="setEmoji('🌸')">🌸</span>
                            <span class="emoji-btn" onclick="setEmoji('🧬')">🧬</span>
                            <span class="emoji-btn" onclick="setEmoji('🏥')">🏥</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ordine</label>
                        <input type="number" name="ordine" value="<?php echo $edit['ordine'] ?? 0; ?>">
                    </div>
                    <div class="form-group">
                        <label style="margin-top: 35px;">
                            <input type="checkbox" name="attivo" <?php echo ($edit['attivo'] ?? 1) ? 'checked' : ''; ?>> Visibile
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Salva</button>
                    <a href="fornitori.php" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <div class="fornitori-grid">
            <?php foreach ($fornitori as $f): ?>
            <div class="fornitore-card <?php echo !$f['attivo'] ? 'status-inactive' : ''; ?>">
                <div class="icon"><?php echo $f['icona']; ?></div>
                <div class="name"><?php echo $f['nome']; ?></div>
                <div class="actions">
                    <a href="fornitori.php?edit=<?php echo $f['id']; ?>" class="edit">✏️</a>
                    <a href="fornitori.php?delete=<?php echo $f['id']; ?>" class="delete" onclick="return confirm('Eliminare?')">🗑️</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    
    <script>
    function setEmoji(emoji) {
        document.getElementById('iconaInput').value = emoji;
    }
    </script>
</body>
</html>
