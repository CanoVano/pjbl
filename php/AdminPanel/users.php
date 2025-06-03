<?php
require "session.php";
require "../koneksi.php";

// Filter & Sorting
$allowed_sort = ['id', 'fullname', 'username', 'email'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'asc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

$id = "";
$fullname = "";
$username = "";
$email = "";
$password = "";
$profile_picture = "";

$sukses = "";
$error = "";

$op = $_GET['op'] ?? "";

// Ambil data jika edit
if ($op == 'edit') {
    $id = $_GET['id'] ?? '';
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $id = $data['id'];
        $fullname = $data['fullname'];
        $username = $data['username'];
        $email = $data['email'];
        $password = "";
        $profile_picture = $data['profile_picture'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Simpan data
if (isset($_POST['simpan'])) {
    $fullname = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    $profile_picture = $_POST['profile_picture'] ?? '';

    if ($fullname && $username && $email) {
        if ($op == 'edit') {
            if (!empty($password_raw)) {
                $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare("UPDATE users SET fullname=?, username=?, email=?, password=?, profile_picture=? WHERE id=?");
                $stmt->bind_param("sssssi", $fullname, $username, $email, $password_hashed, $profile_picture, $id);
            } else {
                $stmt = $koneksi->prepare("UPDATE users SET fullname=?, username=?, email=?, profile_picture=? WHERE id=?");
                $stmt->bind_param("ssssi", $fullname, $username, $email, $profile_picture, $id);
            }
        } else {
            if (empty($password_raw)) {
                $error = "Password wajib diisi";
            } else {
                $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
                $stmt = $koneksi->prepare("INSERT INTO users(fullname, username, email, password, profile_picture) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $fullname, $username, $email, $password_hashed, $profile_picture);
            }
        }

        if (empty($error)) {
            if ($stmt->execute()) {
                $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
                $fullname = $username = $email = $password = $profile_picture = "";
                $op = "";
            } else {
                $error = "Gagal menyimpan data";
            }
        }
    } else {
        $error = "Isi Data Terlebih Dahulu !";
    }
}

// Hapus data
if ($op == 'delete') {
    $id = $_GET['id'] ?? '';
    $stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $sukses = ($stmt->affected_rows > 0) ? "Data berhasil dihapus" : "Gagal menghapus data";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Manajemen Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                <i class="fa-solid fa-users me-2"></i>Users
            </li>
        </ol>
    </nav>

    <h3 class="mb-4 fw-bold">Manajemen Users</h3>

    <!-- FILTER -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold">Filter & Sorting</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama/email/username..." value="<?= htmlspecialchars($search) ?>" />
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-select">
                        <?php foreach ($allowed_sort as $col): ?>
                            <option value="<?= $col ?>" <?= ($sort_by == $col) ? 'selected' : '' ?>>
                                <?= ucfirst($col) ?>
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
                    <a href="users.php" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- FORM INPUT -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-semibold"><?= ($op == 'edit') ? "Edit User" : "Tambah User" ?></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-warning"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($sukses): ?>
                <div class="alert alert-success"><?= $sukses ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="fullname" class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required />
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="<?= $op == 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : 'Isi password' ?>"
                        <?= $op == 'edit' ? '' : 'required' ?> />
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label fw-semibold">Profile Picture URL</label>
                    <input type="text" class="form-control" id="profile_picture" name="profile_picture" value="<?= htmlspecialchars($profile_picture) ?>" />
                </div>
                <button type="submit" name="simpan" class="btn btn-success"><?= ($op == 'edit') ? "Update Data" : "Simpan Data" ?></button>
            </form>
        </div>
    </div>

    <!-- TABEL -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold">Data Users</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Profile</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($search) {
                        $stmt = $koneksi->prepare("SELECT * FROM users WHERE fullname LIKE ? OR username LIKE ? OR email LIKE ? ORDER BY $sort_by $order");
                        $stmt->bind_param("sss", $search_safe, $search_safe, $search_safe);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    } else {
                        $result = mysqli_query($koneksi, "SELECT * FROM users ORDER BY $sort_by $order");
                    }

                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <?php if ($row['profile_picture']) : ?>
                                    <img src="<?= htmlspecialchars($row['profile_picture']) ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" />
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?op=edit&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="6" class="text-center text-muted">Tidak ada data ditemukan.</td></tr>
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
