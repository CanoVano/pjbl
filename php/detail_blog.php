<?php
session_start(); // Start session
include 'koneksi.php'; // Pastikan path ke file koneksi.php sudah benar

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

// Mengambil ID blog dari URL
$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query untuk mengambil detail blog post
$post_query = "SELECT bp.*, bc.name as category_name 
              FROM blog_posts bp 
              JOIN blog_categories bc ON bp.category_id = bc.category_id 
              WHERE bp.blog_id = $blog_id";
$post_result = mysqli_query($koneksi, $post_query);
$post = mysqli_fetch_assoc($post_result);

// Jika blog tidak ditemukan, redirect ke halaman blog
if (!$post) {
    header("Location: blog.php");
    exit;
}

// Query untuk mengambil suggested posts berdasarkan blog yang sedang dilihat
$suggested_query = "SELECT bp.*, bc.name as category_name 
                   FROM blog_posts bp 
                   JOIN blog_categories bc ON bp.category_id = bc.category_id 
                   JOIN suggested_posts sp ON bp.blog_id = sp.suggested_post_id 
                   WHERE sp.main_post_id = $blog_id 
                   ORDER BY bp.publication_date DESC 
                   LIMIT 3";
$suggested_result = mysqli_query($koneksi, $suggested_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?> - Hexagon Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/detail.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="../images/log.png" alt="Hexagon Mart">
        </div>
    
    <nav class="navbar-center">
        <a href="./landing.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'landing.php' ? 'active' : ''; ?>">Home</a>
        <a href="./menu.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'menu.php' ? 'active' : ''; ?>">Menu</a>
        <a href="./blog.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'blog') !== false ? 'active' : ''; ?>">Blog</a>
        <a href="./review.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'review.php' ? 'active' : ''; ?>">Review</a>
    </nav>
          
        <div class="navbar-right">
            <a href="cart.php">🛒<span id="cart-count"><?= array_sum($_SESSION['cart'] ?? []) ?></span></a>
            <a href="profil.php">👤</a>
        </div>
    </header>

    <main class="main-content">
        <a href="blog.php" class="back-button">⬅</a>
        <span class="category"><?php echo $post['category_name']; ?></span>
        <span class="date"><?php echo date('d M Y', strtotime($post['publication_date'])); ?></span>

        <h1><?php echo $post['title']; ?></h1>
        <img src="<?php echo $post['image_path']; ?>" alt="<?php echo $post['title']; ?>" class="featured-image" />
        
        <div class="article-content">
            <?php 
            // Konversi paragraf dan list dalam konten
            $content = nl2br($post['content']);
            echo $content;
            ?>
        </div>
    </main>
    
    <section class="suggested-posts">
        <h2>Suggested Post</h2>
        <div class="product-grid">
            <?php while ($suggested = mysqli_fetch_assoc($suggested_result)): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo $suggested['image_path']; ?>" alt="<?php echo $suggested['title']; ?>">
                </div>
                <div class="product-info">
                    <div class="product-category"><?php echo $suggested['category_name']; ?></div>
                    <div class="product-date"><?php echo date('d M Y', strtotime($suggested['publication_date'])); ?></div>
                    <h3 class="product-title"><?php echo $suggested['title']; ?></h3>
                    <p class="product-desc">
                        <?php echo $suggested['summary']; ?>
                    </p>
                    <a href="detail_blog.php?id=<?php echo $suggested['blog_id']; ?>" class="read-more">Read More...</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    <script>
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