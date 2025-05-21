<?php
session_start();
include 'koneksi.php';

// Get parameters from URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 1;
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : 1;
$part = isset($_GET['part']) ? $_GET['part'] : 'Dada';

// Here you would fetch product data from the database based on $product_id
// For now, we'll use hardcoded data
$products = [
    1 => ["name" => "Ayam Goreng Sambal", "price" => 15000, "image" => "../images/yu.png"],
    2 => ["name" => "Ayam Goreng", "price" => 16000, "image" => "../images/yu.png"],
    3 => ["name" => "Mie Goreng", "price" => 18000, "image" => "../images/yu.png"],
    4 => ["name" => "Mie Kuah", "price" => 14000, "image" => "../images/yu.png"],
    5 => ["name" => "Sayur Kuah", "price" => 28000, "image" => "../images/yu.png"],
    6 => ["name" => "Nasi Goreng", "price" => 23000, "image" => "../images/yu.png"],
    7 => ["name" => "Kwetiau", "price" => 20000, "image" => "../images/yu.png"]
];

$product = $products[$product_id] ?? $products[1]; // Default to first product if ID not found
$total = $product['price'] * $quantity;

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
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            color: #1a1a1a;
        }
        
        /* Layout */
        #app {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Order Summary Header */
        .order-header {
            background-color: #f2a900;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        .back-button {
            background: none;
            border: none;
            color: black;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .header-title {
            flex: 1;
            text-align: center;
            font-weight: 600;
            color: black;
            font-size: 1.125rem;
        }
        
        .spacer {
            width: 2rem;
        }
        
        /* User Info Section */
        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e5e5e5;
            border-top: 0;
            border-radius: 0 0 0.375rem 0.375rem;
            font-size: 1.125rem;
        }
        
        /* Product Order Details */
        .order-details {
            background-color: #1ea1ce;
            border-radius: 0.375rem;
            margin: 0 1rem;
            color: black;
            margin-top: 1.5rem;
        }
        
        .product-order {
            display: flex;
            padding: 1rem;
        }
        
        @media (min-width: 768px) {
            .order-details {
                display: flex;
                flex-direction: row;
            }
            
            .product-order {
                flex: 1;
                padding: 1.5rem;
            }
        }
        
        .product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        
        .product-info {
            margin-left: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .store-name {
            font-weight: 700;
            font-size: 1.125rem;
            margin-bottom: 0.25rem;
        }
        
        .product-name {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-weight: 800;
            font-size: 1.25rem;
        }
        
        .quantity-badge {
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(30, 161, 206, 0.4);
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            color: black;
            font-weight: 600;
            font-size: 1.125rem;
        }
        
        /* Order Summary */
        .order-summary {
            padding: 1rem;
            color: black;
        }
        
        @media (min-width: 768px) {
            .order-summary {
                flex: 1;
                padding: 1.5rem;
            }
        }
        
        .summary-title {
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }
        
        .summary-row:last-child {
            margin-bottom: 0;
        }
        
        .summary-row.total {
            font-weight: 800;
            font-size: 1.125rem;
            margin-top: 0.75rem;
        }
        
        .summary-label {
            font-weight: normal;
        }
        
        .summary-value {
            font-weight: 600;
        }
        
        /* Payment Method */
        .payment-method {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 0.375rem;
            margin: 1.5rem 1rem 0 1rem;
            font-size: 1.125rem;
        }
        
        .payment-icon {
            margin-right: 0.5rem;
            font-size: 1.25rem;
        }
        
        .payment-label {
            margin-right: 0.5rem;
        }
        
        .payment-type {
            font-weight: 600;
        }
        
        /* Order Total */
        .order-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            font-size: 1.125rem;
            margin: 0 1rem;
        }
        
        /* Place Order Button */
        .place-order-btn {
            margin: 0 1rem 1rem 1rem;
            background-color: #e6c94a;
            color: black;
            font-weight: 800;
            font-size: 1.25rem;
            padding: 1rem;
            border-radius: 0.375rem;
            width: calc(100% - 2rem);
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .place-order-btn:hover {
            background-color: #d4b83e;
        }
        
        /* Success Message */
        .success-message {
            background-color: #10b981;
            color: white;
            padding: 1rem;
            border-radius: 0.375rem;
            margin: 1rem;
            white-space: pre-line;
        }
    </style>
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
                <span>minex Fz</span>
                <span>(+62)86**07</span>
            </div>

            <!-- Product Order Details -->
            <div class="order-details">
                <!-- Product Info -->
                <div class="product-order">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
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