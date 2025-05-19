<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $koneksi->prepare("INSERT INTO newsletter (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "success";
    } else {
        echo "invalid";
    }
}
?>