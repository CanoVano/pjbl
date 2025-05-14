<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart - Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }
        
        body {
            background-color: #f8f8f8;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header styles */
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
        
        /* Blog section styles */
        .blog-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .blog-header {
            text-align: center;
            margin: 40px 0;
        }
        
        .section-title {
            margin-bottom: 10px;
            color: #555;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .blog-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .blog-subtitle {
            color: #666;
            font-size: 16px;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* Blog grid */
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
        
        /* Back button */
        .back-button {
    color: #333;
    text-decoration: none;
    width: 36px;
    height: 36px;
    background-color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    margin-bottom: 20px;
}

    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="../images/log.png" alt="Hexagon Mart">
        </div>
    
        <nav class="navbar-center">
            <a href="./landing.html">Home</a>
            <a href="./menu.html">Menu</a>
            <a href="#">Blog</a>
            <a href="#">Review</a>
        </nav>
          
    
        <div class="navbar-right">
            <a href="#">🛒<span id="cart-count">0</span></a>
            <a href="profil.php">👤</a>
        </div>
    </header>
    
    <div class="blog-container">
        <a href="blog.php" class="back-button">⬅</a>

        
        <div class="blog-header">
            <p class="section-title">OUR BLOGS</p>
            <h1 class="blog-title">Find our all blogs about<br>product from here</h1>
            <p class="blog-subtitle">Our blogs are written from very research research and well known writers writers so that we can provide you the best blogs and articles articles for you to read them all along</p>
        </div>
        
        <div class="product-grid">

    <!-- Blog Card 1 -->
    <div class="product-card">
        <div class="product-image">
            <img src="../images/yu.png" alt="Pizza Spesial">
        </div>
        <div class="product-info">
            <div class="product-category">FOOD</div>
            <div class="product-date">27 Dec 2024</div>
            <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
            <p class="product-desc">Hexagon Mart kembali menghadirkan kejutan spesial untuk anda...</p>
            <a href="#" class="read-more">Read More...</a>
        </div>
    </div>

    <!-- Blog Card 2 -->
    <div class="product-card">
        <div class="product-image">
            <img src="../images/yu.png" alt="Pizza Spesial">
        </div>
        <div class="product-info">
            <div class="product-category">FOOD</div>
            <div class="product-date">27 Dec 2024</div>
            <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
            <p class="product-desc">Hexagon Mart kembali menghadirkan kejutan spesial untuk anda...</p>
            <a href="#" class="read-more">Read More...</a>
        </div>
    </div>

    <!-- Blog Card 3 -->
    <div class="product-card">
        <div class="product-image">
            <img src="../images/yu.png" alt="Pizza Spesial">
        </div>
        <div class="product-info">
            <div class="product-category">FOOD</div>
            <div class="product-date">27 Dec 2024</div>
            <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
            <p class="product-desc">Hexagon Mart kembali menghadirkan kejutan spesial untuk anda...</p>
            <a href="#" class="read-more">Read More...</a>
        </div>
    </div>

            
            <!-- Blog Card 4 -->
            <div class="product-card">
        <div class="product-image">
            <img src="../images/yu.png" alt="Pizza Spesial">
        </div>
        <div class="product-info">
            <div class="product-category">FOOD</div>
            <div class="product-date">27 Dec 2024</div>
            <h3 class="product-title">Coba Sekarang Pizza Spesial Dari Mitra UMKM kami</h3>
            <p class="product-desc">Hexagon Mart kembali menghadirkan kejutan spesial untuk anda...</p>
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
            <p class="product-desc">Hexagon Mart kembali menghadirkan kejutan spesial untuk anda...</p>
            <a href="#" class="read-more">Read More...</a>
        </div>
    </div>
    </div> <!-- .product-grid -->
            </div>
        </div>
    </div>
</body>
</html>