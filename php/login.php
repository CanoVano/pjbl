<?php 
include 'koneksi.php';
session_start();

$notif = '';
$username_value = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $username_value = htmlspecialchars($username); // Simpan untuk ditampilkan kembali

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $notif = '<div class="notif success">Login berhasil! Mengalihkan...</div>';
        echo "<meta http-equiv='refresh' content='2;url=landing.php'>";
    } else {
        $notif = '<div class="notif error">Username atau Password salah!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hexagon Mart</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .notif {
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="login-container">
            <img src="../images/log.png" alt="Hexagon Mart" class="logo">
            <h2>LOGIN</h2>

            <!-- Notifikasi -->
            <?php if ($notif): ?>
                <?= $notif ?>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required value="<?= $username_value ?>">
                    <span class="icon">👤</span>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="eye-icon" onclick="togglePassword()">👁️</span>
                </div>
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <button type="submit" class="login-btn">LOGIN</button>
                <p class="register-link">Tidak mempunyai akun? <a href="../php/register.php">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const icon = document.querySelector(".eye-icon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.textContent = "🙈"; // Ganti ikon menjadi mata tertutup
            } else {
                passwordField.type = "password";
                icon.textContent = "👁️"; // Ganti ikon menjadi mata terbuka
            }
        }
    </script>
</body>
</html>
