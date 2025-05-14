<?php 
include './koneksi.php';
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
        echo "<meta http-equiv='refresh' content='2;url=landing.html'>";
    } else {
        $notif = '<div class="notif error">Username atau Password salah!</div>';
    }
}
?>