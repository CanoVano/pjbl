<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart - Belanja Produk UMKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<style>
* {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
            
        }
        
body {
        background-color: #f5f5f5;
        color: #333;
    
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

  .main-content {
    max-width: 900px;
    margin: 2rem auto;
   flex-direction: column;
   
    padding: 2rem;
  }

  .category {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        
        .date {
            font-size: 12px;
            color: #666;
            margin-bottom: 1rem;
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 1rem;
        }
        
        .featured-image {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            max-height: 400px;
            object-fit: cover;
        }
        
        .article-content {
            line-height: 1.6;
            color: #333;
            margin-bottom: 2rem;
        }

        .article-content p {
            margin-bottom: 1rem;
        }
        
        .article-content h3 {
            margin: 1.5rem 0 1rem;
        }
        
        .article-content ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .article-content li {
            margin-bottom: 0.5rem;
        }
        
        .suggested-posts {
            background-color: #f9f9f9;
            padding: 2rem;
        }
        
        .suggested-posts h2 {
            font-size: 24px;
            margin-bottom: 1.5rem;
            color: #333;
        }
        
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
        
        .read-more {
            color: #333;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            border-bottom: 1px solid #333;
        }
        
        .promo-info {
            background-color: #f5f5f5;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

  .back-button {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            justify-content: center;
            margin-bottom: 1rem;
            cursor: pointer;
        }

</style>
<body>
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
            <a href="profil.php">👤</a>
        </div>
    </header>

    <main class="main-content">
        <a href="blog.php" class="back-button">⬅</a>
        <span class="category">FOOD</span>
        <span class="date">17 nov 2024</span>

        <h1>Ayam Goreng Spesial dari Mitra UMKM Hexagon Mart: Pilihan Lezat untuk Anda!</h1>
        <img src="../images/yu.png" alt="Ayam Goreng Spesial" class="featured-image" />
        
        <div class="article-content">
            <p>Kabar gembira untuk para pencinta ayam goreng! Kini, di platform Hexagon Mart, Anda dapat menemukan Ayam Goreng Spesial yang dijual oleh mitra UMKM terbaik kami. Produk ini tidak hanya menggugah selera, tetapi juga mendukung pelaku usaha kecil dan menengah di Indonesia. Nikmati kelezatan Ayam Goreng Spesial dari mitra UMKM kesayangan kami di Hexagon Mart! Segera pesan untuk mendapatkan cita rasa autentik yang memanjakan lidah.</p>
            
            <p>Variasi rasa Bisa Anda Pilih:</p>
            <ul>
                <li>Rasa Nusantara: Rempah khas daerah seperti Padang dan Jawa.</li>
                <li>Spicy Delight: Pedas yang menggugah selera.</li>
                <li>Crunchy & Cheesy: Ayam renyah dengan keju leleh.</li>
            </ul>
            
            <h3>Mengapa Pilih Hexagon Mart?</h3>
            <ol>
                <li>Dukung UMKM Lokal: Belanja Anda membantu UMKM berkembang.</li>
                <li>Beragam Pilihan: Banyak variasi rasa dari mitra terbaik.</li>
                <li>Mudah dan Praktis: Pesan online dengan pengiriman cepat.</li>
            </ol>
            
            <div class="promo-info">
                <p><strong>Gratis Ongkir untuk belanja Rp50.000 ke atas.</strong></p>
                <p><strong>Diskon hingga 30% di toko pilihan.</strong></p>
            </div>

            <p>Ayo temukan ayam goreng favorit Anda di <a href="http://www.hexagonmart.com">www.hexagonmart.com</a> dan dukung UMKM lokal! #HexagonMart #DukungUMKM #BelanjaOnline</p>
        </div>
    </main>
    
    <section class="suggested-posts">
        <h2>Suggested Post</h2>
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


    </main>
</body>
</html>