<?php
include 'koneksi.php';

$notif = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($koneksi, $_POST['fullname']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $notif = '<div class="notif error">Username atau Email sudah digunakan!</div>';
    } else {
        $simpan = mysqli_query($koneksi, "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$password')");
        if ($simpan) {
            $notif = '<div class="notif success">Register berhasil! Mengalihkan ke login...</div>';
            echo "<meta http-equiv='refresh' content='2;url=login.php'>";
        } else {
            $notif = '<div class="notif error">Register gagal! Silakan coba lagi.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hexagon Mart</title>
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
            <img src="../images/logo.png" alt="Hexagon Mart" class="logo">
            <h2>REGISTER</h2>

            <!-- Notifikasi -->
            <?php if ($notif): ?>
                <?= $notif ?>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <span class="icon">🧑</span>
                </div>
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                    <span class="icon">👤</span>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <span class="icon">✉</span>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="eye-icon" onclick="togglePassword()">👁️</span>
                </div>
                <button type="submit" class="login-btn">REGISTER</button>
                <p class="register-link">Sudah punya akun? <a href="../php/login.php">Login</a></p>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const icon = document.querySelector(".eye-icon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.textContent = "🙈";
            } else {
                passwordField.type = "password";
                icon.textContent = "👁️";
            }
        }
    </script>
</body>
</html>
