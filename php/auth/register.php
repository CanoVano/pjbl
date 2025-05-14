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

