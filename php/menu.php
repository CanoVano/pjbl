<?php
session_start(); // Start session

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // If session cart doesn't exist, create a new array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already exists in cart, increase the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1; // Increase product quantity
    } else {
        $_SESSION['cart'][$product_id] = 1; // Initialize with 1
    }

    // Redirect to prevent form resubmission on page refresh
    header("Location: menu.php");
    exit;
}

// Product data array for descriptions - this will be used in detail_produk.php
$product_descriptions = [
    1 => "Ayam Goreng Sambal Spesial dengan nasi putih. Produk ini dibuat dari ayam pilihan berkualitas tinggi dari peternakan terbaik, diolah secara higienis dan disukai oleh banyak orang.",
    2 => "Ayam goreng + nasi putih dan sambal kecap manis. Produk ini dibuat dari ayam pilihan berkualitas tinggi dari peternakan terbaik, diolah secara higienis dan disukai oleh banyak orang.",
    3 => "Mie goreng istimewa paket super dan sayuran yang segar. Produk ini dibuat dari bahan-bahan pilihan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang.",
    4 => "Mie kuah Jawa dengan rasa dan tekstur mantap yang menggugah. Produk ini dibuat dari bahan-bahan pilihan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang.",
    5 => "Sayur kuah kental dari olahan jamu mentah dengan racikan saus daerah. Produk ini dibuat dari sayuran segar pilihan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang.",
    6 => "Nasi goreng spesial kampung yang sangat istimewa. Produk ini dibuat dari beras pilihan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang.",
    7 => "Kwetiau goreng seafood dengan tambahan bahan-bahan seafood yang fresh. Produk ini dibuat dari kwetiau dan seafood pilihan berkualitas tinggi, diolah secara higienis dan disukai oleh banyak orang."
];

// Store product descriptions in session for detail_produk.php to use
$_SESSION['product_descriptions'] = $product_descriptions;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart - Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f7f7f7;
}



.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Navbar styling */
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

  .navbar .navbar-center a:hover {
    border-bottom: 2px solid black;
}

/* Product section styling */
.featured-products {
    margin-top: 30px;
}

.section-title {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}

.products {
    display: flex;
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

.product-image {
    height: 200px;
    width: 100%;
    object-fit: contain;
    border-radius: 8px;
    cursor: pointer; /* Add cursor pointer to indicate clickable */
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
    margin-top: auto;
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

/* Banner styling */
.banner-container {
    margin: 40px 0;
    overflow: hidden;
    position: relative;
}

.banner-wrapper {
    display: flex;
    transition: transform 0.5s ease;
    cursor: grab;
}

.banner-wrapper.grabbing {
    cursor: grabbing;
}

.banner {
    min-width: 100%;
    height: 200px;
    background-color: #A0E7A0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    border-radius: 8px;
    color: #333;
}

.banner:nth-child(even) {
    background-color: #8ad98a;
}

.banner-content {
    text-align: center;
}

.banner-title {
    font-size: 24px;
    margin-bottom: 10px;
}

.banner-text {
    font-size: 16px;
    margin-bottom: 15px;
}

.banner-btn {
    background-color: white;
    color: #1a8917;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
}

/* Dots navigation */
.dots {
    display: flex;
    justify-content: center;
    margin-top: 15px;
}

.dot {
    width: 10px;
    height: 10px;
    background-color: #ccc;
    border-radius: 50%;
    margin: 0 5px;
    cursor: pointer;
}

.dot.active {
    background-color: #1a8917;
}

/* Navigation arrows */
.banner-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
}

.prev-btn {
    left: 10px;
}

.next-btn {
    right: 10px;
}
</style>
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

    <div class="container">
        <!-- Featured Products -->
        <section class="featured-products">
            <h2 class="section-title">Terlaris minggu ini</h2>
            <div class="products">
                <div class="product-card">
                    <a href="detail_produk.php?id=1">
                        <img src="../images/yu.png" alt="Ayam Goreng Sambal" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 15.000</p>
                        <p class="product-name">Ayam Goreng Sambal Spesial dengan nasi putih</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="1">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
                <div class="product-card">
                    <a href="detail_produk.php?id=2">
                        <img src="../images/yu.png" alt="Ayam Goreng" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 16.000</p>
                        <p class="product-name">Ayam goreng + nasi putih dan sambal kecap manis</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="2">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
                <div class="product-card">
                    <a href="detail_produk.php?id=3">
                        <img src="../images/yu.png" alt="Mie Goreng" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 18.000</p>
                        <p class="product-name">Mie goreng istimewa paket super dan sayuran yang segar</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="3">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Swipeable Banner -->
        <section class="banner-container">
            <div class="banner-nav prev-btn">❮</div>
            <div class="banner-wrapper">
                <div class="banner">
                    <div class="banner-content">
                        <h3 class="banner-title">Lezatnya disetiap gigitan</h3>
                        <p class="banner-text">DAPATKAN DISKON UP TO 25%</p>
                        <button class="banner-btn">Lihat Sekarang</button>
                    </div>
                </div>
                <div class="banner">
                    <div class="banner-content">
                        <h3 class="banner-title">Menu Terbaru</h3>
                        <p class="banner-text">Coba menu spesial dari chef kami</p>
                        <button class="banner-btn">Lihat Menu</button>
                    </div>
                </div>
                <div class="banner">
                    <div class="banner-content">
                        <h3 class="banner-title">Promo Akhir Pekan</h3>
                        <p class="banner-text">Diskon 20% untuk pembelian di akhir pekan</p>
                        <button class="banner-btn">Dapatkan Promo</button>
                    </div>
                </div>
            </div>
            <div class="banner-nav next-btn">❯</div>
            <div class="dots">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </section>

        <!-- More Products -->
        <section class="featured-products">
            <h2 class="section-title">Aneka kuliner</h2>
            <div class="products">
                <div class="product-card">
                    <a href="detail_produk.php?id=4">
                        <img src="../images/yu.png" alt="Mie Kuah" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 14.000</p>
                        <p class="product-name">Mie kuah Jawa dengan rasa dan tekstur mantap yang menggugah</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="4">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
                <div class="product-card">
                    <a href="detail_produk.php?id=5">
                        <img src="../images/yu.png" alt="Sayur Kuah" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 28.000</p>
                        <p class="product-name">Sayur kuah kental dari olahan jamu mentah dengan racikan saus daerah</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="5">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
                <div class="product-card">
                    <a href="detail_produk.php?id=6">
                        <img src="../images/yu.png" alt="Nasi Goreng" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 23.000</p>
                        <p class="product-name">Nasi goreng spesial kampung yang sangat istimewa</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="6">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
                <div class="product-card">
                    <a href="detail_produk.php?id=7">
                        <img src="../images/yu.png" alt="Kwetiau" class="product-image">
                    </a>
                    <div class="product-info">
                        <p class="product-price">Rp 20.000</p>
                        <p class="product-name">Kwetiau goreng seafood dengan tambahan bahan-bahan seafood yang fresh</p>
                        <form method="POST" action="">
                            <input type="hidden" name="product_id" value="7">
                            <button type="submit" class="add-to-cart">tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script>
        // Banner slider functionality
        const wrapper = document.querySelector('.banner-wrapper');
        const dots = document.querySelectorAll('.dot');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        let currentIndex = 0;

        // Update the banner position
        function updateSlider() {
            wrapper.style.transform = translateX(-${currentIndex * 100}%);
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }

        // Event listeners for dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                updateSlider();
            });
        });

        // Event listeners for navigation buttons
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex === 0) ? dots.length - 1 : currentIndex - 1;
            updateSlider();
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex === dots.length - 1) ? 0 : currentIndex + 1;
            updateSlider();
        });

        // Active navigation link
        const links = document.querySelectorAll('.navbar-center a');
        const currentPage = window.location.pathname.split("/").pop(); // Get the last file name

        links.forEach(link => {
            const href = link.getAttribute('href').split("/").pop(); // Get the last file name too
            if (href === currentPage) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>