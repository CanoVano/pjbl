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
    die("Order ID not specified or invalid.");
}

// Fetch order details from the database
$order_query = mysqli_query($koneksi, "SELECT o.*, u.username, u.telepon FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = " . $order_id);

if (mysqli_num_rows($order_query) > 0) {
    $order = mysqli_fetch_assoc($order_query);

    // Fetch order items for this order
    $order_items_query = mysqli_query($koneksi, "SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = " . $order_id);
    
    // Group items by part
    $grouped_items = [];
    while($item = mysqli_fetch_assoc($order_items_query)) {
        $part = $item['part'] ?? '';
        if (!isset($grouped_items[$part])) {
            $grouped_items[$part] = [];
        }
        $grouped_items[$part][] = $item;
    }

    // Format product names for display
    $all_product_names = [];
    foreach ($grouped_items as $items) {
        foreach ($items as $item) {
            $all_product_names[] = $item['name'];
        }
    }
    
    $formatted_names = '';
    if (count($all_product_names) == 2) {
        $formatted_names = implode(' & ', $all_product_names);
    } else if (count($all_product_names) > 2) {
        $last_item = array_pop($all_product_names);
        $formatted_names = implode(', ', $all_product_names) . ' & ' . $last_item;
    } else {
        $formatted_names = $all_product_names[0];
    }

    // Order details
    $total_price = $order['total_price'];
    $status = $order['status'];
    $payment_method = "Tunai";
    $username = $order['username'];
    $telepon = $order['telepon'];
    $queue_number = $order['queue_number'];
    $order_time_formatted = date('H:i A d/m/Y', strtotime($order['order_time']));
    $pickup_time_formatted = date('H:i A d/m/Y', strtotime($order['pickup_time']));
    $address = $order['address'];

} else {
    die("Order not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
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
            position: relative;
        }
        .wrapper-detail::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #000;
            border-radius: 10px 0 0 10px;
        }
        .wrapper-detail::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #000;
            border-radius: 0 10px 10px 0;
        }
        .wrapper-detail .top-line {
            position: absolute;
            top: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #000;
            border-radius: 10px 10px 0 0;
        }
        .wrapper-detail .bottom-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: #000;
            border-radius: 0 0 10px 10px;
        }
        header.detail-header {
            width: 100%;
            background: #f5ac0a;
            border-radius: 10px 10px 0 0;
            height: 56px;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
            margin-top: 2px;
        }
        header.detail-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #000;
            border-radius: 10px 0 0 0;
        }
        header.detail-header::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #000;
            border-radius: 0 10px 0 0;
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

        .part-section {
            margin-bottom: 20px;
            padding: 15px;
        }

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

        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }

        .detail-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 20px 0;
            color: #333;
            padding: 0 20px;
        }

        @media (max-width: 900px) {
            .bottom-info {
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
        <div class="top-line"></div>
        <div class="bottom-line"></div>
        <header class="detail-header">
            <button class="back-btn" aria-label="Back" onclick="window.location.href='menu.php'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1>Detail Pesanan</h1>
        </header>

        <!-- Products Section -->
        <div class="order-details">
            <h2 class="detail-title"><?php echo $formatted_names; ?></h2>
            <?php foreach ($grouped_items as $part => $items): ?>
                <div class="part-section">
                    
                    <?php foreach ($items as $item): ?>
                        <div class="product-order">
                            <img src="../images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                            <div class="product-info">
                                <h2 class="store-name"><i class="fas fa-cube mr-2"></i> Hexagon Mart</h2>
                                <p class="product-name"><?php echo $item['name']; ?></p>
                                <p class="product-price">Rp<?php echo number_format($item['price_at_order'], 0, ',', '.'); ?></p>
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
                <div class="summary-row">
                    <span>Status</span>
                    <span><?php echo $status; ?></span>
                </div>
                <div class="summary-row">
                    <span>Pembayaran</span>
                    <span><i class="fas fa-money-bill-wave"></i> <?php echo $payment_method; ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp<?php echo number_format($total_price, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>

        <!-- Bottom Info Section -->
        <div class="bottom-info">
            <div class="user-info">
                <span class="label">Username:</span>
                <span><?php echo $username; ?></span>
                <span><?php echo $telepon; ?></span>
                <span class="label">No Antrian:</span>
                <ul>
                    <li><?php echo $queue_number; ?></li>
                </ul>
            </div>
            <div class="time-info">
                <span class="label">Waktu Pemesanan:</span>
                <span><?php echo $order_time_formatted; ?></span>
                <span class="label">Waktu Pengambilan:</span>
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