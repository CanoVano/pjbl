<?php
require "session.php";
require "../koneksi.php";

// Variabel filter dan sorting
$allowed_sort = ['id', 'email', 'created_at'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

// Inisialisasi form
$email = "";
$buat = "";
$sukses = "";
$error = "";

// Cek operasi
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Ambil data jika edit
if ($op == 'edit') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("SELECT * FROM newsletter_subscribers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $email = $data['email'];
        $buat = $data['created_at'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $email = trim($_POST['email']);
    $buat = $_POST['buat'];

    if ($email && $buat) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE newsletter_subscribers SET email = ?, created_at = ? WHERE id = ?");
            $stmt->bind_param("ssi", $email, $buat, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO newsletter_subscribers(email, created_at) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $buat);
        }

        if ($stmt->execute()) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            // Reset form
            if ($op != 'edit') {
                $email = "";
                $buat = "";
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
    $stmt = $koneksi->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
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
    <title>Manajemen Subscriber Newsletter</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../fontawesome/css/fontawesome.min.css" />
</head>

<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="../adminpanel" class="text-decoration-none text-muted">
                        <i class="fa-solid fa-house me-2"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fa-solid fa-user-plus me-2"></i>Subscriber Newsletter
                </li>
            </ol>
        </nav>

        <div class="container my-5">
            <h3 class="mb-4 fw-bold">Manajemen Subscriber Newsletter</h3>

            <!-- FILTER DATA -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Filter Data</div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan email..."
                                value="<?= htmlspecialchars($search) ?>" />
                        </div>
                        <div class="col-md-3">
                            <select name="sort_by" class="form-select">
                                <option value="email" <?= ($sort_by == 'email') ? 'selected' : '' ?>>Email</option>
                                <option value="created_at" <?= ($sort_by == 'created_at') ? 'selected' : '' ?>>Tanggal Dibuat</option>
                                <option value="id" <?= ($sort_by == 'id') ? 'selected' : '' ?>>ID</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="order" class="form-select">
                                <option value="asc" <?= ($order == 'asc') ? 'selected' : '' ?>>Naik</option>
                                <option value="desc" <?= ($order == 'desc') ? 'selected' : '' ?>>Turun</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                        </div>
                        <div class="col-md-2">
                            <a href="newsletter.php" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM INPUT DATA -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Form Input Subscriber</div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-warning"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($sukses): ?>
                        <div class="alert alert-success"><?= $sukses ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="buat" class="form-label">Tanggal dibuat</label>
                            <input type="datetime-local" class="form-control" id="buat" name="buat"
                                value="<?= $buat ? date('Y-m-d\TH:i', strtotime($buat)) : date('Y-m-d\TH:i') ?>" required />
                        </div>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA SUBSCRIBER -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Subscriber</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Email</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($search) {
                                $stmt = $koneksi->prepare("SELECT * FROM newsletter_subscribers WHERE email LIKE ? OR created_at LIKE ? ORDER BY $sort_by $order");
                                $stmt->bind_param("ss", $search_safe, $search_safe);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                $result = mysqli_query($koneksi, "SELECT * FROM newsletter_subscribers ORDER BY $sort_by $order");
                            }

                            $urut = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $urut++ ?></th>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td>
                                        <a href="?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>

</html>
