<?php
require "session.php";
require "../koneksi.php";

// Variabel form dan filter
$allowed_sort = ['id', 'user_id', 'total_amount', 'status', 'created_at'];
$allowed_order = ['asc', 'desc'];

$sort_by = in_array($_GET['sort_by'] ?? '', $allowed_sort) ? $_GET['sort_by'] : 'id';
$order = in_array(strtolower($_GET['order'] ?? ''), $allowed_order) ? strtolower($_GET['order']) : 'desc';
$search = $_GET['search'] ?? '';
$search_safe = "%" . $koneksi->real_escape_string($search) . "%";

// Inisialisasi variabel form
$user_id = "";
$order_time = "";
$pickup_time = "";
$total_price = "";
$status = "";
$address = "";
$queue_number = "";
$sukses = "";
$error = "";

// Cek operasi
$op = $_GET['op'] ?? "";

// Jika edit, ambil data berdasarkan id
if ($op == 'edit') {
    $id = $_GET['id'];

    $stmt = $koneksi->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $user_id = $data['user_id'];
        $order_time = $data['order_time'];
        $pickup_time = $data['pickup_time'];
        $total_price = $data['total_price'];
        $status = $data['status'];
        $address = $data['address'];
        $queue_number = $data['queue_number'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Proses simpan data
if (isset($_POST['simpan'])) {
    $user_id = $_POST['user_id'];
    $order_time = $_POST['order_time'];
    $pickup_time = $_POST['pickup_time'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];
    $address = $_POST['address'];
    $queue_number = $_POST['queue_number'];

    if ($user_id && $order_time && $pickup_time && $total_price && $status && $address && $queue_number) {
        if ($op == 'edit') {
            $stmt = $koneksi->prepare("UPDATE orders SET user_id = ?, order_time = ?, pickup_time = ?, total_price = ?, status = ?, address = ?, queue_number = ? WHERE id = ?");
            $stmt->bind_param("issssssi", $user_id, $order_time, $pickup_time, $total_price, $status, $address, $queue_number, $id);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO orders(user_id, order_time, pickup_time, total_price, status, address, queue_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $user_id, $order_time, $pickup_time, $total_price, $status, $address, $queue_number);
        }

        $execute = $stmt->execute();
        if ($execute) {
            $sukses = ($op == 'edit') ? "Data berhasil diperbarui" : "Data berhasil disimpan";
            $user_id = $order_time = $pickup_time = $total_price = $status = $address = $queue_number = "";
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

    $stmt = $koneksi->prepare("DELETE FROM orders WHERE id = ?");
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
    <title>Orders</title>
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
                    <i class="fa-solid fa-box me-2"></i>Orders
                </li>
            </ol>
        </nav>

        <div class="container my-5">
            <h3 class="mb-4 fw-bold">Manajemen Orders</h3>

            <!-- FORM INPUT -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-semibold">Form Input Order</div>
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
                            <label for="order_time" class="form-label">Waktu Pemesanan</label>
                            <input type="datetime-local" class="form-control" id="order_time" name="order_time" value="<?= $order_time ? date('Y-m-d\TH:i', strtotime($order_time)) : date('Y-m-d\TH:i') ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="pickup_time" class="form-label">Waktu Pengambilan</label>
                            <input type="datetime-local" class="form-control" id="pickup_time" name="pickup_time" value="<?= $pickup_time ? date('Y-m-d\TH:i', strtotime($pickup_time)) : date('Y-m-d\TH:i') ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="total_price" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="total_price" name="total_price" value="<?= htmlspecialchars($total_price) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="status" name="status" value="<?= htmlspecialchars($status) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($address) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="queue_number" class="form-label">Nomor Antrian</label>
                            <input type="text" class="form-control" id="queue_number" name="queue_number" value="<?= htmlspecialchars($queue_number) ?>" required />
                        </div>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
                    </form>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">Data Orders</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">User ID</th>
                                <th scope="col">Order Time</th>
                                <th scope="col">Pickup Time</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Status</th>
                                <th scope="col">Address</th>
                                <th scope="col">Queue Number</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($search) {
                                $stmt = $koneksi->prepare("SELECT * FROM orders WHERE CAST(user_id AS CHAR) LIKE ? OR status LIKE ? OR created_at LIKE ? ORDER BY $sort_by $order");
                                $stmt->bind_param("sss", $search_safe, $search_safe, $search_safe);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                $result = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY $sort_by $order");
                            }

                            $urut = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $urut++ ?></th>
                                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                                    <td><?= htmlspecialchars($row['order_time']) ?></td>
                                    <td><?= htmlspecialchars($row['pickup_time']) ?></td>
                                    <td><?= htmlspecialchars($row['total_price']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                    <td><?= htmlspecialchars($row['address']) ?></td>
                                    <td><?= htmlspecialchars($row['queue_number']) ?></td>
                                    <td>
                                        <a href="?op=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="?op=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger mb-1" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Tidak ada data ditemukan.</td>
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