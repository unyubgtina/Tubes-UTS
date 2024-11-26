<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";
$koneksi    = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$kode_mk    = "";
$nama_mk    = "";
$sks        = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id         = $_GET['id'];
    $sql1       = "DELETE FROM mata_kuliah WHERE id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil menghapus data";
    } else {
        $error  = "Gagal menghapus data";
    }
}

if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "SELECT * FROM mata_kuliah WHERE id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $kode_mk    = $r1['kode_mata_kuliah'];
    $nama_mk    = $r1['nama_mata_kuliah'];
    $sks        = $r1['sks'];

    if ($kode_mk == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) { // For create and update
    $kode_mk    = $_POST['kode_mk'];
    $nama_mk    = $_POST['nama_mk'];
    $sks        = $_POST['sks'];

    if ($kode_mk && $nama_mk && $sks) {
        if ($op == 'edit') { // Update record
            $sql1 = "UPDATE mata_kuliah SET kode_mata_kuliah = '$kode_mata_kuliah', nama_mata_kuliah = '$nama_mata_kuliah', sks = '$sks' WHERE id = '$id'";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { // Insert new record
            $sql1 = "INSERT INTO mata_kuliah (kode_mata_kuliah, nama_mata_kuliah, sks) VALUES ('$kode_mk', '$nama_mk', '$sks')";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error  = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-primary mb-3">Back to Home</a>
        <a href="dosen.php"  class="btn btn-primary mb-3">Go to Dosen</a>
        <h2 class="mt-4">Data Mata Kuliah</h2>
        
        <!-- Notification Section -->
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php header("refresh:3;url=mata_kuliah.php"); } ?>
        <?php if ($sukses) { ?>
            <div class="alert alert-success"><?php echo $sukses; ?></div>
        <?php header("refresh:3;url=mata_kuliah.php"); } ?>

        <!-- Form Section -->
        <form action="" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                <input type="text" class="form-control" id="kode_mk" name="kode_mk" value="<?php echo $kode_mk; ?>">
            </div>
            <div class="mb-3">
                <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                <input type="text" class="form-control" id="nama_mk" name="nama_mk" value="<?php echo $nama_mk; ?>">
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">SKS</label>
                <input type="number" class="form-control" id="sks" name="sks" value="<?php echo $sks; ?>">
            </div>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
        </form>

        <!-- Table Section -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Mata Kuliah</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql2 = "SELECT * FROM mata_kuliah ORDER BY id DESC";
                $q2   = mysqli_query($koneksi, $sql2);
                $urut = 1;
                while ($r2 = mysqli_fetch_array($q2)) {
                    $id       = $r2['id'];
                    $kode_mk  = $r2['kode_mata_kuliah'];
                    $nama_mk  = $r2['nama_mata_kuliah'];
                    $sks      = $r2['sks'];
                ?>
                    <tr>
                        <th scope="row"><?php echo $urut++; ?></th>
                        <td><?php echo $kode_mk; ?></td>
                        <td><?php echo $nama_mk; ?></td>
                        <td><?php echo $sks; ?></td>
                        <td>
                            <a href="mata_kuliah.php?op=edit&id=<?php echo $id; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="mata_kuliah.php?op=delete&id=<?php echo $id; ?>" onclick="return confirm('Yakin mau delete data?')" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
