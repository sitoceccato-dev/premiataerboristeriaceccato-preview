<?php
/**
 * Admin Login
 */
session_start();

// Credenziali default (cambia in produzione!)
$admin_user = 'admin';
$admin_pass = 'erboristeria2026';

$error = '';

if ($_POST) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $admin_user;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Credenziali non valide';
    }
}

if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged']) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Erboristeria Ceccato</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2d5016 0%, #4a7c23 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 400px;
            text-align: center;
        }
        .logo { font-size: 60px; margin-bottom: 20px; }
        h1 { color: #2d5016; margin-bottom: 10px; font-size: 24px; }
        .subtitle { color: #888; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; color: #2d5016; font-weight: 600; }
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
        }
        .form-group input:focus { outline: none; border-color: #4a7c23; }
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4a7c23, #5a9129);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-login:hover { transform: translateY(-2px); }
        .error { background: #ffe6e6; color: #c00; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .back-link { margin-top: 20px; }
        .back-link a { color: #4a7c23; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">🌿</div>
        <h1>Pannello Admin</h1>
        <p class="subtitle">Erboristeria Ceccato</p>
        
        <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="admin">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-login">🔐 Accedi</button>
        </form>
        
        <div class="back-link">
            <a href="../index.php">← Torna al sito</a>
        </div>
    </div>
</body>
</html>
