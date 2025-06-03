<?php
require "session.php";
require "../koneksi.php";

// Variabel form dan filter
$allowed_sort = ['id', 'order_id', 'product_id', 'quantity', 'price'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

// Inisialisasi variabel form
$order_id = $product_id = $quantity = $price_at_order = $part = "";
$sukses = "";
$error = "";

$op = $_GET['op'] ?? "";

// Jika edit, ambil data berdasarkan id
if ($op == 'edit') {
    $id = $_GET['id'];
    $stmt = $koneksi->prepare("SELECT * FROM order_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $order_id = $data['order_id'];
        $product_id = $data['product_id'];
        $quantity = $data['quantity'];
        $price_at_order = $data['price_at_order'];
        $part = $data['part'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price_at_order = $_POST['price_at_order'];
    $part = $_POST['part'];

    if ($order_id && $product_id && $quantity && $price_at_order && $part) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE order_items SET order_id=?, product_id=?, quantity=?, price_at_order=?, part=? WHERE id=?");
            $stmt->bind_param("iiiisi", $order_id, $product_id, $quantity, $price_at_order, $part, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_order, part) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiis", $order_id, $product_id, $quantity, $price_at_order, $part);
        }

        if ($stmt->execute()) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            // Reset form
            $order_id = $product_id = $quantity = $price_at_order = $part = "";
        } else {
            $error = "Gagal menyimpan data";
        }
    } else {
        $error = "Isi semua data terlebih dahulu!";
    }
}

// Proses hapus data
if ($op == 'delete') {
    $id = $_GET['id'];
    $stmt = $koneksi->prepare("DELETE FROM order_items WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $sukses = ($stmt->affected_rows > 0) ? "Data berhasil dihapus" : "Gagal menghapus data";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Order Items</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css" />
</head>
<body>
<?php require "navbar.php"; ?>
<div class="container mt-5">
    <h3 class="fw-bold mb-4">Manajemen Order Items</h3>

    <!-- Filter -->
    <form class="row g-3 mb-4" method="GET">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari Order ID atau Product ID" value="<?= htmlspecialchars($search) ?>" />
        </div>
        <div class="col-md-2">
            <select name="sort_by" class="form-select">
                <option value="order_id" <?= $sort_by == 'order_id' ? 'selected' : '' ?>>Order ID</option>
                <option value="product_id" <?= $sort_by == 'product_id' ? 'selected' : '' ?>>Product ID</option>
                <option value="id" <?= $sort_by == 'id' ? 'selected' : '' ?>>ID</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="order" class="form-select">
                <option value="asc" <?= $order == 'asc' ? 'selected' : '' ?>>Naik</option>
                <option value="desc" <?= $order == 'desc' ? 'selected' : '' ?>>Turun</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
        </div>
        <div class="col-md-2">
            <a href="order_items.php" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    <!-- Form Input -->
    <div class="card mb-4">
        <div class="card-header">Form Input Order Item</div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-warning"><?= $error ?></div><?php endif; ?>
            <?php if ($sukses): ?><div class="alert alert-success"><?= $sukses ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Order ID</label>
                    <input type="number" name="order_id" class="form-control" value="<?= htmlspecialchars($order_id) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Product ID</label>
                    <input type="number" name="product_id" class="form-control" value="<?= htmlspecialchars($product_id) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($quantity) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Price at Order</label>
                    <input type="number" step="0.01" name="price_at_order" class="form-control" value="<?= htmlspecialchars($price_at_order) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Part</label>
                    <input type="text" name="part" class="form-control" value="<?= htmlspecialchars($part) ?>" required>
                </div>
                <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card">
        <div class="card-header">Data Order Items</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Order ID</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Price at Order</th>
                        <th>Part</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($search) {
                    $stmt = $koneksi->prepare("SELECT * FROM order_items WHERE CAST(order_id AS CHAR) LIKE ? OR CAST(product_id AS CHAR) LIKE ? ORDER BY $sort_by $order");
                    $stmt->bind_param("ss", $search_safe, $search_safe);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $result = mysqli_query($koneksi, "SELECT * FROM order_items ORDER BY $sort_by $order");
                }

                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['order_id']) . "</td>
                        <td>" . htmlspecialchars($row['product_id']) . "</td>
                        <td>" . htmlspecialchars($row['quantity']) . "</td>
                        <td>" . htmlspecialchars($row['price_at_order']) . "</td>
                        <td>" . htmlspecialchars($row['part']) . "</td>
                        <td>
                            <a href='?op=edit&id={$row['id']}' class='btn btn-sm btn-warning mb-1'><i class='fa fa-edit'></i></a>
                            <a href='?op=delete&id={$row['id']}' onclick='return confirm(\"Yakin ingin menghapus?\")' class='btn btn-sm btn-danger mb-1'><i class='fa fa-trash'></i></a>
                        </td>
                    </tr>";
                    $no++;
                }

                if (mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='7' class='text-center text-muted'>Tidak ada data.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../fontawesome/js/all.min.js"></script>
</body>
</html>
