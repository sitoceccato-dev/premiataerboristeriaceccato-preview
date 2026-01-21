<?php
/**
 * Admin - Gestione Categorie
 */
session_start();
if (!isset($_SESSION['admin_logged'])) { header('Location: index.php'); exit; }

require_once '../config.php';

$message = '';

// Elimina
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categorie WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = '✅ Categoria eliminata';
}

// Salva
if ($_POST) {
    $data = [$_POST['nome'], $_POST['slug'], $_POST['icona'], $_POST['ordine']];
    
    if ($_POST['id']) {
        $data[] = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE categorie SET nome=?, slug=?, icona=?, ordine=? WHERE id=?");
        $stmt->execute($data);
        $message = '✅ Categoria aggiornata';
    } else {
        $stmt = $pdo->prepare("INSERT INTO categorie (nome, slug, icona, ordine) VALUES (?,?,?,?)");
        $stmt->execute($data);
        $message = '✅ Categoria creata';
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categorie WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

$showForm = isset($_GET['action']) && $_GET['action'] === 'new';
$categorie = $pdo->query("SELECT * FROM categorie ORDER BY ordine")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorie - Admin</title>
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
        .form-actions { display: flex; gap: 15px; margin-top: 20px; }
        .btn { padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: #4a7c23; color: white; }
        .btn-secondary { background: #ddd; color: #333; text-decoration: none; display: inline-block; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9f9f5; color: #2d5016; }
        .cat-icon { font-size: 30px; }
        .actions a { display: inline-block; padding: 8px 15px; margin-right: 5px; border-radius: 5px; text-decoration: none; font-size: 13px; }
        .actions .edit { background: #f4d03f; color: #2d5016; }
        .actions .delete { background: #e74c3c; color: white; }
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
        <a href="categorie.php" class="active">📁 Categorie</a>
        <a href="slider.php">🖼️ Slider</a>
        <a href="fornitori.php">🏭 Fornitori</a>
        <a href="impostazioni.php">⚙️ Impostazioni</a>
    </nav>
    
    <main>
        <div class="page-header">
            <h2>📁 Gestione Categorie</h2>
            <a href="categorie.php?action=new" class="btn-new">➕ Nuova Categoria</a>
        </div>
        
        <?php if ($message): ?><div class="message"><?php echo $message; ?></div><?php endif; ?>
        
        <?php if ($edit || $showForm): ?>
        <div class="card">
            <h3 style="color: #2d5016; margin-bottom: 20px;"><?php echo $edit ? '✏️ Modifica' : '➕ Nuova'; ?> Categoria</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome *</label>
                        <input type="text" name="nome" value="<?php echo $edit['nome'] ?? ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Slug (URL)</label>
                        <input type="text" name="slug" value="<?php echo $edit['slug'] ?? ''; ?>" placeholder="tisane-infusi">
                    </div>
                    <div class="form-group">
                        <label>Icona (emoji)</label>
                        <input type="text" name="icona" value="<?php echo $edit['icona'] ?? '🌿'; ?>">
                    </div>
                    <div class="form-group">
                        <label>Ordine</label>
                        <input type="number" name="ordine" value="<?php echo $edit['ordine'] ?? 0; ?>">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Salva</button>
                    <a href="categorie.php" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <table>
                <thead><tr><th></th><th>Nome</th><th>Slug</th><th>Ordine</th><th>Azioni</th></tr></thead>
                <tbody>
                    <?php foreach ($categorie as $c): ?>
                    <tr>
                        <td class="cat-icon"><?php echo $c['icona']; ?></td>
                        <td><strong><?php echo $c['nome']; ?></strong></td>
                        <td><?php echo $c['slug']; ?></td>
                        <td><?php echo $c['ordine']; ?></td>
                        <td class="actions">
                            <a href="categorie.php?edit=<?php echo $c['id']; ?>" class="edit">✏️ Modifica</a>
                            <a href="categorie.php?delete=<?php echo $c['id']; ?>" class="delete" onclick="return confirm('Eliminare?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
