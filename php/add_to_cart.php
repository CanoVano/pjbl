<?php
session_start();
include 'koneksi.php'; // Pastikan file ini benar dan koneksi menggunakan variabel $koneksi

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='../login.php';</script>";
    exit;
}

// Cek apakah product_id dikirim lewat POST
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Amankan input
    $user_id = $_SESSION['user']['id']; // Pastikan 'id' sesuai dengan struktur $_SESSION['user']

    // Cek apakah produk sudah ada di cart
    $check = mysqli_query($koneksi, "SELECT * FROM cart WHERE user_id=$user_id AND product_id=$product_id");

    if (mysqli_num_rows($check) > 0) {
        // Jika sudah ada, tambahkan quantity-nya
        mysqli_query($koneksi, "UPDATE cart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$product_id");
    } else {
        // Jika belum ada, insert ke cart
        mysqli_query($koneksi, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }

    header("Location: ./landing.php");
    exit;
} else {
    echo "<script>alert('Produk tidak ditemukan.'); window.location.href='./landing.php';</script>";
    exit;
}