<?php
header('Content-Type: application/json');
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Email tidak valid!']);
        exit;
    }

    // Cek apakah email sudah ada di database
    $cek = mysqli_query($koneksi, "SELECT * FROM newsletter_subscribers WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar!']);
        exit;
    }

    // Simpan email ke database
    $insert = mysqli_query($koneksi, "INSERT INTO newsletter_subscribers (email) VALUES ('$email')");
    if ($insert) {
        echo json_encode(['success' => true, 'message' => 'Terima kasih sudah berlangganan!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendaftar, coba lagi!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid!']);
}
?>