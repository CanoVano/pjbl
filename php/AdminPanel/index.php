<?php
require "session.php";
require "../koneksi.php";

$jumlahkategori = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM blog_categories"));
$jumlahpost = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM blog_posts"));
$jumlahpesanan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM cart"));
$jumlahakun = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM newsletter_subscribers"));
$jumlahproduk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM products"));
$jumlahsaran = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM suggested_posts"));
$jumlahusers = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));
$jumlahorders = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM orders"));
$jumlahorderitems = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM order_items"));
$jumlahreview = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM review"));
$jumlahuserscarts = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM user_carts"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-summary {
            border-radius: 12px;
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            color: #fff;
            height: 180px;
            padding: 20px;
            position: relative;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-in-out;
        }


        .card-summary:hover {
            transform: scale(1.03);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            font-size: 50px;
            opacity: 0.8;
            animation: popIn 0.5s ease;
        }

        .card-body-content {
            position: absolute;
            bottom: 20px;
            left: 20px;
        }

        .card-body-content h5 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .card-body-content p {
            margin-bottom: 8px;
        }

        .card-body-content a {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
        }

        .card-body-content a:hover {
            text-decoration: underline;
            color: #fff;
        }

        .breadcrumb {
            background: none;
            padding-left: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes popIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fa-solid fa-house me-2"></i>Home
                </li>
            </ol>
        </nav>

        <h2 class="mb-4">Selamat Datang di Dashboard Admin</h2>

        <div class="row g-4">
            <?php
            $data = [
                ['Kategori Blog', $jumlahkategori, 'blog_categories.php', 'fa-newspaper'],
                ['Blog Posts', $jumlahpost, 'blog_posts.php', 'fa-scroll'],
                ['Keranjang', $jumlahpesanan, 'cart.php', 'fa-cart-shopping'],
                ['Subscriber', $jumlahakun, 'newsletter_subscribers.php', 'fa-user-plus'],
                ['Produk', $jumlahproduk, 'produk.php', 'fa-bag-shopping'],
                ['Saran Post', $jumlahsaran, 'suggested_posts.php', 'fa-thumbs-up'],
                ['Users', $jumlahusers, 'users.php', 'fa-users'],
                ['Orders', $jumlahorders, 'orders.php', 'fa-box'],
                ['Order Items', $jumlahorderitems, 'order_items.php', 'fa-cubes'],
                ['Review', $jumlahreview, 'review.php', 'fa-star'],
                ['Users Carts', $jumlahuserscarts, 'user_carts.php', 'fa-cart-plus'],
            ];

            foreach ($data as $d) {
                echo '<div class="col-12 col-sm-6 col-lg-4">
                        <div class="card-summary">
                            <i class="fa-solid ' . $d[3] . ' card-icon"></i>
                            <div class="card-body-content">
                                <h5>' . $d[0] . '</h5>
                                <p>' . $d[1] . '</p>
                                <a href="' . $d[2] . '">Lihat Detail</a>
                            </div>
                        </div>
                    </div>';
            }
            ?>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>