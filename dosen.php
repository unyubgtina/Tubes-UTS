<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";
$koneksi    = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) { // Check connection
    die("Failed to connect to the database");
}

$nama_dosen = "";
$nip        = "";
$fakultas   = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM dosen WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Successfully deleted data";
    } else {
        $error = "Failed to delete data";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM dosen WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nama_dosen = $r1['nama_dosen'];
    $nip = $r1['nip'];
    $fakultas = $r1['fakultas'];

    if ($nama_dosen == '') {
        $error = "Data not found";
    }
}

if (isset($_POST['simpan'])) { // To create or update
    $nama_dosen = $_POST['nama_dosen'];
    $nip = $_POST['nip'];
    $fakultas = $_POST['fakultas'];

    if ($nama_dosen && $nip && $fakultas) {
        if ($op == 'edit') { // Update
            $sql1 = "UPDATE dosen SET nama_dosen = '$nama_dosen', nip = '$nip', fakultas = '$fakultas' WHERE id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data successfully updated";
            } else {
                $error = "Failed to update data";
            }
        } else { // Insert
            $sql1 = "INSERT INTO dosen(nama_dosen, nip, fakultas) VALUES ('$nama_dosen', '$nip', '$fakultas')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Successfully added new data";
            } else {
                $error = "Failed to add data";
            }
        }
    } else {
        $error = "Please enter all data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mx-auto { width: 800px; }
        .card { margin-top: 10px; }
    </style>
</head>
<body>
<div class="mx-auto">
    <a href="index.php" class="btn btn-primary mb-3">Back to Home</a>
    <a href="mata_kuliah.php"  class="btn btn-primary mb-3">Go to Mata Kuliah</a>
    <div class="card">
        <div class="card-header">Create / Edit Dosen Data</div>
        <div class="card-body">
            <?php if ($error) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            <?php if ($sukses) { ?>
                <div class="alert alert-success"><?php echo $sukses; ?></div>
            <?php } ?>
            <form action="" method="POST">
                <div class="mb-3 row">
                    <label for="nama_dosen" class="col-sm-2 col-form-label">Nama Dosen</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama_dosen" value="<?php echo $nama_dosen; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nip" class="col-sm-2 col-form-label">NIP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nip" value="<?php echo $nip; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fakultas" value="<?php echo $fakultas; ?>">
                    </div>
                </div>
                <input type="submit" name="simpan" value="Save Data" class="btn btn-primary">
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-white bg-secondary">Dosen Data</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Dosen</th>
                        <th>NIP</th>
                        <th>Fakultas</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql2 = "SELECT * FROM dosen ORDER BY id DESC";
                    $q2 = mysqli_query($koneksi, $sql2);
                    $urut = 1;
                    while ($r2 = mysqli_fetch_array($q2)) {
                        $id = $r2['id'];
                        $nama_dosen = $r2['nama_dosen'];
                        $nip = $r2['nip'];
                        $fakultas = $r2['fakultas'];
                    ?>
                        <tr>
                            <th><?php echo $urut++; ?></th>
                            <td><?php echo $nama_dosen; ?></td>
                            <td><?php echo $nip; ?></td>
                            <td><?php echo $fakultas; ?></td>
                            <td>
                                <a href="dosen.php?op=edit&id=<?php echo $id; ?>"><button class="btn btn-warning">Edit</button></a>
                                <a href="dosen.php?op=delete&id=<?php echo $id; ?>" onclick="return confirm('Are you sure?')"><button class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
