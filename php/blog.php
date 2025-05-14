<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart - Belanja Produk UMKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        /* Header */
        header {
            background-color: #FFC107;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .logo-text h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .logo-text p {
            font-size: 12px;
            margin: 0;
        }
        
        .navbar {
            font-family: 'Poppins', sans-serif;
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

        
        /* Main Content */
        main {
            padding: 30px 0;
        }
        
        .section-title {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        
        .view-all {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .view-all-btn {
            background-color: #fff;
            color: #333;
            text-decoration: none;
            border: 1px solid #000000;
            border-radius: 5px;
            padding: 5px 15px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .view-all-btn:hover {
            background-color: #f5f5f5;
        }
        
        /* Featured Product */
        .featured-product {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .featured-product-content {
            display: flex;
            padding: 20px;
        }
        
        .featured-product-image {
            flex: 0 0 40%;
            max-width: 40%;
        }
        
        .featured-product-image img {
            width: 100%;
            border-radius: 5px;
            height: auto;
        }
        
        .featured-product-info {
            flex: 0 0 60%;
            max-width: 60%;
            padding: 0 20px;
        }
        
        .featured-product-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .featured-product-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .featured-product-desc {
            font-size: 16px;
            line-height: 1.5;
            color: #555;
            margin-bottom: 20px;
        }
        
        .read-more-btn {
            display: inline-block;
            background-color: #fff;
            border: 1px solid #ee0707;
            border-radius: 5px;
            padding: 5px 15px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            color: #333;
        }
        
        .read-more-btn:hover {
            background-color: #f5f5f5;
        }
        
        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .product-card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .product-image {
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }
        
        .product-image img:hover {
            transform: scale(1.05);
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-category {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .product-date {
            font-size: 12px;
            color: #666;
        }
        
        .product-title {
            font-size: 18px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .product-desc {
            font-size: 14px;
            line-height: 1.4;
            color: #555;
            margin-bottom: 15px;
        }
        
        /* Popular Products Section */
        .popular-products {
            margin-top: 50px;
        }

        .new-products,
        .popular-products {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .read-more {
            color: #333;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            border-bottom: 1px solid #333;
        }

    </style>
</head>
<body>
<header class="navbar">
    <div class="logo">
        <img src="../images/log.png" alt="Hexagon Mart">
    </div>

    <nav class="navbar-center">
        <a href="./landing.php">Home</a>
        <a href="./menu.php">Menu</a>
        <a href="#">Blog</a>
        <a href="#">Review</a>
    </nav>
      

    <div class="navbar-right">
        <a href="#">🛒<span id="cart-count">0</span></a>
        <a href="profil.php">👤</a>
    </div>
</header>


    <main class="container">
        <section class="new-products">
            <h2 class="section-title">Produk Baru</h2>
            <div class="featured-product">
                <div class="featured-product-content">
                    <div class="featured-product-image">
                        <img src="../images/yu.png" alt="Ayam Goreng Spesial">
                    </div>
                    <div class="featured-product-info">
                        <div class="featured-product-meta">
                            <span>MAKANAN</span>
                            <span>27 Dec 2024</span>
                        </div>
                        <h3 class="featured-product-title">Ayam Goreng Spesial dari Mitra UMKM Hexagon Mart: Pilihan Lezat untuk Anda!</h3>
                        <p class="featured-product-desc">
                            Kelezatan makanan lokal kini tersedia secara online! Kini, di platform Hexagon Mart, Anda dapat menikmati Ayam Goreng Spesial yang diolah oleh mitra UMKM terbaik kami. Produk ini tidak hanya mengenyangkan selera, tetapi juga mendukung pelaku usaha kecil dan menengah di Indonesia...
                        </p>
                        <a href="#" class="read-more-btn">Read More</a>
                    </div>
                </div>
            </div>
            
            <div class="product-grid">
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="popular-products">
            <div class="view-all">
                <h2 class="section-title">Popular Produk</h2>
                <a href="./semuablog.php" class="view-all-btn">View All</a>
            </div>
            
            <div class="product-grid">
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="../images/yu.png" alt="Pizza Spesial">
                    </div>
                    <div class="product-info">
                        <div class="product-category">FOOD</div>
                        <div class="product-date">27 Dec 2024</div>
                        <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
                        <p class="product-desc">
                            Hexagon Mart kembali menghadirkan kejutan spesial untuk anda. Kali ini Salah Satu UMKM Kami menyediakan makanan baru yaitu pizza yang siap memuaskan lidah anda.
                        </p>
                        <a href="#" class="read-more">Read More...</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>