<?php
session_start();

if (isset($_POST['klaim_voucher'])) {
    $kode_voucher_diklaim = $_POST['kode_voucher'];
    $voucher_tersedia = [
        "DISKON25" => ["nilai" => 25, "jenis" => "persen"],
        "DISKON50" => ["nilai" => 50, "jenis" => "persen"],
        "DISKON5"  => ["nilai" => 5,  "jenis" => "persen"],
    ];

    if (isset($voucher_tersedia[$kode_voucher_diklaim])) {
        $_SESSION['voucher_diklaim'] = $kode_voucher_diklaim;
    } else {
        $_SESSION['error_voucher'] = "Kode voucher tidak valid.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$voucher_diklaim_saat_ini = $_SESSION['voucher_diklaim'] ?? null;
$error_voucher = $_SESSION['error_voucher'] ?? null;
unset($_SESSION['error_voucher']);
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
            gap: 50px; 
            overflow-x: auto;
            padding-bottom: 5px;
            justify-content: center; 
            padding: 20px;
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

        .total-label {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="#">&larr; Keranjang</a>
            <a href="#">&#128722;</a>
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

        <?php
        $cartItems = [
            [
                "image" => "geprek.jpg",
                "name" => "Ayam Geprek",
                "description" => "Ayam Geprek Godzilla Meteor dengan rasa pedas yang membara",
                "price" => "Rp. 15.000",
                "quantity" => "1x"
            ],
            [
                "image" => "capcay.jpg",
                "name" => "Capcay",
                "description" => "Capcay dengan kuah kental dan berbagai macam sayuran menjadi satu hidangan",
                "price" => "Rp. 28.000",
                "quantity" => "1x"
            ],
            [
                "image" => "nasgor.jpg",
                "name" => "Nasi Goreng Seafood",
                "description" => "Nasi goreng seafood dengan aneka seafood yang fresh",
                "price" => "Rp. 20.000",
                "quantity" => "1x"
            ],
        ];

        foreach ($cartItems as $item) {
            echo '<div class="cart-item">';
            echo '<img src="' . $item["image"] . '" alt="' . $item["name"] . '">';
            echo '<div class="item-details">';
            echo '<div class="item-name">' . $item["name"] . '</div>';
            echo '<div class="item-description">' . $item["description"] . '</div>';
            echo '<div class="item-price">' . $item["price"] . '</div>';
            echo '</div>';
            echo '<div class="item-quantity">' . $item["quantity"] . '</div>';
            echo '</div>';
        }
        ?>

        <div class="recommendation-section">
            <div class="recommendation-title">Rekomendasi</div>
            <div class="recommendation-grid">
                <?php
                $recommendations = [
                    [
                        "image" => "kebab.jpg",
                        "name" => "Kebab",
                        "price" => "Rp. 17.000"
                    ],
                    [
                        "image" => "burger.jpg",
                        "name" => "Burger",
                        "price" => "Rp. 26.000"
                    ],
                    [
                        "image" => "thaitea.jpg",
                        "name" => "Thai tea",
                        "price" => "Rp. 13.000"
                    ],
                ];

                foreach ($recommendations as $recommendation) {
                    echo '<div class="recommendation-item">';
                    echo '<img src="' . $recommendation["image"] . '" alt="' . $recommendation["name"] . '">';
                    echo '<div class="recommendation-info">';
                    echo '<div class="recommendation-name">' . $recommendation["name"] . '</div>';
                    echo '<div class="recommendation-price">' . $recommendation["price"] . '</div><br>';
                    echo '<button class="recommendation-add">tambah</button>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="total-section">
            <div class="total-label">Total</div>
            <div class="total-amount">Rp. 66.000</div>
        </div>

        <div class="checkout-button-container">
            <button class="checkout-button">Checkout</button>
        </div>
    </div>
</body>
</html>