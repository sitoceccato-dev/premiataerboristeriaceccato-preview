<?php
/**
 * Admin - Gestione Prodotti
 */
session_start();
if (!isset($_SESSION['admin_logged'])) { header('Location: index.php'); exit; }

require_once '../config.php';

$message = '';

// Elimina prodotto
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM prodotti WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = '✅ Prodotto eliminato';
}

// Salva prodotto
if ($_POST) {
    $data = [
        $_POST['nome'],
        $_POST['descrizione'],
        $_POST['prezzo'],
        $_POST['prezzo_originale'] ?: null,
        $_POST['icona'],
        $_POST['categoria_id'],
        $_POST['badge'] ?: null,
        isset($_POST['in_evidenza']) ? 1 : 0,
        isset($_POST['attivo']) ? 1 : 0
    ];
    
    if ($_POST['id']) {
        // Modifica
        $data[] = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE prodotti SET nome=?, descrizione=?, prezzo=?, prezzo_originale=?, icona=?, categoria_id=?, badge=?, in_evidenza=?, attivo=? WHERE id=?");
        $stmt->execute($data);
        $message = '✅ Prodotto aggiornato';
    } else {
        // Nuovo
        $stmt = $pdo->prepare("INSERT INTO prodotti (nome, descrizione, prezzo, prezzo_originale, icona, categoria_id, badge, in_evidenza, attivo) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute($data);
        $message = '✅ Prodotto creato';
    }
}

// Carica categorie per select
$categorie = $pdo->query("SELECT * FROM categorie ORDER BY nome")->fetchAll();

// Modifica prodotto
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM prodotti WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

// Nuovo prodotto
$showForm = isset($_GET['action']) && $_GET['action'] === 'new';

// Lista prodotti
$prodotti = $pdo->query("SELECT p.*, c.nome as categoria_nome FROM prodotti p LEFT JOIN categorie c ON p.categoria_id = c.id ORDER BY p.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prodotti - Admin</title>
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
        }
        .admin-nav a:hover, .admin-nav a.active { background: #4a7c23; color: white; }
        
        main { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h2 { color: #2d5016; }
        .btn-new {
            background: #4a7c23;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        
        .message {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group.full { grid-column: span 2; }
        .form-group label { display: block; margin-bottom: 8px; color: #2d5016; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #4a7c23; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .checkbox-group { display: flex; gap: 20px; }
        .checkbox-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .checkbox-group input { width: auto; }
        
        .form-actions { display: flex; gap: 15px; margin-top: 20px; }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary { background: #4a7c23; color: white; }
        .btn-secondary { background: #ddd; color: #333; text-decoration: none; display: inline-block; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9f9f5; color: #2d5016; font-weight: 600; }
        tr:hover { background: #fafafa; }
        
        .product-icon { font-size: 30px; }
        .badge { padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold; }
        .badge-offerta { background: #e74c3c; color: white; }
        .badge-nuovo { background: #27ae60; color: white; }
        .status-active { color: #27ae60; }
        .status-inactive { color: #999; }
        
        .actions a {
            display: inline-block;
            padding: 8px 15px;
            margin-right: 5px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }
        .actions .edit { background: #f4d03f; color: #2d5016; }
        .actions .delete { background: #e74c3c; color: white; }
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
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="prodotti.php" class="active">📦 Prodotti</a>
        <a href="categorie.php">📁 Categorie</a>
        <a href="slider.php">🖼️ Slider</a>
        <a href="fornitori.php">🏭 Fornitori</a>
        <a href="impostazioni.php">⚙️ Impostazioni</a>
    </nav>
    
    <main>
        <div class="page-header">
            <h2>📦 Gestione Prodotti</h2>
            <a href="prodotti.php?action=new" class="btn-new">➕ Nuovo Prodotto</a>
        </div>
        
        <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($edit || $showForm): ?>
        <!-- Form Modifica/Nuovo -->
        <div class="card">
            <h3 style="color: #2d5016; margin-bottom: 20px;"><?php echo $edit ? '✏️ Modifica Prodotto' : '➕ Nuovo Prodotto'; ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nome *</label>
                        <input type="text" name="nome" value="<?php echo $edit['nome'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Icona (emoji)</label>
                        <input type="text" name="icona" value="<?php echo $edit['icona'] ?? '🌿'; ?>" placeholder="🌿">
                    </div>
                    
                    <div class="form-group full">
                        <label>Descrizione</label>
                        <textarea name="descrizione"><?php echo $edit['descrizione'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Prezzo (€) *</label>
                        <input type="number" step="0.01" name="prezzo" value="<?php echo $edit['prezzo'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Prezzo Originale (€) - per sconti</label>
                        <input type="number" step="0.01" name="prezzo_originale" value="<?php echo $edit['prezzo_originale'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria_id">
                            <option value="">-- Seleziona --</option>
                            <?php foreach ($categorie as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($edit['categoria_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo $cat['icona'] . ' ' . $cat['nome']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Badge</label>
                        <select name="badge">
                            <option value="">Nessuno</option>
                            <option value="OFFERTA" <?php echo ($edit['badge'] ?? '') === 'OFFERTA' ? 'selected' : ''; ?>>OFFERTA</option>
                            <option value="NUOVO" <?php echo ($edit['badge'] ?? '') === 'NUOVO' ? 'selected' : ''; ?>>NUOVO</option>
                        </select>
                    </div>
                    
                    <div class="form-group full">
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" name="in_evidenza" <?php echo ($edit['in_evidenza'] ?? 0) ? 'checked' : ''; ?>>
                                ⭐ Mostra in Homepage
                            </label>
                            <label>
                                <input type="checkbox" name="attivo" <?php echo ($edit['attivo'] ?? 1) ? 'checked' : ''; ?>>
                                ✅ Attivo
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Salva</button>
                    <a href="prodotti.php" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- Lista Prodotti -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Prezzo</th>
                        <th>Badge</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodotti as $p): ?>
                    <tr>
                        <td class="product-icon"><?php echo $p['icona']; ?></td>
                        <td>
                            <strong><?php echo $p['nome']; ?></strong>
                            <?php if ($p['in_evidenza']): ?><br><small>⭐ In evidenza</small><?php endif; ?>
                        </td>
                        <td><?php echo $p['categoria_nome'] ?? '-'; ?></td>
                        <td>
                            <strong>€<?php echo number_format($p['prezzo'], 2); ?></strong>
                            <?php if ($p['prezzo_originale']): ?>
                            <br><small style="text-decoration: line-through; color: #999;">€<?php echo number_format($p['prezzo_originale'], 2); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['badge']): ?>
                            <span class="badge badge-<?php echo strtolower($p['badge']); ?>"><?php echo $p['badge']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="<?php echo $p['attivo'] ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $p['attivo'] ? '✅ Attivo' : '❌ Disattivo'; ?>
                        </td>
                        <td class="actions">
                            <a href="prodotti.php?edit=<?php echo $p['id']; ?>" class="edit">✏️ Modifica</a>
                            <a href="prodotti.php?delete=<?php echo $p['id']; ?>" class="delete" onclick="return confirm('Eliminare questo prodotto?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
