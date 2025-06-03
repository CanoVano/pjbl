<?php
require "session.php";
require "../koneksi.php";

// Filter / Search sederhana (optional)
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

$title = "";
$content = "";
$image = "";
$kategori = "";
$tanggal = "";
$is_featured = "";
$is_popular = "";
$summary = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// EDIT DATA - mengambil data produk yang akan diedit
if ($op == 'edit') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("SELECT * FROM blog_posts WHERE blog_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $r1 = $result->fetch_assoc();

    if ($r1) {
        $title = $r1['title'];
        $content = $r1['content'];
        $image = $r1['image_path'];
        $kategori = $r1['category_id'];
        $tanggal = date('Y-m-d', strtotime($r1['publication_date']));
        $is_featured = $r1['is_featured'];
        $is_popular = $r1['is_popular'];
        $summary = $r1['summary'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// SIMPAN DATA - proses simpan atau update
if (isset($_POST['simpan'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image'];
    $kategori = $_POST['kategori'];
    $tanggal = $_POST['tanggal'];
    $is_featured = $_POST['is_featured'];
    $is_popular = $_POST['is_popular'];
    $summary = $_POST['summary'];

    if ($title && $content && $image && $kategori && $tanggal) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE blog_posts SET title = ?, content = ?, image_path = ?, category_id = ?, publication_date = ?, is_featured = ?, is_popular = ?, summary = ? WHERE blog_id = ?");
            $stmt->bind_param("ssssssssi", $title, $content, $image, $kategori, $tanggal, $is_featured, $is_popular, $summary, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO blog_posts(title, content, image_path, category_id, publication_date, is_featured, is_popular, summary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $title, $content, $image, $kategori, $tanggal, $is_featured, $is_popular, $summary);
        }

        if ($stmt->execute()) {
            $sukses = $op == 'edit' ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            if ($op != 'edit') {
                // Reset form hanya kalau tambah data baru
                $title = $content = $image = $kategori = $tanggal = $is_featured = $is_popular = $summary = "";
            }
        } else {
            $error = "Gagal memproses data";
        }
    } else {
        $error = "Isi Data Terlebih Dahulu !";
    }
}

// Proses hapus data (optional)
if ($op == 'delete') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("DELETE FROM blog_posts WHERE blog_id = ?");
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
    <title>Manajemen Blog Post</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css" />
    <style>
        .no-decoration {
            text-decoration: none;
        }
    </style>
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
                    <i class="fa-solid fa-scroll me-2"></i>Blog Post
                </li>
            </ol>
        </nav>

        <div class="container my-5">
            <h3 class="mb-4 fw-bold">Manajemen Blog Post</h3>

            <!-- FILTER DATA -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Filter Produk</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan title..." value="<?= htmlspecialchars($search) ?>" />
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                        </div>
                        <div class="col-md-3">
                            <a href="blog_posts.php" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM INPUT PRODUK -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold"><?= $op == 'edit' ? 'Edit Produk' : 'Tambah Produk Baru' ?></div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-warning"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($sukses): ?>
                        <div class="alert alert-success"><?= $sukses ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Nama Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="kategori" class="form-label">Category ID</label>
                                <input type="text" class="form-control" id="kategori" name="kategori" value="<?= htmlspecialchars($kategori) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Content</label>
                            <textarea class="form-control" id="content" name="content" rows="4" required><?= htmlspecialchars($content) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image Path</label>
                            <input type="text" class="form-control" id="image" name="image" value="<?= htmlspecialchars($image) ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tanggal" class="form-label">Publication Date</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="is_featured" class="form-label">Is Featured</label>
                                <select class="form-select" id="is_featured" name="is_featured" required>
                                    <option value="1" <?= $is_featured == '1' ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= $is_featured == '0' ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="is_popular" class="form-label">Is Popular</label>
                                <select class="form-select" id="is_popular" name="is_popular" required>
                                    <option value="1" <?= $is_popular == '1' ? 'selected' : '' ?>>Yes</option>
                                    <option value="0" <?= $is_popular == '0' ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" id="summary" name="summary" rows="3"><?= htmlspecialchars($summary) ?></textarea>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-success w-100"><?= $op == 'edit' ? 'Update Produk' : 'Simpan Produk' ?></button>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA PRODUK -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Produk</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0 table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Image</th>
                                <th>Category ID</th>
                                <th>Publication Date</th>
                                <th>Featured</th>
                                <th>Popular</th>
                                <th>Summary</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM blog_posts WHERE title LIKE ? ORDER BY blog_id DESC";
                            $stmt = $koneksi->prepare($query);
                            $stmt->bind_param("s", $search_safe);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0):
                                $no = 1;
                                while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars(substr($row['content'], 0, 50)) ?>...</td>
                                <td>
                                    <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Gambar" width="60" height="60" class="rounded">
                                </td>
                                <td class="text-center"><?= htmlspecialchars($row['category_id']) ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row['publication_date'])) ?></td>
                                <td class="text-center"><?= $row['is_featured'] == '1' ? 'Ya' : 'Tidak' ?></td>
                                <td class="text-center"><?= $row['is_popular'] == '1' ? 'Ya' : 'Tidak' ?></td>
                                <td><?= htmlspecialchars(substr($row['summary'], 0, 30)) ?>...</td>
                                <td class="text-center">
                                    <a href="?op=edit&id=<?= $row['blog_id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="?op=delete&id=<?= $row['blog_id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data</td>
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
