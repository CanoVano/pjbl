    <?php
    session_start();
    include 'koneksi.php';

    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }

    // Get parameters from URL
    $product_id = isset($_GET['id']) ? $_GET['id'] : 1;
    $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;
    $part = isset($_GET['part']) ? $_GET['part'] : 'Dada';

    // Fetch product data from database
    $product_query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = $product_id");
    if (mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
    } else {
        header("Location: menu.php");
        exit();
    }

    $total = $product['price'] * $quantity;

    // Get user data from session
    $user = $_SESSION['user'];

    // Process order
    $success_message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
        // Here you would process the order in your database
        // ...
        
        // For now, just set a success message
        $success_message = "Pembelian sukses: 
        Produk: {$product['name']} ($part)
        Jumlah: $quantity
        Total: Rp" . number_format($total, 0, ',', '.');
        
        // In a real application, you might want to clear the cart or redirect to a thank you page
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
                    <button onclick="window.location.href='detail_produk.php?id=<?php echo $product_id; ?>'" class="back-button">
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
                    <!-- Product Info -->
                    <div class="product-order">
                        <img src="../images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                        <div class="product-info">
                            <h2 class="store-name"><i class="fas fa-cube mr-2"></i> Hexagon Mart</h2>
                            <p class="product-name"><?php echo $product['name']; ?> - <?php echo $part; ?></p>
                            <p class="product-price">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="quantity-badge">
                            <?php echo $quantity; ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h3 class="summary-title">Ringkasan Pesanan</h3>
                        <div class="summary-row">
                            <span class="summary-label">Waktu Pengambilan</span>
                            <span class="summary-value">09.30 WIB</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">Rp<?php echo number_format($total, 0, ',', '.'); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>Rp<?php echo number_format($total, 0, ',', '.'); ?></span>
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
                    <span>Total (<?php echo $quantity; ?> item<?php echo $quantity > 1 ? 's' : ''; ?>)</span>
                    <span class="font-semibold">Rp<?php echo number_format($total, 0, ',', '.'); ?></span>
                </div>

                <!-- Place Order Button -->
                <form method="POST">
                    <button type="submit" name="place_order" class="place-order-btn">
                        Buat Pesanan
                    </button>
                </form>

                <!-- Success Message -->
                <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo $success_message; ?>
                </div>
                <script>
                    // Redirect back to menu after 3 seconds
                    setTimeout(function() {
                        window.location.href = 'menu.php';
                    }, 3000);
                </script>
                <?php endif; ?>
            </div>
        </div>
    </body>
    </html>