<?php
// Get first 3 products
$produk = mysqli_query($koneksi, "SELECT * FROM products LIMIT 3");
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-text">
        <h3>HEXAGON-MART</h3>
        <h1>Super value deals<br><span>On all products</span></h1>
        <p>Save more with coupons & up to 30% off</p>
        <button onclick="loadPage('menu')">Shop Now</button>
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
    <button class="view-all" onclick="loadPage('menu')">View All</button>
</section>

<!-- Newsletter -->
<section class="newsletter">
    <div class="newsletter-content">
        <h2>Newsletter</h2>
        <p>Dapatkan notifikasi produk terbaru dan diskon spesial</p>
        <form id="newsletter-form" method="POST" action="">
            <input type="email" name="email" placeholder="Masukkan email Anda" required>
            <button type="submit">Subscribe</button>
        </form>
        <p id="newsletter-message"></p>
    </div>
</section> 