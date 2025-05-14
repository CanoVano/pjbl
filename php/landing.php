<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="">
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
    justify-content: center; /* awalnya space-between, sekarang center */
    gap: 50px; /* kasih jarak antar teks dan gambar */
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
    height: 50px;
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
    color: #000; /* Warna icon */
    transition: color 0.3s;
}

.social-icons a:hover {
    color: #6c63ff; /* Warna saat dihover */
}

.copyright {
    text-align: center;
    margin-top: 20px;
    border-top: 3px solid #000;
    padding-top: 10px;
}


</style>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="logo">
        <img src="../images/log.png" alt="Hexagon Mart">
    </div>

    <nav class="navbar-center">
        <a href="./landing.php">Home</a>
        <a href="./menu.php">Menu</a>
        <a href="./blog.php">Blog</a>
        <a href="#">Review</a>
    </nav>
      

    <div class="navbar-right">
        <a href="#">🛒<span id="cart-count">0</span></a>
        <a href="./auth/profil.php">👤</a>
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
        <div class="product-card">
            <img src="../images/yu.png" alt="Ayam Goreng Sambal" class="product-image">
            <div class="product-info">
                <p class="product-price">Rp 15.000</p>
                <p class="product-name">Ayam Goreng Sambal Spesial dengan nasi putih</p>
                <button class="add-to-cart">tambah</button>
            </div>
        </div>
        <div class="product-card">
            <img src="../images/yu.png" alt="Ayam Goreng" class="product-image">
            <div class="product-info">
                <p class="product-price">Rp 16.000</p>
                <p class="product-name">Ayam goreng + nasi putih dan sambal kecap manis</p>
                <button class="add-to-cart">tambah</button>
            </div>
        </div>
        <div class="product-card">
            <img src="../images/yu.png" alt="Mie Goreng" class="product-image">
            <div class="product-info">
                <p class="product-price">Rp 18.000</p>
                <p class="product-name">Mie goreng istimewa paket super dan sayuran yang segar</p>
                <button class="add-to-cart">tambah</button>
            </div>
        </div>
        <div class="product-card">
            <img src="../images/yu.png" alt="Ayam Goreng Sambal" class="product-image">
            <div class="product-info">
                <p class="product-price">Rp 15.000</p>
                <p class="product-name">Ayam Goreng Sambal Spesial dengan nasi putih</p>
                <button class="add-to-cart">tambah</button>
            </div>
        </div>
    </div>
    <button class="view-all" href="./menu.html">View All</button>
</section>

<!-- Newsletter -->
<section class="newsletter">
    <h2>Dapatkan Notifikasi Untuk Produk Baru dan Diskon</h2>
    <div class="newsletter-form">
        <input type="email" placeholder="Email kamu...">
        <button>Get started</button>
    </div>
    <p class="small-text">
        Get a response tomorrow if you submit by 9pm today. If we received after 9pm will get a response the following day.
    </p>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-logo">
        <img src="../images/logo.png" alt="Hexagon Mart">
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
    const links = document.querySelectorAll('.navbar-center a');
const currentPage = window.location.pathname.split("/").pop(); // Ambil nama file terakhir

links.forEach(link => {
  const href = link.getAttribute('href').split("/").pop(); // Ambil nama file terakhir juga
  if (href === currentPage) {
    link.classList.add('active');
  }
});


  </script>
  
</body>
</html>
