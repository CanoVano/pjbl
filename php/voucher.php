<?php
session_start();
include 'koneksi.php';

// Initialize used_vouchers array in session if not exists
if (!isset($_SESSION['used_vouchers'])) {
    $_SESSION['used_vouchers'] = [];
}

// Atur array voucher yang tersedia
$voucher_tersedia = [
    "DISKON25" => ["nilai" => 25, "jenis" => "persen", "min_pembelian" => 50000, "deskripsi" => "Min. pembelian 50rb"],
    "DISKON50" => ["nilai" => 50, "jenis" => "persen", "min_pembelian" => 100000, "deskripsi" => "Min. pembelian 100rb"],
    "DISKON5"  => ["nilai" => 5,  "jenis" => "persen", "min_pembelian" => 0,      "deskripsi" => "Tanpa min. pembelian"],
];

$voucher_diklaim_saat_ini = $_SESSION['voucher_diklaim'] ?? null;

// Check for 2-day lockout
$lockout_message = '';
$lockout_time = $_SESSION['voucher_lockout_time'] ?? 0;
$lockout_duration = 2 * 24 * 60 * 60; // 2 days in seconds

if ($lockout_time > 0 && (time() - $lockout_time) < $lockout_duration) {
    $unlock_time = $lockout_time + $lockout_duration;
    $lockout_message = "Anda telah menggunakan semua voucher yang tersedia. Voucher baru akan tersedia lagi pada " . date('d/m/Y H:i', $unlock_time) . ".";
    // Clear claimed voucher in session if under lockout
    unset($_SESSION['voucher_diklaim']);
    $voucher_diklaim_saat_ini = null;
} else if ($lockout_time > 0 && (time() - $lockout_time) >= $lockout_duration) {
    // Lockout period over, reset session variables
    unset($_SESSION['voucher_lockout_time']);
    $_SESSION['used_vouchers'] = [];
}

// Proses klaim voucher
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['klaim_voucher']) && empty($lockout_message)) {
    $kode_voucher_diklaim = $_POST['kode_voucher'];
    
    if (isset($voucher_tersedia[$kode_voucher_diklaim])) {
        // Check if voucher already used in this session
        if (!in_array($kode_voucher_diklaim, $_SESSION['used_vouchers'])) {
            $_SESSION['voucher_diklaim'] = $kode_voucher_diklaim;
            $_SESSION['used_vouchers'][] = $kode_voucher_diklaim; // Mark as used in session
            $_SESSION['success_message'] = "Voucher " . $kode_voucher_diklaim . " berhasil diklaim!";
            
            // Check if all vouchers are now used in this session
            if (count($_SESSION['used_vouchers']) === count($voucher_tersedia)) {
                 $_SESSION['voucher_lockout_time'] = time(); // Set lockout start time
            }

        } else {
             $_SESSION['error_message'] = "Voucher " . $kode_voucher_diklaim . " sudah pernah Anda klaim.";
             // If user tries to claim an already used voucher and all are used, set lockout
             if (count($_SESSION['used_vouchers']) === count($voucher_tersedia)) {
                  $_SESSION['voucher_lockout_time'] = time();
             }
        }
    } else {
        $_SESSION['error_message'] = "Kode voucher tidak valid.";
    }
    header("Location: voucher.php"); // Redirect back to voucher page
    exit();
}

// Check for success or error messages from claiming
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klaim Voucher</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #F6AB0E;
            padding: 10px;
            display: flex;
            align-items: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header a {
            text-decoration: none;
            color: #000000;
            font-size: 18px;
            margin-right: 15px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
        }
        .voucher-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .voucher-item {
            background-color: #c9f1dd;
            color: #000000;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .voucher-details h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .voucher-details p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .voucher-actions button {
            background-color: #4E94B2;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
         .voucher-actions button.diklaim,
         .voucher-actions button:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            border: 1px solid #999;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .lockout-message {
            text-align: center;
            color: orange;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="cart.php">&larr;</a>
            <h1>Klaim Voucher</h1>
        </div>

        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($lockout_message): ?>
             <div class="lockout-message"><?php echo $lockout_message; ?></div>
        <?php else: ?>
            <div class="voucher-list">
                <?php foreach ($voucher_tersedia as $kode => $voucher): ?>
                    <div class="voucher-item">
                        <div class="voucher-details">
                            <h3><?php echo $kode; ?></h3>
                            <p><?php echo $voucher['deskripsi']; ?></p>
                        </div>
                        <div class="voucher-actions">
                            <?php if ($voucher_diklaim_saat_ini === $kode): ?>
                                <button class="diklaim" disabled>Diklaim (Saat Ini)</button>
                            <?php elseif (in_array($kode, $_SESSION['used_vouchers'])): // Check if used in session ?>
                                <button disabled>Sudah digunakan</button>
                            <?php else: ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="kode_voucher" value="<?php echo $kode; ?>">
                                    <button type="submit" name="klaim_voucher">Klaim</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 