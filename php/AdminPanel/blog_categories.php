<?php
require "session.php";
require "../koneksi.php";

// Variabel form dan filter
$allowed_sort = ['category_id', 'name', 'description'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'category_id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

// Inisialisasi variabel form
$nama = "";
$description = "";
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

    $stmt = $koneksi->prepare("SELECT * FROM blog_categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $nama = $data['name'];
        $description = $data['description'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama']);
    $description = trim($_POST['description']);

    if ($nama && $description) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE blog_categories SET name = ?, description = ? WHERE category_id = ?");
            $stmt->bind_param("ssi", $nama, $description, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO blog_categories(name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $nama, $description);
        }

        $execute = $stmt->execute();
        if ($execute) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            // Reset form setelah berhasil simpan
            if ($op != 'edit') {
                $nama = "";
                $description = "";
            }
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

    $stmt = $koneksi->prepare("DELETE FROM blog_categories WHERE category_id = ?");
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
    <title>Kategori Blog</title>
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
                    <i class="fa-solid fa-newspaper me-2"></i>Kategori Blog
                </li>
            </ol>
        </nav>

        <div class="container my-5">
            <h3 class="mb-4 fw-bold">Manajemen Kategori Blog</h3>

            <!-- FILTER -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Filter Data</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama atau deskripsi..."
                                value="<?= htmlspecialchars($search) ?>" />
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-select">
                                <option value="name" <?= ($sort_by == 'name') ? 'selected' : '' ?>>Nama</option>
                                <option value="description" <?= ($sort_by == 'description') ? 'selected' : '' ?>>Deskripsi</option>
                                <option value="category_id" <?= ($sort_by == 'category_id') ? 'selected' : '' ?>>ID</option>
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
                            <a href="blog_categories.php" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM INPUT DATA KATEGORI -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Form Input Kategori</div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-warning"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($sukses): ?>
                        <div class="alert alert-success"><?= $sukses ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" id="description" name="description" value="<?= htmlspecialchars($description) ?>" required />
                        </div>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA KATEGORI -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Kategori Blog</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($search) {
                                $stmt = $koneksi->prepare("SELECT * FROM blog_categories WHERE name LIKE ? OR description LIKE ? ORDER BY $sort_by $order");
                                $stmt->bind_param("ss", $search_safe, $search_safe);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                $sql = "SELECT * FROM blog_categories ORDER BY $sort_by $order";
                                $result = mysqli_query($koneksi, $sql);
                            }

                            $urut = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $urut++ ?></th>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td>
                                        <a href="?op=edit&id=<?= $row['category_id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="?op=delete&id=<?= $row['category_id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            if (mysqli_num_rows($result) == 0) {
                                echo '<tr><td colspan="4" class="text-center text-muted">Tidak ada data ditemukan.</td></tr>';
                            }
                            ?>
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
