<?php
session_start();
include 'koneksi.php';

$cart = $_SESSION['cart'] ?? [];

$cart_products = [];
if (!empty($cart)) {
    // Ambil data produk yang ada di cart
    $ids = implode(',', array_keys($cart));
    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $cart[$row['id']];
        $cart_products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Keranjang Belanja</title>
    <style>
        table {
            width: 70%;
            margin: auto;
            border-collapse: collapse;
            margin-top: 40px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #f6ab0e;
            color: #000;
        }
        .view-all-btn {
            margin-top: 20px;
            padding: 10px 20px;
            display: block;
            width: 200px;
            text-align: center;
            background: #A0E7A0;
            color: black;
            text-decoration: none;
            margin-left: auto;
            margin-right: auto;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2 style="text-align:center; margin-top: 20px;">Keranjang Belanja</h2>

<?php if (empty($cart_products)): ?>
    <p style="text-align:center;">Keranjang kosong.</p>
<?php else: ?>
<table>
    <tr>
        <th>Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
    <?php foreach ($cart_products as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="checkout.php" class="view-all-btn">Lanjut ke Checkout</a>
<?php endif; ?>

</body>
</html>