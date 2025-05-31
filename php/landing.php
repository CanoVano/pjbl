<?php
session_start(); // Mulai session

include 'koneksi.php';

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

    // Also update session cart for immediate display
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect to prevent form resubmission on page refresh
    header("Location: landing.php");
    exit;
}

// Load user's cart from database if logged in
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $cart_query = mysqli_query($koneksi, "SELECT product_id, quantity FROM user_carts WHERE user_id = $user_id");
    
    // Initialize session cart
    $_SESSION['cart'] = [];
    
    // Load cart items from database into session
    while ($item = mysqli_fetch_assoc($cart_query)) {
        $_SESSION['cart'][$item['product_id']] = $item['quantity'];
    }
}

$produk = mysqli_query($koneksi, "SELECT * FROM products LIMIT 3");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="logo">
        <img src="../images/log.png" alt="Hexagon Mart">
    </div>

    <nav class="navbar-center">
        <a href="./landing.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'landing.php' ? 'active' : ''; ?>">Home</a>
        <a href="./menu.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">Menu</a>
        <a href="./blog.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>">Blog</a>
        <a href="./review.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'review.php' ? 'active' : ''; ?>">Review</a>
    </nav>
      
    <div class="navbar-right">
        <a href="./cart.php">🛒<span id="cart-count"><?= array_sum($_SESSION['cart'] ?? []) ?></span></a>
        <a href="profil.php">👤</a>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-text">
        <h3>HEXAGON-MART</h3>
        <h1>Super value deals<br><span>On all products</span></h1>
        <p>Save more with coupons & up to 30% off</p>
        <button onclick="window.location.href='menu.php'">Shop Now</button>

    </div>
    <div class="hero-image">
        <img src="../images/yy.webp" alt="Makanan" class="circle-image">
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products">
    <h2>FEATURED PRODUCTS</h2>
    <div class="products">
        <?php while ($row = mysqli_fetch_assoc($produk)) : ?>
        <div class="product-card">
            <a href="detail_produk.php?id=<?= $row['id'] ?>" class="product-image-link">
                <img src="../images/<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image">
            </a>
            <div class="product-info">
                <p class="product-price">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
                <p class="product-name"><?= $row['name'] ?></p>
                <form method="POST" action="" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="add-to-cart">tambah</button>
                </form>     
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <button class="view-all" onclick="window.location.href='./menu.php'">View All</button>
</section>

<!-- Newsletter -->
<section class="newsletter">
    <h2>Dapatkan Notifikasi Untuk Produk Baru dan Diskon</h2>
    <form id="newsletter-form" class="newsletter-form">
        <input type="email" name="email" placeholder="Email kamu..." required>
        <button type="submit">Get started</button>
    </form>
    <div id="newsletter-message" style="color: white; margin-top: 10px;"></div>
    <p class="small-text">
        Get a response tomorrow if you submit by 9pm today. If we received after 9pm will get a response the following day.
    </p>
</section>

<!-- Footer -->
<div class="floating-info-button">
    <i class="fas fa-info"></i>
</div>
<footer class="footer">
    <div class="footer-logo">
        <img src="../images/log.png" alt="Hexagon Mart">
    </div>
    <nav class="footer-links">
        <a href="#">Home</a>
        <a href="#">Blog</a>
        <a href="#">About</a>
        <a href="#">Review</a>
    </nav>
    <div class="social-icons">
        <a href="#"><i class="fa-brands fa-facebook"></i></a>
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
    </div>    
    <p class="copyright">
        Copyright Hexagon Mart © 2024. All Right Reserved
    </p>
</footer>

<script>
    document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const msg = document.getElementById('newsletter-message');

    fetch('./subscribe.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            msg.style.color = 'lightgreen';
            msg.textContent = data.message;
            form.reset();
        } else {
            msg.style.color = 'red';
            msg.textContent = data.message;
        }
    })
    .catch(() => {
        msg.style.color = 'red';
        msg.textContent = 'Terjadi kesalahan, coba lagi nanti.';
    });
});

    // Add to cart AJAX
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Update cart count
                    const cartCount = document.getElementById('cart-count');
                    cartCount.textContent = data.cart_count;
                    
                    // Optional: Show success message
                    const button = this.querySelector('.add-to-cart');
                    const originalText = button.textContent;
                    button.textContent = 'Ditambahkan!';
                    button.style.backgroundColor = '#4CAF50';
                    
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.backgroundColor = '';
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
</script>
  
</body>
</html>