<?php
require "session.php";
require "../koneksi.php";

// Variabel form dan filter
$allowed_sort = ['id', 'user_id', 'product_id', 'rating', 'tanggal'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

// Inisialisasi variabel form
$user_id = "";
$product_id = "";
$rating = "";
$komentar = "";
$image = "";
$tanggal = "";
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

    $stmt = $koneksi->prepare("SELECT * FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $user_id = $data['user_id'];
        $product_id = $data['product_id'];
        $rating = $data['rating'];
        $komentar = $data['komentar'];
        $image = $data['image'];
        $tanggal = $data['tanggal'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];
    $image = $_POST['image'];
    $tanggal = $_POST['tanggal'];

    if ($user_id && $product_id && $rating && $komentar && $tanggal) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE reviews SET user_id = ?, product_id = ?, rating = ?, komentar = ?, image = ?, tanggal = ? WHERE id = ?");
            $stmt->bind_param("iiisssi", $user_id, $product_id, $rating, $komentar, $image, $tanggal, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO reviews(user_id, product_id, rating, komentar, image, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisss", $user_id, $product_id, $rating, $komentar, $image, $tanggal);
        }

        $execute = $stmt->execute();
        if ($execute) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            // Reset form setelah berhasil simpan
            $user_id = $product_id = $rating = $komentar = $image = $tanggal = "";
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

    $stmt = $koneksi->prepare("DELETE FROM reviews WHERE id = ?");
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
    <title>Reviews</title>
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
                    <i class="fa-solid fa-star me-2"></i>Review
                </li>
            </ol>
        </nav>

        <div class="container my-5">
            <h3 class="mb-4 fw-bold">Manajemen Review</h3>

            <!-- FILTER -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Filter Data</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari User ID, Komentar, atau Tanggal..."
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-select">
                                <option value="user_id" <?= ($sort_by == 'user_id') ? 'selected' : '' ?>>User ID</option>
                                <option value="tanggal" <?= ($sort_by == 'tanggal') ? 'selected' : '' ?>>Tanggal</option>
                                <option value="id" <?= ($sort_by == 'komentar') ? 'selected' : '' ?>>Komentar</option>
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
                            <a href="review.php" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM INPUT DATA REVIEW -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Form Input Review</div>
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
                            <label for="rating" class="form-label">Rating (1-5)</label>
                            <input type="number" min="1" max="5" class="form-control" id="rating" name="rating" value="<?= htmlspecialchars($rating) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="komentar" class="form-label">Komentar</label>
                            <textarea class="form-control" id="komentar" name="komentar" rows="3" required><?= htmlspecialchars($komentar) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image URL</label>
                            <input type="text" class="form-control" id="image" name="image" value="<?= htmlspecialchars($image) ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="datetime-local" class="form-control" id="tanggal" name="tanggal"
                                value="<?= $tanggal ? date('Y-m-d\TH:i', strtotime($tanggal)) : date('Y-m-d\TH:i') ?>" required />
                        </div>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA REVIEW -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Review</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Product ID</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Komentar</th>
                                <th scope="col">Image</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($search) {
                                $stmt = $koneksi->prepare("SELECT * FROM review WHERE CAST(user_id AS CHAR) LIKE ? OR CAST(product_id AS CHAR) LIKE ? OR komentar LIKE ? OR tanggal LIKE ? ORDER BY $sort_by $order");
                                $stmt->bind_param("ssss", $search_safe, $search_safe, $search_safe, $search_safe);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                $result = mysqli_query($koneksi, "SELECT * FROM review ORDER BY $sort_by $order");
                            }

                            $urut = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $urut++ ?></th>
                                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                                    <td><?= htmlspecialchars($row['product_id']) ?></td>
                                    <td><?= htmlspecialchars($row['rating']) ?></td>
                                    <td><?= htmlspecialchars($row['komentar']) ?></td>
                                    <td>
                                        <?php if ($row['image']): ?>
                                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Image Review" style="max-width: 80px; max-height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                    <td>
                                        <a href="?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada data ditemukan.</td>
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
