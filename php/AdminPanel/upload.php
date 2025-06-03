<?php
// Folder tempat upload
$upload_dir = "uploads/";

// Cek apakah folder 'uploads' sudah ada
if (!is_dir($upload_dir)) {
    // Buat folder dengan permission 0755
    if (mkdir($upload_dir, 0755, true)) {
        echo "Folder 'uploads' berhasil dibuat.";
    } else {
        die("Gagal membuat folder 'uploads'. Pastikan permission direktori induk sudah benar.");
    }
}
?>
