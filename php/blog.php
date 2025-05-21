<?php
session_start(); // Start session
include 'koneksi.php'; // Pastikan path ke file koneksi.php sudah benar
// Query untuk mengambil featured blog post
$featured_query = "SELECT bp.*, bc.name as category_name 
                  FROM blog_posts bp 
                  JOIN blog_categories bc ON bp.category_id = bc.category_id 
                  WHERE bp.is_featured = TRUE 
                  ORDER BY bp.publication_date DESC 
                  LIMIT 1";
$featured_result = mysqli_query($koneksi, $featured_query);
$featured_post = mysqli_fetch_assoc($featured_result);

// Query untuk mengambil blog posts terbaru (tidak termasuk featured post)
$new_posts_query = "SELECT bp.*, bc.name as category_name 
                   FROM blog_posts bp 
                   JOIN blog_categories bc ON bp.category_id = bc.category_id 
                   WHERE bp.blog_id != {$featured_post['blog_id']} 
                   ORDER BY bp.publication_date DESC 
                   LIMIT 3";
$new_posts_result = mysqli_query($koneksi, $new_posts_query);

// Query untuk mengambil popular blog posts
$popular_posts_query = "SELECT bp.*, bc.name as category_name 
                       FROM blog_posts bp 
                       JOIN blog_categories bc ON bp.category_id = bc.category_id 
                       WHERE bp.is_popular = TRUE 
                       ORDER BY bp.publication_date DESC 
                       LIMIT 6";
$popular_posts_result = mysqli_query($koneksi, $popular_posts_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart - Belanja Produk UMKM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/blog.css">
</head>
<body>
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
        <a href="cart.php">🛒<span id="cart-count"><?= array_sum($_SESSION['cart'] ?? []) ?></span></a>
        <a href="profil.php">👤</a>
    </div>
</header>

<main class="container">
    <section class="new-products">
        <h2 class="section-title">Produk Baru</h2>
        
        <?php if ($featured_post): // Tampilkan featured post ?>
        <div class="featured-product">
            <div class="featured-product-content">
                <div class="featured-product-image">
                    <img src="<?php echo $featured_post['image_path']; ?>" alt="<?php echo $featured_post['title']; ?>">
                </div>
                <div class="featured-product-info">
                    <div class="featured-product-meta">
                        <span><?php echo $featured_post['category_name']; ?></span>
                        <span><?php echo date('d M Y', strtotime($featured_post['publication_date'])); ?></span>
                    </div>
                    <h3 class="featured-product-title"><?php echo $featured_post['title']; ?></h3>
                    <p class="featured-product-desc"><?php echo $featured_post['summary']; ?></p>
                    <a href="detail_blog.php?id=<?php echo $featured_post['blog_id']; ?>" class="read-more-btn">Read More</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="product-grid">
            <?php while ($post = mysqli_fetch_assoc($new_posts_result)): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo $post['image_path']; ?>" alt="<?php echo $post['title']; ?>">
                </div>
                <div class="product-info">
                    <div class="product-category"><?php echo $post['category_name']; ?></div>
                    <div class="product-date"><?php echo date('d M Y', strtotime($post['publication_date'])); ?></div>
                    <h3 class="product-title"><?php echo $post['title']; ?></h3>
                    <p class="product-desc"><?php echo $post['summary']; ?></p>
                    <a href="detail_blog.php?id=<?php echo $post['blog_id']; ?>" class="read-more">Read More...</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    
    <section class="popular-products">
        <div class="view-all">
            <h2 class="section-title">Popular Produk</h2>
            <a href="./semuablog.php" class="view-all-btn">View All</a>
        </div>
        
        <div class="product-grid">
            <?php while ($post = mysqli_fetch_assoc($popular_posts_result)): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo $post['image_path']; ?>" alt="<?php echo $post['title']; ?>">
                </div>
                <div class="product-info">
                    <div class="product-category"><?php echo $post['category_name']; ?></div>
                    <div class="product-date"><?php echo date('d M Y', strtotime($post['publication_date'])); ?></div>
                    <h3 class="product-title"><?php echo $post['title']; ?></h3>
                    <p class="product-desc"><?php echo $post['summary']; ?></p>
                    <a href="detail_blog.php?id=<?php echo $post['blog_id']; ?>" class="read-more">Read More...</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>
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