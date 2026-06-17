<?php
require_once 'autoload.php';

use App\Models\Admin;
use App\Models\RegularUser;
use App\Services\AuthService;

// PHP Session'ı başlatıyoruz
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authService = new AuthService();
$message = "";
$messageClass = "";

// Test Kullanıcıları oluşturuluyor (Hocanın dokümanındaki veriler)
$adminUser = new Admin("Alice", "alice@example.com", "admin123");
$regularUser = new RegularUser("Bob", "bob@example.com", "user123");

// Giriş Formu Post Edildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Hangi kullanıcı nesnesiyle eşleştiğini bul
        if ($email === $adminUser->getEmail()) {
            $selectedUser = $adminUser;
        } elseif ($email === $regularUser->getEmail()) {
            $selectedUser = $regularUser;
        } else {
            $selectedUser = null;
        }

        if ($selectedUser) {
            // Kimlik doğrulamayı çalıştırıyoruz
            $result = $authService->authenticate($selectedUser, $email, $password);
            
            if ($result === "User logged in successfully.") {
                $_SESSION['user_name'] = $selectedUser->getName();
                $_SESSION['user_email'] = $selectedUser->getEmail();
                $_SESSION['user_role'] = $selectedUser->userRole();
                
                $message = "Giriş Başarılı!";
                $messageClass = "success";
            } else {
                $message = "Hatalı Şifre!";
                $messageClass = "error";
            }
        } else {
            $message = "Kullanıcı Bulunamadı!";
            $messageClass = "error";
        }
    }

    // Çıkış Butonuna Basıldığında
    if ($_POST['action'] === 'logout') {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>User Management System</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 360px; }
        h2 { margin-top: 0; color: #1f2937; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; color: #4b5563; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 11px; background: #2563eb; border: none; color: white; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #1d4ed8; }
        .btn-logout { background: #dc2626; margin-top: 10px; }
        .btn-logout:hover { background: #b91c1c; }
        .alert { padding: 10px; border-radius: 6px; text-align: center; margin-bottom: 15px; font-size: 14px; font-weight: bold; }
        .success { background: #d1fae5; color: #065f46; }
        .error { background: #fee2e2; color: #991b1b; }
        .badge { display: inline-block; padding: 4px 8px; background: #e5e7eb; border-radius: 4px; font-size: 12px; font-weight: bold; color: #374151; }
        .info { font-size: 12px; color: #6b7280; background: #f9fafb; padding: 10px; border-radius: 6px; margin-top: 20px; line-height: 1.5; }
    </style>
</head>
<body>

<div class="card">
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $messageClass; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['user_email'])): ?>
        <h2>Giriş Yap</h2>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label>E-posta Adresi:</label>
                <input type="email" name="email" required placeholder="alice@example.com">
            </div>
            <div class="form-group">
                <label>Şifre:</label>
                <input type="password" name="password" required placeholder="admin123">
            </div>
            <button type="submit">Giriş Yap</button>
        </form>

        <div class="info">
            <strong>Test Hesapları:</strong><br>
            • Admin: alice@example.com / admin123<br>
            • User: bob@example.com / user123
        </div>

    <?php else: ?>
        <h2>Kullanıcı Profili</h2>
        <p><strong>İsim:</strong> <?php echo $_SESSION['user_name']; ?></p>
        <p><strong>E-posta:</strong> <?php echo $_SESSION['user_email']; ?></p>
        <p><strong>Rol:</strong> <span class="badge"><?php echo $_SESSION['user_role']; ?></span></p>
        
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="btn-logout">Çıkış Yap</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>