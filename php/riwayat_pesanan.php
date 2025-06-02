<?php
session_start();
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Fetch orders for the logged-in user
$orders_query = mysqli_query($koneksi, "SELECT * FROM orders WHERE user_id = " . $user_id . " ORDER BY order_time DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
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
        .wrapper {
            background: #fff;
            max-width: 800px;
            margin: 32px auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding-bottom: 32px;
            position: relative;
            padding: 20px;
        }
         header.history-header {
            width: 100%;
            background: #f5ac0a;
            border-radius: 10px 10px 0 0;
            height: 56px;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
             margin-top: -20px;
             margin-left: -20px;
             margin-right: -20px;
             padding: 0 20px;
        }
        header.history-header h1 {
            flex: 1;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #222;
            margin: 0;
        }
        header.history-header .back-btn {
            background: none;
            border: none;
            color: #222;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 2;
        }
        .order-card {
            background: #4aa8e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #000;
        }
        .store-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .store-name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .detail-btn {
             background: #f5ac0a;
            color: #222;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
             font-size: 0.9rem;
        }
        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
             padding-bottom: 10px;
             border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .product-item:last-child {
             margin-bottom: 0;
             padding-bottom: 0;
             border-bottom: none;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }
        .product-details {
            flex-grow: 1;
        }
        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .product-price-qty {
            font-size: 0.9rem;
        }
        .order-summary {
            text-align: right;
            margin-top: 15px;
             font-size: 0.95rem;
        }
        .order-total {
            font-weight: 600;
            margin-top: 5px;
        }
        .action-buttons {
            text-align: right;
            margin-top: 15px;
        }
        .action-button {
            background: #f5ac0a;
            color: #222;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
             font-size: 0.9rem;
        }
         .no-orders {
            text-align: center;
            font-size: 1.1rem;
            color: #666;
            margin-top: 50px;
        }

    </style>
</head>
<body>
    <div class="wrapper">
        <header class="history-header">
             <button class="back-btn" aria-label="Back" onclick="window.location.href='menu.php'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1>Riwayat Pesanan</h1>
             <div style="width: 1.5rem;"></div> <!-- Spacer -->
        </header>

        <?php if (mysqli_num_rows($orders_query) > 0): ?>
            <?php while($order = mysqli_fetch_assoc($orders_query)): ?>
                <div class="order-card">
                    <div class="store-info">
                        <span class="store-name"><i class="fas fa-cube"></i> Hexagon Mart</span>
                        <a href="detail_pesanan.php?order_id=<?php echo $order['id']; ?>" class="detail-btn">Detail</a>
                    </div>

                    <?php
                    // Fetch items for this order
                    $order_items_query = mysqli_query($koneksi, "SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = " . $order['id']);
                    $total_products_in_order = 0;
                    while($item = mysqli_fetch_assoc($order_items_query)): 
                        $total_products_in_order += $item['quantity'];
                    ?>
                        <div class="product-item">
                            <img src="../images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                            <div class="product-details">
                                <div class="product-name"><?php echo $item['name']; ?></div>
                                <div class="product-price-qty">Rp<?php echo number_format($item['price_at_order'], 0, ',', '.'); ?> x <?php echo $item['quantity']; ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <div class="order-summary">
                        <div>Total <?php echo $total_products_in_order; ?> produk: Rp<?php echo number_format($order['total_price'], 0, ',', '.'); ?></div>
                    </div>

                    <div class="action-buttons">
                        <button class="action-button">Beli lagi</button>
                        <button class="action-button">Komentar</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-orders">Belum ada riwayat pesanan.</p>
        <?php endif; ?>
    </div>
</body>
</html> 