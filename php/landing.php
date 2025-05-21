<?php
session_start(); // Mulai session

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Jika session cart belum ada, buat array baru
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Jika produk sudah ada di cart, tambah jumlahnya
    if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += 1; // Tambah jumlah produk
} else {
    $_SESSION['cart'][$product_id] = 1; // Inisialisasi dengan 1
}

    // Redirect supaya form tidak submit ulang jika refresh page
    header("Location: landing.php");
    exit;
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
    <link rel="stylesheet" href="">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: #333;
    }


    /* Navbar */
    .navbar {
        background: #F6AB0E;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 40px;
    }

    .navbar .logo img {
        height: 60px;
    } 

    .navbar-center {
        display: flex;
        gap: 20px;
    }

    .navbar-center a {
        text-decoration: none;
        color: black;
        font-weight: 500;
        padding: 10px;
    }

    .navbar-right {
        display: flex;
        gap: 15px;
    }

    .navbar-right a {
        text-decoration: none;
        font-size: 20px;
        color: black;
    }

    .navbar-center a.active {
        font-weight: bold;
        border-bottom: 2px solid black;
    }  

    /* Hero */
    .hero {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 50px;
        padding: 60px 40px;
    }


    .hero-text h3 {
        font-weight: normal;
    }

    .hero-text h1 {
        font-size: 2.5em;
    }

    .hero-text span {
        color: purple;
    }

    .hero-text p {
        margin: 20px 0;
    }

    .hero-text button {
        padding: 10px 20px;
        background: lightgray;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }

    .hero-image img.circle-image {
        width: 300px;
        height: 300px;
        object-fit: center;
        border-radius: 50%;
    }

    /* Featured Products */
    .featured-products {
        text-align: center;
        padding: 50px 20px;
    }

    .featured-products h2 {
        margin-bottom: 30px;
    }

    .products {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .product-card {
        max-width: 250px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image-link {
        display: block;
        cursor: pointer;
    }

    .product-image {
        height: 200px;
        width: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    .product-info {
        padding: 15px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-price {
        font-size: 18px;
        font-weight: bold;
        color: #1a8917;
        margin-bottom: 5px;
    }

    .product-name {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .add-to-cart {
        background-color: #A0E7A0;
        color: black;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .add-to-cart:hover {
        background-color: #8ad98a;
    }

    .view-all {
        margin-top: 30px;
        padding: 10px 20px;
        background: white;
        border: 1px solid black;
        cursor: pointer;
    }

    /* Newsletter */
    .newsletter {
        background: #333;
        color: white;
        padding: 60px 20px;
        text-align: center;
    }

    .newsletter-form {
        margin: 20px 0;
    }

    .newsletter-form input {
        padding: 10px;
        width: 250px;
        margin-right: 10px;
    }

    .newsletter-form button {
        padding: 10px 20px;
        background: transparent;
        color: white;
        border: 1px solid white;
        cursor: pointer;
    }

    .small-text {
        font-size: 0.8em;
        margin-top: 10px;
    }

    /* Footer */
    .footer {
        background: #f5f5f5;
        text-align: center;
        padding: 40px 20px;
    }

    .footer-logo img {
        height: 100px;
        margin-bottom: 20px;
    }

    .footer-links {
        margin-bottom: 20px;
    }

    .footer-links a {
        margin: 0 10px;
        text-decoration: none;
        color: black;
    }

    .social-icons {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 20px;
    }

    .social-icons a {
        font-size: 24px;
        color: #000;
        transition: color 0.3s;
    }

    .social-icons a:hover {
        color: #6c63ff;
    }

    .copyright {
        text-align: center;
        margin-top: 20px;
        border-top: 3px solid #000;
        padding-top: 10px;
    }

    .floating-info-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        background-color: #ffffff;
        border-radius: 50%;
        border: 2px solid #ccc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        z-index: 999;
        transition: background-color 0.3s;
    }

    .floating-info-button:hover {
        background-color: #f0f0f0;
    }
    </style>
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
        <button>Shop Now</button>
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
                <form method="POST" action="">
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
</script>
  
</body>
</html>