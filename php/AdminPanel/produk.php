<?php
require "session.php";
require "../koneksi.php";

// Allowed sort & order
$allowed_columns = ['id', 'name', 'price', 'description'];
$allowed_order = ['asc', 'desc'];

$sort_column = in_array($_GET['sort'] ?? '', $allowed_columns) ? $_GET['sort'] : 'id';
$sort_order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

$nama_produk = "";
$harga = "";
$description = "";
$image = "";
$sukses = "";
$error = "";

$op = $_GET['op'] ?? "";

// Ambil data untuk edit
if ($op == 'edit') {
    $id = $_GET['id'] ?? '';

    $stmt = $koneksi->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    if ($data) {
        $nama_produk = $data['name'];
        $harga = $data['price'];
        $description = $data['description'];
        $image = $data['image'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Simpan data
if (isset($_POST['simpan'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    if ($nama_produk && $harga && $description && $image) {
        if ($op == 'edit') {
            $id = $_GET['id'];
            $stmt = $koneksi->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sdssi", $nama_produk, $harga, $description, $image, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO products(name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $nama_produk, $harga, $description, $image);
        }

        if ($stmt->execute()) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            $nama_produk = $harga = $description = $image = "";
            $op = "";
        } else {
            $error = "Gagal menyimpan data";
        }
    } else {
        $error = "Isi Data Terlebih Dahulu !";
    }
}

// Hapus data
if ($op == 'delete') {
    $id = $_GET['id'] ?? '';

    $stmt = $koneksi->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $sukses = ($stmt->affected_rows > 0) ? "Data berhasil dihapus" : "Gagal menghapus data";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/fontawesome.min.css" />
</head>
<body>
<?php require "navbar.php"; ?>
<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="../adminpanel" class="no-decoration text-muted">
                    <i class="fa-solid fa-house me-2"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item active">
                <i class="fa-solid fa-burger me-2"></i>Produk
            </li>
        </ol>
    </nav>

    <h3 class="mb-4 fw-bold">Manajemen Produk</h3>

    <!-- FILTER -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">Filter & Sorting</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>" />
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <?php foreach ($allowed_columns as $col): ?>
                            <option value="<?= $col ?>" <?= ($sort_column == $col) ? 'selected' : '' ?>>
                                <?= ucfirst($col) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order" class="form-select">
                        <option value="asc" <?= ($sort_order == 'asc') ? 'selected' : '' ?>>Naik</option>
                        <option value="desc" <?= ($sort_order == 'desc') ? 'selected' : '' ?>>Turun</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                </div>
                <div class="col-md-2">
                    <a href="produk.php" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- FORM INPUT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold"><?= ($op == 'edit') ? "Edit Produk" : "Tambah Produk" ?></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-warning"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($sukses): ?>
                <div class="alert alert-success"><?= $sukses ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= ($op == 'edit') ? "produk.php?op=edit&id=" . htmlspecialchars($_GET['id']) : "produk.php" ?>">
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($nama_produk) ?>" required />
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" step="0.01" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($harga) ?>" required />
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($description) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">URL Gambar</label>
                    <input type="text" class="form-control" id="image" name="image" value="<?= htmlspecialchars($image) ?>" required />
                </div>
                <button type="submit" name="simpan" class="btn btn-success"><?= ($op == 'edit') ? "Update Data" : "Simpan Data" ?></button>
                <?php if ($op == 'edit'): ?>
                    <a href="produk.php" class="btn btn-secondary ms-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold">Data Produk</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($search) {
                        $stmt = $koneksi->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY $sort_column $sort_order");
                        $stmt->bind_param("ss", $search_safe, $search_safe);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $sql = "SELECT * FROM products ORDER BY $sort_column $sort_order";
                        $result = mysqli_query($koneksi, $sql);
                    }

                    $no = 1;
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td>
                                    <?php if ($row['image']): ?>
                                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Gambar" style="max-height: 50px;">
                                    <?php else: ?>- <?php endif; ?>
                                </td>
                                <td>
                                    <a href="produk.php?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="produk.php?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus data ini?')" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
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
