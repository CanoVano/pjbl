<?php
session_start();
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// If no order ID, redirect to menu or an error page
if ($order_id <= 0) {
    // Or show an error message
    die("Order ID not specified or invalid.");
    // header("Location: menu.php");
    // exit();
}

// Fetch order details from the database
// Join with users table to get username and phone
$order_query = mysqli_query($koneksi, "SELECT o.*, u.username, u.telepon FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = " . $order_id);

if (mysqli_num_rows($order_query) > 0) {
    $order = mysqli_fetch_assoc($order_query);

    // Fetch order items for this order
    // Join with products table to get product name and image
    $order_items_query = mysqli_query($koneksi, "SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = " . $order_id);
    $order_items = [];
    while($item = mysqli_fetch_assoc($order_items_query)) {
        $order_items[] = $item;
    }

    // --- Assigning data to variables for display ---
    // Adapt for single item display as per your current HTML structure
    $first_item = !empty($order_items) ? $order_items[0] : null;

    if (!$first_item) {
        die("No items found for this order.");
    }

    $product_name = $first_item['name'];
    $product_image = $first_item['image'];
    $quantity = $first_item['quantity'];
    $price_per_item = $first_item['price_at_order']; // Use price at time of order
    $total_price = $order['total_price']; // Use total price from orders table
    $status = $order['status'];
    // Payment method is still hardcoded as "Tunai" in your current structure
    $payment_method = "Tunai";
    $username = $order['username'];
    $telepon = $order['telepon'];
    $queue_number = $order['queue_number'];
    // Format dates from database DATETIME format to your desired format
    $order_time_formatted = date('H:i A d/m/Y', strtotime($order['order_time']));
    $pickup_time_formatted = date('H:i A d/m/Y', strtotime($order['pickup_time']));
    $address = $order['address']; // Use address from orders table


} else {
    // Order not found
    die("Order not found.");
    // header("Location: menu.php");
    // exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #ede9e8;
            min-height: 100vh;
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .wrapper-detail {
            background: #fff;
            max-width: 900px;
            margin: 32px auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding-bottom: 32px;
        }
        header.detail-header {
            width: 100%;
            background: #f5ac0a;
            border-radius: 10px 10px 0 0;
            height: 56px;
            display: flex;
            align-items: center;
            position: relative;
        }
        header.detail-header h1 {
            flex: 1;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #222;
            margin: 0;
        }
        header.detail-header .back-btn {
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #222;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 2;
        }

        h2.detail-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 800;
            margin: 32px 0 24px 0;
            color: #111;
        }

        .main-detail {
            width: 100%;
            max-width: 700px;
            margin: 0 auto 32px auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 40px;
        }
        .left-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 220px;
        }
        .left-detail .logo-row { display: none; }
        .left-detail img.product-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            /* float: left; */
            /* border-radius: 50%; */
            /* border: 4px solid #eee; */
        }
        .right-detail {
            display: flex;
            flex-direction: column;
            gap: 16px;
            min-width: 260px;
            justify-content: center;
        }
        .info-box {
            background: #4aa8e0;
            color: #000;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 0.375rem;
            min-width: 320px;
            max-width: 350px;
        }
        .info-box span:first-child {
            font-weight: 600;
        }
        .info-box.price {
            background: #2a8ad6;
            color: #000;
        }
        .info-box.price span:last-child {
            font-weight: 700;
            text-align: right;
            width: 120px;
            display: inline-block;
        }
        .info-box .fa-money-bill-wave {
            font-size: 1.125rem;
        }

        .bottom-info {
            width: 90%;
            margin: 0 auto;
            display: flex;
            background: #4aa8e0;
            border-radius: 0.375rem;
            margin-top: 32px;
            color: #000;
            box-sizing: border-box;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .bottom-info > div {
            flex: 1;
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 1rem;
            min-width: 0;
        }
        .bottom-info > div:not(:last-child) {
            border-right: 3px solid #f5ac0a;
        }
        .user-info span,
        .user-info ul {
            font-size: 0.95rem;
            font-weight: 400;
            margin-bottom: 0.25rem;
        }
        .user-info ul {
            list-style: disc inside;
            font-weight: 700;
            margin-left: 1.25rem;
            margin-top: -0.5rem;
        }
        .user-info .label {
            font-weight: 700;
        }
        .time-info .label {
            font-weight: 700;
        }
        .address-info span {
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .address-info .fa-paper-plane {
            font-size: 1.25rem;
        }
        @media (max-width: 900px) {
            .main-detail, .bottom-info {
                flex-direction: column;
                gap: 1.5rem;
                align-items: center;
            }
            .bottom-info > div:not(:last-child) {
                border-right: none;
                border-bottom: 3px solid #f5ac0a;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper-detail">
        <header class="detail-header">
            <button class="back-btn" aria-label="Back" onclick="window.location.href='menu.php'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1>Detail Pembayaran</h1>
        </header>
        <h2 class="detail-title"><?php echo $product_name; ?></h2>
        <div class="main-detail">
            <div class="left-detail">
                <!-- Logo row removed as requested -->
                <img src="../images/<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" class="product-img" />
            </div>
            <div class="right-detail">
                <div class="info-box">
                    <span>Jumlah :</span>
                    <span><?php echo $quantity; ?></span>
                </div>
                <div class="info-box price">
                    <span>Harga Satuan :</span>
                    <span>Rp<?php echo number_format($price_per_item, 0, ',', '.'); ?></span>
                </div>
                <div class="info-box">
                    <span>Status :</span>
                    <span><?php echo $status; ?></span>
                </div>
                <div class="info-box">
                    <span>Pembayaran :</span>
                    <span style="display:flex;align-items:center;gap:6px;"><i class="fas fa-money-bill-wave"></i> <?php echo $payment_method; ?></span>
                </div>
                 <div class="info-box price">
                    <span>Total Harga :</span>
                    <span>Rp<?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>
        <div class="bottom-info">
            <div class="user-info">
                <span class="label">username :</span>
                <span><?php echo $username; ?></span>
                <span><?php echo $telepon; ?></span>
                <span class="label">No Antrian :</span>
                <ul>
                    <li><?php echo $queue_number; ?></li>
                </ul>
            </div>
            <div class="time-info">
                <span class="label">Waktu Pemesanan :</span>
                <span><?php echo $order_time_formatted; ?></span>
                <span class="label">Waktu Pengambilan :</span>
                <span><?php echo $pickup_time_formatted; ?></span>
            </div>
            <div class="address-info">
                <span>
                    <i class="fas fa-paper-plane"></i>
                    <?php echo $address; ?>
                </span>
            </div>
        </div>
    </div>
</body>
</html>