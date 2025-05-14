<?php
include '../php/koneksi.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../php/login.php");
    exit();
}

$user = $_SESSION['user'];

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
    $new_filename = "profile_" . $user['id'] . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is valid
    $valid_file = true;
    
    // Check file size - limit to 5MB
    if ($_FILES["profile_picture"]["size"] > 5000000) {
        echo "<script>alert('File terlalu besar. Maksimal 5MB.');</script>";
        $valid_file = false;
    }
    
    // Allow certain file formats
    if ($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg" && $file_extension != "gif") {
        echo "<script>alert('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.');</script>";
        $valid_file = false;
    }
    
    if ($valid_file) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update database with new profile picture path
            $update_query = mysqli_query($koneksi, "UPDATE users SET profile_picture='$target_file' WHERE id=".$user['id']);
            
            if ($update_query) {
                // Update session data
                $_SESSION['user']['profile_picture'] = $target_file;
                echo "<script>alert('Foto profil berhasil diperbarui!');window.location='profil.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat memperbarui database.');</script>";
            }
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengunggah file.');</script>";
        }
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: ../php/login.php');
        exit;
    }    
}

// Get latest user data
$user_query = mysqli_query($koneksi, "SELECT * FROM users WHERE id=".$user['id']);
$current_user = mysqli_fetch_assoc($user_query);

// Update session with latest data
$_SESSION['user'] = $current_user;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Hexagon Mart</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background-color: #8ed8c3;
            color: #333;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        
        .header a {
            color: #333;
            text-decoration: none;
            margin-right: 10px;
        }
        
        .profile-picture-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }
        
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #8ed8c3;
            position: relative;
        }
        
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin-top: 10px;
        }
        
        .btn {
            border: 2px solid #8ed8c3;
            color: #333;
            background-color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
        }
        
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .info-box {
            background-color: #8ed8c3;
            margin: 10px 20px;
            padding: 15px;
            border-radius: 5px;
        }
        
        .button-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        
        .logout-btn {
            background-color: #ff6b6b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #ff5252;
        }
        
        #uploadForm {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .submit-btn {
            background-color: #8ed8c3;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="header">
            <a href="landing.html">←</a>
            <h2>Profil</h2>
        </div>
        
        <div class="profile-picture-container">
            <img src="<?php echo !empty($current_user['profile_picture']) ? $current_user['profile_picture'] : 'default_avatar.png'; ?>" alt="Profile Picture" class="profile-picture" id="profileImage">
            
            <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
                <div class="upload-btn-wrapper">
                    <button class="btn" type="button">Ubah Foto</button>
                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*">
                </div>
                <button type="submit" class="submit-btn" id="submitBtn">Simpan</button>
            </form>
        </div>
        
        <div class="info-box">
            <p>Username : <?php echo $current_user['username']; ?></p>
        </div>
        
        <div class="info-box">
            <p>No Telepon : <?php echo $current_user['telepon'] ?? '-'; ?></p>
        </div>
        
        <div class="info-box">
            <p>Email : <?php echo $current_user['email']; ?></p>
        </div>
        
        <div class="button-container">
            <a href="login.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <script>
        // Show preview of selected image
        document.getElementById('profilePictureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profileImage').src = event.target.result;
                    document.getElementById('submitBtn').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>