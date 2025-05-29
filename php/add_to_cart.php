<?php
session_start();
include 'koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user']['id'];

    // Check if product already exists in user's cart
    $check_query = mysqli_query($koneksi, "SELECT * FROM user_carts WHERE user_id = $user_id AND product_id = $product_id");
    
    if (mysqli_num_rows($check_query) > 0) {
        // Update quantity if product exists
        mysqli_query($koneksi, "UPDATE user_carts SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Insert new product if it doesn't exist
        mysqli_query($koneksi, "INSERT INTO user_carts (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }

    // Update session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Get total cart count
    $cart_count = array_sum($_SESSION['cart']);

    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart',
        'cart_count' => $cart_count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}