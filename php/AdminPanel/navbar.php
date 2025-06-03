<nav class="navbar navbar-expand-lg bg-success bg-gradient p-2">
  <div class="container">
    <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="index.php">
      <i class="fa-solid fa-gauge-high me-2"></i> Admin Panel
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
      aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto flex-wrap align-items-center">
        <?php
        $menuItems = [
          ['Home', '../AdminPanel', 'fa-house'],
          ['Kategori Blog', 'blog_categories.php', 'fa-folder'],
          ['Blog Post', 'blog_posts.php', 'fa-pen-nib'],
          ['Keranjang', 'cart.php', 'fa-cart-shopping'],
          ['Subscriber', 'newsletter_subscribers.php', 'fa-envelope'],
          ['Orders', 'orders.php', 'fa-box'],
          ['Order Items', 'order_items.php', 'fa-boxes-stacked'],
          ['Produk', 'produk.php', 'fa-bag-shopping'],
          ['Review', 'review.php', 'fa-star'],
          ['Saran Postingan', 'suggested_posts.php', 'fa-thumbs-up'],
          ['Users', 'users.php', 'fa-users'],
          ['Users Carts', 'user_carts.php', 'fa-cart-plus'],
          ['Logout', 'logout.php', 'fa-right-from-bracket']
        ];

        foreach ($menuItems as $item) {
          echo '<li class="nav-item mx-2 my-1">
                 <a class="nav-link text-white fw-semibold d-flex align-items-center" href="' . $item[1] . '">
                    <i class="fa-solid ' . $item[2] . ' me-1"></i> ' . $item[0] . '
                  </a>
                </li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>

<!-- FontAwesome -->
<script src="../../fontawesome/js/all.min.js"></script>
