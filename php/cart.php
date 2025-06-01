<?php
session_start();
include 'koneksi.php';

// Atur array voucher yang tersedia
$voucher_tersedia = [
    "DISKON25" => ["nilai" => 25, "jenis" => "persen"],
    "DISKON50" => ["nilai" => 50, "jenis" => "persen"],
    "DISKON5"  => ["nilai" => 5,  "jenis" => "persen"],
];

// Proses klaim voucher
if (isset($_POST['klaim_voucher'])) {
    $kode_voucher_diklaim = $_POST['kode_voucher'];
    
    if (isset($voucher_tersedia[$kode_voucher_diklaim])) {
        $_SESSION['voucher_diklaim'] = $kode_voucher_diklaim;
    } else {
        $_SESSION['error_voucher'] = "Kode voucher tidak valid.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Proses menghapus item dari keranjang
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user']['id'];
    
    // Update database
    if (isset($_SESSION['cart'][$product_id])) {
        if ($_SESSION['cart'][$product_id] > 1) {
            // Decrease quantity in database
            mysqli_query($koneksi, "UPDATE user_carts SET quantity = quantity - 1 WHERE user_id = $user_id AND product_id = $product_id");
            $_SESSION['cart'][$product_id]--;
        } else {
            // Remove item from database
            mysqli_query($koneksi, "DELETE FROM user_carts WHERE user_id = $user_id AND product_id = $product_id");
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Proses menambah item ke keranjang
if (isset($_POST['add_recommendation'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user']['id'];
    
    // Check if product already exists in user's cart
    $check_query = mysqli_query($koneksi, "SELECT * FROM user_carts WHERE user_id = $user_id AND product_id = $product_id");
    
    if (mysqli_num_rows($check_query) > 0) {
        // Update quantity if product exists
        mysqli_query($koneksi, "UPDATE user_carts SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Insert new product if it doesn't exist
        mysqli_query($koneksi, "INSERT INTO user_carts (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
    
    // Update session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

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

$voucher_diklaim_saat_ini = $_SESSION['voucher_diklaim'] ?? null;
$error_voucher = $_SESSION['error_voucher'] ?? null;
unset($_SESSION['error_voucher']);

// Ambil data produk dari database
$cart_items = [];
$total_harga = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $id_list = implode(',', $product_ids);
    
    if (!empty($id_list)) {
        $query = mysqli_query($koneksi, "SELECT * FROM products WHERE id IN ($id_list)");
        
        while ($row = mysqli_fetch_assoc($query)) {
            $quantity = $_SESSION['cart'][$row['id']];
            $subtotal = $row['price'] * $quantity;
            $total_harga += $subtotal;
            
            $cart_items[] = [
                "id" => $row['id'],
                "image" => $row['image'],
                "name" => $row['name'],
                "description" => $row['description'] ?? 'Tidak ada deskripsi',
                "price" => $row['price'],
                "quantity" => $quantity,
                "subtotal" => $subtotal
            ];
        }
    }
}

// Hitung diskon jika ada voucher yang diklaim
$diskon = 0;
$harga_setelah_diskon = $total_harga;

if ($voucher_diklaim_saat_ini && isset($voucher_tersedia[$voucher_diklaim_saat_ini])) {
    $voucher = $voucher_tersedia[$voucher_diklaim_saat_ini];
    
    if ($voucher['jenis'] == 'persen') {
        $diskon = ($total_harga * $voucher['nilai']) / 100;
        $harga_setelah_diskon = $total_harga - $diskon;
    } else { // Jika jenisnya 'nominal'
        $diskon = $voucher['nilai'];
        $harga_setelah_diskon = $total_harga - $diskon;
        if ($harga_setelah_diskon < 0) $harga_setelah_diskon = 0;
    }
}

// Ambil rekomendasi produk (5 produk yang tidak ada di keranjang)
$existing_ids = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : [];
$exclude_ids = empty($existing_ids) ? "0" : implode(',', $existing_ids);

$recommendations = [];
$query_rekomendasi = mysqli_query($koneksi, "SELECT * FROM products WHERE id NOT IN ($exclude_ids) LIMIT 5");

while ($row = mysqli_fetch_assoc($query_rekomendasi)) {
    $recommendations[] = [
        "id" => $row['id'],
        "image" => $row['image'],
        "name" => $row['name'],
        "price" => $row['price']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .container {
            background-color: #fff;
            width: 90%;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #F6AB0E;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
        }

        .header a {
            text-decoration: none;
            color: #000000;
            font-size: 18px;
        }

        .discount-bar {
            display: flex;
            padding: 15px;
            gap: 60px; 
            overflow-x: auto;
            background-color: #e9f7ef;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .discount-item {
            background-color: #c9f1dd;
            color: #000000;
            border-radius: 10px;
            padding: 10px 32px;
            font-size: 15px;
            display: flex;
            align-items: center;
            flex-shrink: 0;
            width: auto;
        }

        .discount-item span {
            font-weight: bold;
            margin-right: 8px;
        }

        .discount-item button {
            background-color: #4E94B2;
            color: white;
            border: none;
            padding: 7px 12px;
            border-radius: 3px;
            font-size: 13px;
            cursor: pointer;
            margin-left: 15px;
        }

        .discount-item button.diklaim {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            border: 1px solid #999;
        }

        .cart-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            align-items: center;
        }

        .cart-item img {
            width: 150px; 
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .item-description {
            color: #777;
            font-size: 14px; 
            margin-bottom: 15px;
        }

        .item-price {
            font-weight: bold;
            color: #000000;
            font-size: 14px;
        }

        .item-quantity {
            background-color: #d4edda;
            color: #155724;
            padding: 1px 30px;
            border-radius: 30px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        
        .item-remove {
            margin-left: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 3px 8px;
            cursor: pointer;
            font-size: 12px;
        }

        .recommendation-section {
            padding: 12px;
            gap: 50px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .recommendation-title {
            font-weight: bold;
            margin-bottom: 18px;
            text-align: center;
            font-size: 16px;
        }

        .recommendation-grid {
            display: flex;
            gap: 30px; 
            overflow-x: auto;
            padding-bottom: 15px;
            justify-content: center; 
            padding: 20px;
            flex-wrap: wrap;
        }

        .recommendation-item {
            background-color: #fff;
            border-radius: 0px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 120px; 
            flex-shrink: 0;
        }

        .recommendation-item img {
            width: 100%;
            height: 100px; 
            object-fit: contain;
        }

        .recommendation-info {
            padding: 8px;
            text-align: center;
        }

        .recommendation-name {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .recommendation-price {
            font-size: 12px;
            color: #000000;
        }

        .recommendation-add {
            background-color: #f0ad4e;
            color: rgb(43, 42, 42);
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 13px; 
            cursor: pointer;
            width: 100%;
        }

        .total-section {
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            width: 80%;
            max-width: 400px;
            margin-bottom: 5px;
        }

        .subtotal-label, .discount-label {
            font-size: 14px;
        }

        .total-label {
            font-weight: bold;
            font-size: 16px;
            margin-top: 15px;
        }

        .subtotal-amount, .discount-amount {
            font-size: 14px;
        }

        .total-amount {
            font-weight: bold;
            font-size: 18px;
            color: #28a745;
        }

        .checkout-button-container {
            padding: 15px;
            display: flex;
            justify-content: center;
        }

        .checkout-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 90%;
            max-width: 400px;
        }

        .error-voucher {
            color: red;
            font-style: italic;
            margin-top: 10px;
            text-align: center;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            font-size: 16px;
        }
        
        .back-to-shopping {
            margin-top: 20px;
            padding: 10px 20px;
            background: #F6AB0E;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="landing.php">&larr; Keranjang</a>
            <a href="cart.php">&#128722; <?= array_sum($_SESSION['cart'] ?? []) ?></a>
        </div>

        <div class="discount-bar">
            <?php
            $discounts = [
                ["label" => "-25%", "kode" => "DISKON25"],
                ["label" => "-50%", "kode" => "DISKON50"],
                ["label" => "-5%", "kode" => "DISKON5"],
            ];

            foreach ($discounts as $discount) {
                $kode = $discount["kode"];
                $diklaim = ($voucher_diklaim_saat_ini === $kode);

                echo '<div class="discount-item"><span>' . $discount["label"] . '</span> ';
                if (!$diklaim) {
                    echo '<form method="POST" action="">';
                    echo '<input type="hidden" name="kode_voucher" value="' . $kode . '">';
                    echo '<button type="submit" name="klaim_voucher">Klaim</button>';
                    echo '</form>';
                } else {
                    echo '<button class="diklaim" disabled>Diklaim</button>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <?php if ($error_voucher): ?>
            <p class="error-voucher"><?php echo $error_voucher; ?></p>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <p>Keranjang belanja Anda kosong</p>
                <a href="menu.php" class="back-to-shopping">Lanjut Belanja</a>
            </div>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="../images/<?= $item["image"] ?>" alt="<?= $item["name"] ?>">
                    <div class="item-details">
                        <div class="item-name"><?= $item["name"] ?></div>
                        <div class="item-description"><?= $item["description"] ?></div>
                        <div class="item-price">Rp <?= number_format($item["price"], 0, ',', '.') ?></div>
                    </div>
                    <div class="item-quantity">
                        <?= $item["quantity"] ?>x
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?= $item["id"] ?>">
                            <button type="submit" name="remove_item" class="item-remove">✕</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="recommendation-section">
                <div class="recommendation-title">Rekomendasi</div>
                <div class="recommendation-grid">
                    <?php if (empty($recommendations)): ?>
                        <p>Tidak ada rekomendasi tersedia saat ini.</p>
                    <?php else: ?>
                        <?php foreach ($recommendations as $recommendation): ?>
                            <div class="recommendation-item">
                                <img src="../images/<?= $recommendation["image"] ?>" alt="<?= $recommendation["name"] ?>">
                                <div class="recommendation-info">
                                    <div class="recommendation-name"><?= $recommendation["name"] ?></div>
                                    <div class="recommendation-price">Rp <?= number_format($recommendation["price"], 0, ',', '.') ?></div><br>
                                    <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?= $recommendation["id"] ?>">
                                        <button type="submit" name="add_recommendation" class="recommendation-add">tambah</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="total-section">
                <div class="total-row">
                    <div class="subtotal-label">Subtotal</div>
                    <div class="subtotal-amount">Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
                </div>
                
                <?php if ($diskon > 0): ?>
                <div class="total-row">
                    <div class="discount-label">Diskon (<?= $voucher_tersedia[$voucher_diklaim_saat_ini]['nilai'] ?>%)</div>
                    <div class="discount-amount">- Rp <?= number_format($diskon, 0, ',', '.') ?></div>
                </div>
                <?php endif; ?>
                
                <div class="total-row">
                    <div class="total-label">Total</div>
                    <div class="total-amount">Rp <?= number_format($harga_setelah_diskon, 0, ',', '.') ?></div>
                </div>
            </div>

            <div class="checkout-button-container">
                <form action="checkout.php" method="GET">
                    <input type="hidden" name="cart_data" value="<?php echo htmlspecialchars(json_encode($cart_items)); ?>">
                    <input type="hidden" name="total_price" value="<?php echo $harga_setelah_diskon; ?>">
                    <button type="submit" class="checkout-button">Checkout</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>