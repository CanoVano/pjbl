<?php
session_start();
include 'koneksi.php';

// Tampilan semua eror untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get product ID if viewing product-specific reviews
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;

// Proses kirim ulasan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user']['id'];
    $rating = $_POST['rating'] ?? 0;
    $komentar = $_POST['komentar'] ?? '';
    $image = "";
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
    
    // Proses upload gambar jika ada
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $fileType = $_FILES['image']['type'];
        $fileSize = $_FILES['image']['size'];

        // Validasi tipe dan ukuran gambar
        if (in_array($fileType, $allowedTypes) && $fileSize <= 2 * 1024 * 1024) {
            $folder = "uploads/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $filename = time() . "_" . basename($_FILES['image']['name']);
            $target = $folder . $filename;

            // Simpan file ke folder uploads
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image = $target;
            }
        }
    }

    // Simpan ke database
    $ratingInt = intval($rating);
    $stmt = mysqli_prepare($koneksi, "INSERT INTO review (user_id, product_id, rating, komentar, image, tanggal) VALUES (?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "iiiss", $user_id, $product_id, $ratingInt, $komentar, $image);
    mysqli_stmt_execute($stmt);

    // Redirect setelah submit
    if ($product_id) {
        header("Location: detail_produk.php?id=" . $product_id . "#reviews");
    } else {
        header("Location: " . $_SERVER['PHP_SELF']);
    }
    exit;
}

// Ambil data review dengan filter
$filter = $_GET['filter'] ?? 'all';
$whereMap = [
    'with-photo' => "WHERE r.image IS NOT NULL AND r.image != ''",
    'no-photo' => "WHERE r.image IS NULL OR r.image = ''"
];
$where = $whereMap[$filter] ?? "";

// Add product filter if viewing product-specific reviews
if ($product_id) {
    $where = $where ? $where . " AND r.product_id = $product_id" : "WHERE r.product_id = $product_id";
}

// Query data review dengan join ke users dan products
$query = "SELECT r.*, u.username, u.profile_picture, p.name as product_name 
          FROM review r 
          LEFT JOIN users u ON r.user_id = u.id 
          LEFT JOIN products p ON r.product_id = p.id 
          $where 
          ORDER BY r.tanggal DESC";
$result = mysqli_query($koneksi, $query);

// Hitung rata-rata rating dan total review
$reviews = [];
$total = 0;
$sum = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
    $sum += $row['rating'];
    $total++;
}
$average = $total > 0 ? $sum / $total : 0;

// Fungsi render bintang rating
function renderStars($count) {
    $stars = "";
    for ($i = 1; $i <= 5; $i++) {
        $stars .= "<span style='color:" . ($i <= $count ? "#FFC107" : "#ddd") . "'>★</span>";
    }
    return $stars;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $product_id ? "Review Produk" : "Review" ?></title>
    <style>

        /* ==== Global Styles ==== */
        body { 
            font-family: Arial;
            background-color: #f4f4f4;
            justify-content: center;
            align-items: start;
            padding-top: 1px; 
        }
        .container { 
            margin: auto;
            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            max-width: 2000px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        
        /* ==== Rating Summary ==== */
        .rating-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #40AEDE;
            padding: 30px 40px; /* Panjang dan lebar border atas */
            border-radius: 8px; /* Border paling atas */
            margin-bottom: 15px; /* Jarak antara border atas dengan button image, komentar, dan semua */
            gap: 20px
        }
        
        .rating-summary .left-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .rating-summary .left-info strong { /* Untuk angka jumlah pengulas */
            font-size: 2.5rem;
            color:rgb(0, 0, 0);
            line-height: 1;
        }

        .rating-summary .left-info p {
            margin: 5px 0 0; /* Jarak */
            font-size: 14px; /* Ukuran huruf pengulas */
            color: #000;

        }

        .rating-summary .stars { /* Ukuran bintang */
            font-size: 50px;
        }
        
        /* ==== Tabs ==== */
        .tabs { /* Jarak antara form dan button */
            display: flex;
            justify-content: center;
            gap: 16px;
            padding: 10px;
            
        }
        
        .tabs .tab img { /* Button pensil, image, komentar */
            text-align: center;
            cursor: pointer;
            width: 165px; /* Lebar button */
            height: 100px; /* Tinggi button */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 5px
        }

        .tabs .tab img:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            background: #40AEDE;
        }
        
        .tabs .tab.active {
            background: #40AEDE;
            border: 2px solid rgb(255, 255, 255);
        }

        /* ==== Review Box ==== */
        .review { /* Untuk nama yang mereview */
            background: #F6F1F1;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 10px;
            box-shadow: 0 0px 30px rgba(0,0,0,0.05);
        }

        .review .header {
            display: flex;
            align-items: center;
        }

        .initial { /* Bagian profil pereview */
            width: 36px;
            height: 36px;
            background: #5ab9ea;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .review .stars { 
            margin-top: 0px; /* Jarak bintang dengan nama */
            font-size: 30px; /* Ukuran bintang yang di hasil */
        }

        .review img { /* Untuk foto yang di upload */
            width: 30%;
            max-width: 200px;
            border-radius: 6px;
            max-height: 200px;
            object-fit: cover;
            display: block; /* Agar tanggal dan waktu nya berada di bawah foto */
        }

        .review small { /* Untuk tanggal dan waktu yg sudah di review */
            color: #666; 
            font-size: 12px;

        }

        form { /* Jarak antara garis di bawah button pensil, image, dan komentar */
            margin-top: 25px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        
        input, textarea, select { /* Untuk mengisi ulasan */
            width: 98%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 18px;
        }

        .rating-input {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 50px;
            cursor: pointer;
            justify-content: center;
        }
        
        .rating-input .star {
            color: #ccc;
            transition: color 0.2s;
        }
        
        
        .rating-input .star.hover,
        .rating-input .star.selected {
            color: #FFA629;
        }
        
        .form-box {
            background: #f6f6f6;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        
        textarea[name="komentar"] {
            height: 100px; /* Untuk lebar comentar */
            resize: none;
            border-radius: 12px;
            padding: 12px 0px 12px 5px;
            font-size: 18px;
            background: white;
            border: 1px solid #ccc;
        }

        #uploadMessage { /* Untuk tulisan "gambar berhasil di upload" */
            display: none;
            color: green;
            font-weight: bold;
            position: absolute;
            bottom: 25px;
            left: 70px; /* Geser ke kanan dari ikon upload */
            white-space: nowrap;
        }
        
        input[type="file"] {
            margin-top: 8px;
            border: none;
            background: none;
        }
        
        button[type="submit"] {  /* Untuk tombol button kirim */
            position: absolute;
            bottom: 7px;
            right: 30px;
            color: black;
            border: none;
            margin-top: 10px;
            background: #F6AB0E;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
        }

        @media (min-width: 768px) {
            .rating-summary {
                padding: 40px 50px;
            }

            .rating-summary .left-info strong {
                font-size: 3rem;
            }

            .rating-summary .stars {
                font-size: 48px;
            }
        }

        /* Additional styles for product reviews */
        .product-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .product-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .review .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .review .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        
        .review .product-name {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
    .tabs {
        flex-wrap: wrap;
        gap: 12px;
        padding: 10px;
    }

    .tabs .tab img {
        width: 120px;
        height: 80px;
    }
}

@media (max-width: 480px) {
    .tabs {
        flex-direction: column;
        align-items: center;
    }

    .tabs .tab img {
        width: 100px;
        height: 70px;
    }
}

    </style>
</head>
<body>
    <div class="container">

        <!-- HEADER ATAS -->
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <img src="../images/back.png" style="width: 30px; height: 30px; cursor: pointer;" onclick="window.history.length > 1 ? history.back() : window.location.href='index.php'">
            <h2 style="margin: 0; font-weight: 500;"><?= $product_id ? "Review Produk" : "Review" ?></h2>
        </div>
        
        <?php if ($product_id): ?>
        <!-- Product Info -->
        <div class="product-info">
            <h3>Review untuk: <?= htmlspecialchars($reviews[0]['product_name'] ?? 'Produk') ?></h3>
        </div>
        <?php endif; ?>
        
        <!-- RINGKASAN RATING -->
        <div class="rating-summary">
            <div class="left-info">
                <strong><?= number_format($average, 1) ?></strong>
                <p><?= $total ?> Ulasan</p>
            </div>
            <div class="stars"><?= renderStars(round($average)) ?></div>
        </div>
        

        <!-- TABS DENGAN IKON -->
        <div class="tabs">
            <div class="tab <?= $filter == 'all' ? 'active' : '' ?>" id="tab-pen" onclick="window.location.href='?<?= $product_id ? "product_id=$product_id&" : "" ?>filter=all'">
                <img src="../images/semua.png">
            </div>
            <div class="tab <?= $filter == 'with-photo' ? 'active' : '' ?>" id="tab-image" onclick="window.location.href='?<?= $product_id ? "product_id=$product_id&" : "" ?>filter=with-photo'">
                <img src="../images/image.png">
            </div>
            <div class="tab <?= $filter == 'no-photo' ? 'active' : '' ?>" id="tab-comment" onclick="window.location.href='?<?= $product_id ? "product_id=$product_id&" : "" ?>filter=no-photo'">
                <img src="../images/komentar.png">
            </div>
        </div>
        
        
        <!-- FORM KIRIM ULASAN -->
        <form method="POST" enctype="multipart/form-data" class="form-box">
            <?php if ($product_id): ?>
            <input type="hidden" name="product_id" value="<?= $product_id ?>">
            <?php endif; ?>

            <!-- Bintang Rating -->
            <div class="rating-input" id="starRating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" data-value="<?= $i ?>">★</span>
                <?php endfor; ?>
                <input type="hidden" name="rating" id="ratingValue" required>
            </div>
            
            <!-- Input nama, komentar, gambar, dan tombol submit -->
            <div style="position: relative; margin-top: 20px;">
                <!-- Nama Pengguna -->
                <input type="text" name="username" placeholder="Nama anda" required>

                <!-- Komentar -->
                <div style="margin-top: 10px">
                    <textarea name="komentar" placeholder="Ketikkan ulasan anda" required></textarea>

                    <!-- Upload Gambar -->
                    <div style="display: flex; justify-content: space-between; ">
                        <label for="imageUpload" title="Unggah gambar" style="position: absolute; bottom: 30px; left: 2px; cursor: pointer; width: 22px; height: 24px;">
                            <img src="../images/uploads.png" alt="Upload" style="width: 60px; height: 40px;">
                        </label>
                    </div>
                    
                    <!-- Preview Gambar -->
                    <div id="uploadMessage" style="display: none; color: green; margin-top: 10px; font-weight: bold;">
                        Gambar berhasil di upload.
                    </div>
                </div>
                
                <!-- File input disembunyikan -->
                <input type="file" name="image" id="imageUpload" style="display: none;">
                
                <!-- Tombol Kirim -->
                <button type="submit">Kirim</button>
            </div>
        </form>
        

        <!-- TAMPILAN REVIEW -->
        <?php foreach ($reviews as $r): ?>
            <div class="review">
                <div class="header">
                    <div class="user-info">
                        <img src="<?= $r['profile_picture'] ?: 'default-avatar.png' ?>" alt="Profile" class="user-avatar">
                        <div>
                            <strong><?= htmlspecialchars($r['username']) ?></strong>
                            <?php if ($r['product_name']): ?>
                            <div class="product-name">Review untuk: <?= htmlspecialchars($r['product_name']) ?></div>
                            <?php endif; ?>
                            <div class="stars"><?= renderStars($r['rating']) ?></div>
                        </div>
                    </div>
                </div>
                <p><?= htmlspecialchars($r['komentar']) ?></p>

                <?php if ($r['image']): ?>
                    <img src="<?= $r['image'] ?>" alt="Foto ulasan">
                <?php endif; ?>
                
                <small><?= date("d M Y, H:i", strtotime($r['tanggal'])) ?></small>
            </div>
        <?php endforeach; ?>

    </div>


    <!-- JAVA SCRIPT untuk interaksi bintang rating -->
    <script>
    const stars = document.querySelectorAll('#starRating .star');
    const ratingInput = document.getElementById('ratingValue');
    let selectedRating = 0;

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            highlightStars(index + 1);
        });

        star.addEventListener('mouseout', () => {
            highlightStars(selectedRating);
        });

        star.addEventListener('click', () => {
            selectedRating = index + 1;
            ratingInput.value = selectedRating;
            highlightStars(selectedRating);
        });
    });

    function highlightStars(count) {
        stars.forEach((star, idx) => {
            star.classList.remove('selected');
            if (idx < count) {
                star.classList.add('selected');
            }
        });
    }
    
    const imageInput = document.getElementById('imageUpload');
    const uploadMessage = document.getElementById('uploadMessage');
    
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            uploadMessage.style.display = 'block';
        } else {
            uploadMessage.style.display = 'none';
        }
    });
    </script>
</body>
</html>