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

// Query untuk mengambil semua blog posts
$all_posts_query = "SELECT bp.*, bc.name as category_name 
                   FROM blog_posts bp 
                   JOIN blog_categories bc ON bp.category_id = bc.category_id 
                   ORDER BY bp.publication_date DESC";
$all_posts_result = mysqli_query($koneksi, $all_posts_query);

// Hitung total blog posts
$total_posts = mysqli_num_rows($all_posts_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexagon Mart - Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/semuablog.css">
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
    
    <div class="blog-container">
        <a href="blog.php" class="back-button">⬅</a>
        
        <div class="blog-header">
            <p class="section-title">OUR BLOGS</p>
            <h1 class="blog-title">Find our all blogs about<br>product from here</h1>
            <p class="blog-subtitle">Our blogs are written from very research research and well known writers writers so that we can provide you the best blogs and articles articles for you to read them all along</p>
        </div>
        
        <div class="product-grid">
            <?php if ($total_posts > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($all_posts_result)): ?>
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
            <?php else: ?>
                <div class="no-posts">
                    <p>Belum ada artikel blog yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
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