<?php
session_start();
include 'koneksi.php';

// Get product ID from the URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 1; // Default to 1 if not specified

// Fetch product data from the database based on $product_id
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($koneksi, $query);

// If product found in database, use it
if (mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    // Fallback to default products if not found in database
    $products = [
        1 => ["name" => "Ayam Goreng Sambal", "price" => 15000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari ayam pilihan berkualitas tinggi dari peternakan terbaik, diolah secara higienis dan disukai oleh banyak orang."],
        2 => ["name" => "Ayam Goreng", "price" => 16000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari ayam pilihan berkualitas tinggi dari peternakan terbaik, diolah secara higienis dan disukai oleh banyak orang."],
        3 => ["name" => "Mie Goreng", "price" => 18000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari bahan-bahan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang."],
        4 => ["name" => "Mie Kuah", "price" => 14000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari bahan-bahan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang."],
        5 => ["name" => "Sayur Kuah", "price" => 28000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari sayuran segar berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang."],
        6 => ["name" => "Nasi Goreng", "price" => 23000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari beras berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang."],
        7 => ["name" => "Kwetiau", "price" => 20000, "image" => "../images/yu.png", "description" => "Produk ini dibuat dari bahan-bahan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang."]
    ];

    $product = $products[$product_id] ?? $products[1]; // Default to first product if ID not found
}

// Add to cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // If session cart doesn't exist, create a new array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already exists in cart, increase the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $_POST['quantity']; // Increase by selected quantity
    } else {
        $_SESSION['cart'][$product_id] = $_POST['quantity']; // Initialize with selected quantity
    }

    // Redirect to prevent form resubmission
    header("Location: detail_produk.php?id=" . $product_id . "&added=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hexagon Mart - Detail Produk</title>
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
            gap: 2.5rem;
        }
        
        /* Back Button */
        .back-button {
            position: fixed;
            top: 1rem;
            left: 2.5rem;
            z-index: 50;
            background-color: #f2a900;
            color: #000;
            border-radius: 50%;
            padding: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        
        .back-button:hover {
            background-color: #ffbb33;
        }
        
        /* Product Section */
        .product-section {
            display: flex;
            flex-direction: column;
            margin-top: 2rem;
        }
        
        @media (min-width: 768px) {
            .product-section {
                flex-direction: row;
                gap: 1.5rem;
            }
        }
        
        /* Product Image */
        .product-image {
            flex-shrink: 0;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .product-image {
                width: 18rem;
            }
        }
        
        .product-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 0.25rem;
        }
        
        /* Product Details */
        .product-details {
            flex: 1;
            margin-top: 1rem;
        }
        
        @media (min-width: 768px) {
            .product-details {
                margin-top: 0;
            }
        }
        
        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .product-title {
            font-weight: 600;
            font-size: 1.25rem;
            line-height: 1.2;
            max-width: 70%;
        }
        
        @media (min-width: 768px) {
            .product-title {
                font-size: 1.5rem;
            }
        }
        
        .product-logo {
            width: 2.5rem;
            height: 2.5rem;
            object-fit: contain;
        }
        
        /* Product Rating */
        .product-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #666;
        }
        
        .rating-value {
            font-weight: 600;
            color: #f2a900;
        }
        
        .rating-stars {
            display: flex;
            gap: 0.125rem;
            color: #f2a900;
        }
        
        .rating-divider {
            color: #ccc;
        }
        
        .rating-count, .rating-sold {
            color: #888;
            font-size: 0.75rem;
        }
        
        /* Product Price */
        .product-price {
            font-weight: 700;
            font-size: 1.5rem;
            margin-top: 0.75rem;
        }
        
        .price-unit {
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        /* Quantity Input */
        .quantity-control {
            margin-top: 1rem;
            max-width: 20rem;
        }
        
        .quantity-label {
            display: block;
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .quantity-input {
            width: 3.5rem;
            text-align: center;
            border-radius: 0.375rem;
            background-color: #d5eef5;
            color: #0e7fa8;
            font-weight: 600;
            padding: 0.25rem;
            border: 1px solid #bce0eb;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            max-width: 20rem;
        }
        
        .cart-button, .order-button {
            flex: 1;
            background-color: #1ea1ce;
            color: black;
            border-radius: 0.375rem;
            padding: 0.5rem;
            text-align: center;
            font-size: 1.125rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .cart-button:hover, .order-button:hover {
            background-color: #1a91bc;
        }
        
        /* About Product Section */
        .about-section {
            margin-top: 1.5rem;
        }
        
        .about-heading {
            color: #444;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .about-content {
            background-color: rgba(242, 169, 0, 0.9);
            padding: 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: black;
            line-height: 1.2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .about-text {
            max-width: 85%;
        }
        
        .comment-link {
            color: #0066cc;
            text-decoration: underline;
            font-size: 0.875rem;
        }
        
        /* Success Message */
        .success-message {
            background-color: #10b981;
            color: white;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="container">
            <!-- Back Button -->
            <a href="menu.php" class="back-button">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            
            <!-- Product Section -->
            <section class="product-section">
                <!-- Product Image -->
                <div class="product-image">
                    <img src="../images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="250" height="250">
                </div>

                <!-- Product Details -->
                <div class="product-details">
                    <div class="product-header">
                        <h1 class="product-title"><?php echo $product['name']; ?></h1>
                        <img src="../images/log.png" alt="Logo Hexagon Mart" class="product-logo" width="40" height="40">
                    </div>

                    <!-- Rating -->
                    <div class="product-rating">
                        <span class="rating-value">4.7</span>
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-divider">|</span>
                        <span class="rating-count">15.6RB</span>
                        <span class="rating-divider">|</span>
                        <span class="rating-sold">terjual</span>
                    </div>

                    <!-- Price -->
                    <p class="product-price">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <p class="price-unit">per porsi</p>

                    <form method="POST" action="">
                        <!-- Quantity -->
                        <div class="quantity-control">
                            <label for="quantity" class="quantity-label">Jumlah</label>
                            <input type="number" id="quantity" name="quantity" class="quantity-input" min="1" value="1">
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" name="add_to_cart" class="cart-button">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <button type="button" class="order-button" onclick="redirectToCheckout()">
                                Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- About Product Section -->
            <section class="about-section">
                <p class="about-heading">Tentang Produk</p>
                <div class="about-content">
                    <p class="about-text">
                        <?php echo $product['description']; ?>
                    </p>
                    <a href="#" class="comment-link">Lihat Komentar</a>
                </div>
            </section>

            <!-- Success Message for Cart Addition -->
            <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
            <div class="success-message">
                Produk berhasil ditambahkan ke keranjang!
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to redirect to checkout page
        function redirectToCheckout() {
            const quantity = document.getElementById('quantity').value;
            const productId = <?php echo $product_id; ?>;
            
            if (quantity < 1) {
                alert('Jumlah harus minimal 1');
                return;
            }
            
            window.location.href = `checkout.php?id=${productId}&quantity=${quantity}`;
        }
    </script>
</body>
</html>