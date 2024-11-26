<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$error = "";
$sukses = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($username && $password && $confirm_password) {
        if ($password == $confirm_password) {
            // Check if username already exists
            $sql = "SELECT * FROM login WHERE username = '$username'";
            $result = mysqli_query($koneksi, $sql);
            if (mysqli_num_rows($result) == 0) {
                // Insert new user into the database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password for security
                $sql = "INSERT INTO login (username, password) VALUES ('$username', '$hashed_password')";
                if (mysqli_query($koneksi, $sql)) {
                    $sukses = "Akun berhasil dibuat! Silakan login.";
                } else {
                    $error = "Gagal membuat akun.";
                }
            } else {
                $error = "Username sudah terdaftar.";
            }
        } else {
            $error = "Password dan konfirmasi password tidak cocok.";
        }
    } else {
        $error = "Harap isi semua field.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Daftar Akun</h2>
        <?php
        if ($error) {
        ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php
        }
        ?>
        <?php
        if ($sukses) {
        ?>
            <div class="alert alert-success">
                <?php echo $sukses; ?>
            </div>
        <?php
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="register" class="btn btn-primary">Daftar</button>
        </form>
        <p class="mt-3">Sudah punya akun? <a href="login.php">Login disini</a></p>
    </div>
</body>

</html>
