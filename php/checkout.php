<?php
session_start();
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get cart data from URL
$cart_data = isset($_GET['cart_data']) ? json_decode(html_entity_decode($_GET['cart_data']), true) : [];
$total_price = isset($_GET['total_price']) ? (float)$_GET['total_price'] : 0;

// Handle direct purchase (when only product ID and quantity are provided)
if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = (int)$_GET['id'];
    $quantity = (int)$_GET['quantity'];
    
    // Fetch product data from database
    $product_query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = $product_id");
    if ($product = mysqli_fetch_assoc($product_query)) {
        $cart_data = [[
            'id' => $product['id'],
            'name' => $product['name'],
            'image' => $product['image'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'part' => 'Dada' // Default part
        ]];
        $total_price = $product['price'] * $quantity;
    }
}

// Get user data from session
$user = $_SESSION['user'];

// Process order when form is submitted
$success_message = '';
$order_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Get necessary data for database insertion
    $user_id = $user['id'];
    
    // Use user's address from session if available, otherwise use static one
    $address = isset($user['alamat']) ? $user['alamat'] : "Jl. Pandanaran 2, Mugasari, Kec. Semarang Sel., Kota Semarang, Jawa Tengah. (50249)";

    // Generate queue number
    $queue_number = rand(1, 50);

    // Get current time and estimated pickup time (30 minutes from now)
    $order_time = date('Y-m-d H:i:s');
    $pickup_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    // Start database transaction
    mysqli_begin_transaction($koneksi);
    $success = true;

    try {
        // 1. Insert into orders table
        $insert_order_query = "INSERT INTO orders (user_id, order_time, pickup_time, total_price, status, address, queue_number)
                               VALUES ($user_id, '$order_time', '$pickup_time', $total_price, 'Proses', '$address', $queue_number)";

        if (mysqli_query($koneksi, $insert_order_query)) {
            $order_id = mysqli_insert_id($koneksi);

            // 2. Insert all cart items into order_items table
            foreach ($cart_data as $item) {
                $product_id = $item['id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $part = $item['part'] ?? 'Dada'; // Default to 'Dada' if part is not set

                $insert_item_query = mysqli_prepare($koneksi, "INSERT INTO order_items (order_id, product_id, quantity, price_at_order, part) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($insert_item_query, "iiids", $order_id, $product_id, $quantity, $price, $part);

                if (!mysqli_stmt_execute($insert_item_query)) {
                    $success = false;
                    $success_message = "Gagal menyimpan detail item: " . mysqli_error($koneksi);
                    break;
                }
                mysqli_stmt_close($insert_item_query);
            }

            // Clear the cart after successful order
            if ($success) {
                mysqli_query($koneksi, "DELETE FROM user_carts WHERE user_id = $user_id");
                unset($_SESSION['cart']);
                unset($_SESSION['voucher_diklaim']);
            }
        } else {
            $success = false;
            $success_message = "Gagal menyimpan pesanan: " . mysqli_error($koneksi);
        }

        // Commit or rollback transaction
        if ($success) {
            mysqli_commit($koneksi);
            $success_message = "Pembelian sukses!";
        } else {
            mysqli_rollback($koneksi);
            if(empty($success_message)) {
                $success_message = "Gagal membuat pesanan. Silakan coba lagi.";
            }
            $order_id = null;
        }

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $success_message = "Terjadi kesalahan: " . $e->getMessage();
        $order_id = null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hexagon Mart - Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
    <div id="app">
        <div class="container">
            <!-- Order Summary Header -->
            <div class="order-header">
                <button onclick="window.location.href='cart.php'" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="header-title">Ringkasan Pesanan</h1>
                <div class="spacer"></div>
            </div>

            <!-- User Info -->
            <div class="user-info">
                <span><?php echo $user['username']; ?></span>
                <span><?php echo $user['telepon']; ?></span>
            </div>

            <!-- Product Order Details -->
            <div class="order-details">
                <?php 
                // Group items by part
                $grouped_items = [];
                foreach ($cart_data as $item) {
                    $part = $item['part'] ?? ''; // Default to 'Dada' if part is not set
                    if (!isset($grouped_items[$part])) {
                        $grouped_items[$part] = [];
                    }
                    $grouped_items[$part][] = $item;
                }

                // Display items grouped by part
                foreach ($grouped_items as $part => $items): 
                ?>
                    <div class="part-section">
                        <h3 class="part-title"><?php echo $part; ?></h3>
                        <?php foreach ($items as $item): ?>
                        <div class="product-order">
                            <img src="../images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                            <div class="product-info">
                                <h2 class="store-name"><i class="fas fa-cube mr-2"></i> Hexagon Mart</h2>
                                <p class="product-name"><?php echo $item['name']; ?></p>
                                <p class="product-price">Rp<?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="quantity-badge">
                                <?php echo $item['quantity']; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3 class="summary-title">Ringkasan Pesanan</h3>
                    <div class="summary-row">
                        <span class="summary-label">Waktu Pengambilan</span>
                        <span class="summary-value"><?php echo date('H:i', strtotime('+30 minutes')); ?> WIB</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">Rp<?php echo number_format($total_price, 0, ',', '.'); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>Rp<?php echo number_format($total_price, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="payment-method">
                <div>
                    <i class="fas fa-money-bill-wave payment-icon"></i>
                    <span class="payment-label">Pembayaran</span>
                    <span class="payment-type">Tunai</span>
                </div>
            </div>

            <!-- Order Total -->
            <div class="order-total">
                <span>Total (<?php echo count($cart_data); ?> item<?php echo count($cart_data) > 1 ? 's' : ''; ?>)</span>
                <span class="font-semibold">Rp<?php echo number_format($total_price, 0, ',', '.'); ?></span>
            </div>

            <!-- Place Order Button -->
            <form method="POST">
                <button type="submit" name="place_order" class="place-order-btn">
                    Buat Pesanan
                </button>
            </form>

            <!-- Success/Error Message and Detail Button -->
            <?php if ($success_message): ?>
                <div class="success-message" style="background-color: <?php echo ($order_id !== null) ? '#10b981' : '#f44336'; ?>;">
                    <?php echo $success_message; ?>
                </div>
                <?php if ($order_id !== null): ?>
                    <div style="text-align:center; margin-top: 16px;">
                        <a href="detail_pesanan.php?order_id=<?php echo $order_id; ?>" class="place-order-btn" style="background:#f5ac0a;color:#222;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:600;">Lihat Detail Pesanan</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>

    <style>
        

        .part-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            padding-left: 10px;
            border-left: 4px solid #F6AB0E;
        }

        .product-order {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            margin-left: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .product-info {
            flex-grow: 1;
        }

        .store-name {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .product-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 14px;
            color: #333;
        }

        .quantity-badge {
            background: #e9f7ef;
            color: #155724;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</body>
</html>