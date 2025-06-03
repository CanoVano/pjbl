<?php
require "session.php";
require "../koneksi.php";

// Variabel form dan filter
$allowed_sort = ['id', 'user_id', 'created_at'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

// Inisialisasi variabel form
$user_id = "";
$product_id = "";
$quantity = "";
$created_at = "";
$sukses = "";
$error = "";

// Cek operasi
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Jika edit, ambil data berdasarkan id
if ($op == 'edit') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("SELECT * FROM cart WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $user_id = $data['user_id'];
        $product_id = $data['product_id'];
        $quantity = $data['quantity'];
        $created_at = $data['created_at'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $created_at = $_POST['created_at'];

    if ($user_id && $product_id && $quantity && $created_at) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE cart SET user_id = ?, product_id = ?, quantity = ?, created_at = ? WHERE id = ?");
            $stmt->bind_param("iiisi", $user_id, $product_id, $quantity, $created_at, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO cart(user_id, product_id, quantity, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $created_at);
        }

        $execute = $stmt->execute();
        if ($execute) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            // Reset form setelah berhasil simpan
            $user_id = $product_id = $quantity = $created_at = "";
        } else {
            $error = "Gagal menyimpan data";
        }
    } else {
        $error = "Isi Data Terlebih Dahulu !";
    }
}

// Proses hapus data
if ($op == 'delete') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Gagal menghapus data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cart Data</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css" />
</head>

<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="../adminpanel" class="no-decoration text-muted">
                        <i class="fa-solid fa-house me-2"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fa-solid fa-cart-shopping me-2"></i>Cart
                </li>
            </ol>
        </nav>

        <div class="container my-5">
            <h3 class="mb-4 fw-bold">Manajemen Cart</h3>

            <!-- FILTER -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Filter Data</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari User ID atau Created At..."
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-select">
                                <option value="user_id" <?= ($sort_by == 'user_id') ? 'selected' : '' ?>>User ID</option>
                                <option value="created_at" <?= ($sort_by == 'created_at') ? 'selected' : '' ?>>Created At</option>
                                <option value="id" <?= ($sort_by == 'id') ? 'selected' : '' ?>>ID</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="order" class="form-select">
                                <option value="asc" <?= ($order == 'asc') ? 'selected' : '' ?>>Naik</option>
                                <option value="desc" <?= ($order == 'desc') ? 'selected' : '' ?>>Turun</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                        </div>
                        <div class="col-md-2">
                            <a href="cart.php" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM INPUT DATA CART -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Form Input Cart</div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-warning"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($sukses): ?>
                        <div class="alert alert-success"><?= $sukses ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User ID</label>
                            <input type="number" class="form-control" id="user_id" name="user_id" value="<?= htmlspecialchars($user_id) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product ID</label>
                            <input type="number" class="form-control" id="product_id" name="product_id" value="<?= htmlspecialchars($product_id) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($quantity) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Tanggal dibuat</label>
                            <input type="datetime-local" class="form-control" id="created_at" name="created_at"
                                value="<?= $created_at ? date('Y-m-d\TH:i', strtotime($created_at)) : date('Y-m-d\TH:i') ?>" required />
                        </div>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA CART -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Cart</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Product ID</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($search) {
                                $stmt = $koneksi->prepare("SELECT * FROM cart WHERE CAST(user_id AS CHAR) LIKE ? OR created_at LIKE ? ORDER BY $sort_by $order");
                                $stmt->bind_param("ss", $search_safe, $search_safe);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                $result = mysqli_query($koneksi, "SELECT * FROM cart ORDER BY $sort_by $order");
                            }

                            $urut = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $urut++ ?></th>
                                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td>
                                        <a href="?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>
