<?php
require "session.php";
require "../koneksi.php";

// Variabel filter & sorting
$allowed_sort = ['id', 'user_id', 'created_at', 'updated_at'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $conn->real_escape_string($search) . "%";

// Variabel form
$user_id = $product_id = $quantity = $created_at = $updated_at = "";
$sukses = $error = "";

// Cek operasi
$op = $_GET['op'] ?? "";

// Ambil data untuk edit
if ($op == 'edit') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM user_carts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    if ($data) {
        $user_id = $data['user_id'];
        $product_id = $data['product_id'];
        $quantity = $data['quantity'];
        $created_at = $data['created_at'];
        $updated_at = $data['updated_at'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Simpan data
if (isset($_POST['simpan'])) {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $created_at = $_POST['created_at'];
    $updated_at = date('Y-m-d H:i:s');

    if ($user_id && $product_id && $quantity && $created_at) {
        if ($op == 'edit') {
            $stmt = $conn->prepare("UPDATE user_carts SET user_id=?, product_id=?, quantity=?, created_at=?, updated_at=? WHERE id=?");
            $stmt->bind_param("iiissi", $user_id, $product_id, $quantity, $created_at, $updated_at, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO user_carts(user_id, product_id, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $user_id, $product_id, $quantity, $created_at, $updated_at);
        }

        if ($stmt->execute()) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            $user_id = $product_id = $quantity = $created_at = $updated_at = "";
        } else {
            $error = "Gagal menyimpan data";
        }
    } else {
        $error = "Isi Data Terlebih Dahulu!";
    }
}

// Hapus data
if ($op == 'delete') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM user_carts WHERE id = ?");
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
    <title>User Cart Management</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../fontawesome/css/all.min.css" />
</head>

<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="../adminpanel" class="text-muted"><i class="fa fa-home me-2"></i>Home</a>
                </li>
                <li class="breadcrumb-item active">User Carts</li>
            </ol>
        </nav>

        <h3 class="my-4 fw-bold">Manajemen User Carts</h3>

        <!-- Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">Filter Data</div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari User ID atau Created At..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                    </div>
                    <div class="col-md-2">
                        <select name="sort_by" class="form-select">
                            <option value="user_id" <?= ($sort_by == 'user_id') ? 'selected' : '' ?>>User ID</option>
                            <option value="created_at" <?= ($sort_by == 'created_at') ? 'selected' : '' ?>>Created At
                            </option>
                            <option value="updated_at" <?= ($sort_by == 'updated_at') ? 'selected' : '' ?>>Updated At
                            </option>
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

        <!-- Form Input -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-semibold">Form Input Cart</div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-warning"><?= $error ?></div>
                <?php endif; ?>
                <?php if ($sukses): ?>
                    <div class="alert alert-success"><?= $sukses ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User ID</label>
                        <input type="number" class="form-control" id="user_id" name="user_id"
                            value="<?= htmlspecialchars($user_id) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product ID</label>
                        <input type="number" class="form-control" id="product_id" name="product_id"
                            value="<?= htmlspecialchars($product_id) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            value="<?= htmlspecialchars($quantity) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="form-label">Tanggal Dibuat</label>
                        <?php
                        $created_input_value = $created_at ? date('Y-m-d\TH:i', strtotime($created_at)) : date('Y-m-d\TH:i');
                        ?>
                        <input type="datetime-local" class="form-control" id="created_at" name="created_at"
                            value="<?= $created_input_value ?>" required />

                    </div>
                    <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">Data User Carts</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>User ID</th>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($search) {
                            $stmt = $conn->prepare("SELECT * FROM user_carts WHERE CAST(user_id AS CHAR) LIKE ? OR created_at LIKE ? ORDER BY $sort_by $order");
                            $stmt->bind_param("ss", $search_safe, $search_safe);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        } else {
                            $result = mysqli_query($conn, "SELECT * FROM user_carts ORDER BY $sort_by $order");
                        }

                        $urut = 1;
                        while ($row = mysqli_fetch_assoc($result)):
                            ?>
                            <tr>
                                <td><?= $urut++ ?></td>
                                <td><?= htmlspecialchars($row['user_id']) ?></td>
                                <td><?= htmlspecialchars($row['product_id']) ?></td>
                                <td><?= htmlspecialchars($row['quantity']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td><?= htmlspecialchars($row['updated_at']) ?></td>
                                <td>
                                    <a href="?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1"
                                        title="Edit"><i class="fa fa-pen-to-square"></i></a>
                                    <a href="?op=delete&id=<?= $row['id'] ?>"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                        class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>

</html>