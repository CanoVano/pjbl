<?php
require "session.php";
require "../koneksi.php";

// Filter & Sorting
$allowed_sort = ['id', 'title', 'content', 'created_at'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

$main_post_id = "";
$suggested_post_id = "";
$sukses = "";
$error = "";

$op = $_GET['op'] ?? "";

// Ambil data jika edit
if ($op == 'edit') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("SELECT * FROM suggested_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $main_post_id = $data['main_post_id'];
        $suggested_post_id = $data['suggested_post_id'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Simpan data
if (isset($_POST['simpan'])) {
    $main_post_id = $_POST['main_post_id'];
    $suggested_post_id = $_POST['suggested_post_id'];

    if ($main_post_id && $suggested_post_id) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE suggested_posts SET main_post_id = ?, suggested_post_id = ? WHERE id = ?");
            $stmt->bind_param("iii", $main_post_id, $suggested_post_id, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO suggested_posts(main_post_id, suggested_post_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $main_post_id, $suggested_post_id);
        }

        if ($stmt->execute()) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            $main_post_id = $suggested_post_id = "";
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
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("DELETE FROM suggested_posts WHERE id = ?");
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
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Suggested Posts</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css" />
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
                <i class="fa-solid fa-thumbs-up me-2"></i>Suggested Posts
            </li>
        </ol>
    </nav>

    <h3 class="mb-4 fw-bold">Manajemen Suggested Posts</h3>

    <!-- FILTER -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">Filter & Sorting</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari ID..." value="<?= htmlspecialchars($search) ?>" />
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-select">
                        <?php foreach ($allowed_sort as $col): ?>
                            <option value="<?= $col ?>" <?= ($sort_by == $col) ? 'selected' : '' ?>>
                                <?= ucfirst(str_replace('_', ' ', $col)) ?>
                            </option>
                        <?php endforeach; ?>
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
                    <a href="suggested_posts.php" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- FORM INPUT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold"><?= ($op == 'edit') ? "Edit Suggested Post" : "Tambah Suggested Post" ?></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-warning"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($sukses): ?>
                <div class="alert alert-success"><?= $sukses ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= ($op == 'edit') ? "suggested_posts.php?op=edit&id=" . htmlspecialchars($_GET['id']) : "suggested_posts.php" ?>">
                <div class="mb-3">
                    <label for="main_post_id" class="form-label">Main Post ID</label>
                    <input type="number" class="form-control" id="main_post_id" name="main_post_id" value="<?= htmlspecialchars($main_post_id) ?>" <?= ($op == 'edit') ? 'readonly' : '' ?> required />
                </div>
                <div class="mb-3">
                    <label for="suggested_post_id" class="form-label">Suggested Post ID</label>
                    <input type="number" class="form-control" id="suggested_post_id" name="suggested_post_id" value="<?= htmlspecialchars($suggested_post_id) ?>" required />
                </div>
                <button type="submit" name="simpan" class="btn btn-success"><?= ($op == 'edit') ? "Update Data" : "Simpan Data" ?></button>
                <?php if ($op == 'edit'): ?>
                    <a href="suggested_posts.php" class="btn btn-secondary ms-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold">Data Suggested Posts</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Main Post ID</th>
                        <th>Suggested Post ID</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($search) {
                        $stmt = $koneksi->prepare("SELECT * FROM suggested_posts WHERE CAST(main_post_id AS CHAR) LIKE ? OR CAST(suggested_post_id AS CHAR) LIKE ? ORDER BY $sort_by $order");
                        $stmt->bind_param("ss", $search_safe, $search_safe);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = mysqli_query($koneksi, "SELECT * FROM suggested_posts ORDER BY $sort_by $order");
                    }

                    $no = 1;
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['main_post_id']) ?></td>
                        <td><?= htmlspecialchars($row['suggested_post_id']) ?></td>
                        <td>
                            <a href="suggested_posts.php?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="suggested_posts.php?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus data ini?')" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="4" class="text-center">Data tidak ditemukan.</td></tr>
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
