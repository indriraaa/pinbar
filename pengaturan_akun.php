<?php

session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}

include("koneksi.php");

// Ambil nama pengguna dari sesi
$nama_pengguna = $_SESSION['login_user'];

// Tampilkan data pengguna saat ini
$sql = "SELECT * FROM pengguna WHERE nama = '$nama_pengguna'";
$query = mysqli_query($db, $sql);

// Periksa apakah data pengguna ditemukan
if ($query) {
    $data_pengguna = mysqli_fetch_assoc($query);

    // Periksa apakah $data_pengguna bukan null
    if ($data_pengguna) {
        // ... (lanjutkan dengan menampilkan data dan form pengaturan akun)
    } else {
        echo "Data pengguna tidak ditemukan.";
    }
} else {
    echo "Query tidak berhasil dieksekusi.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <!-- Memuat CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-4">
        <h2>Pengaturan Akun</h2>
        <form action="proses_ganti_akun.php" method="post">
            <div class="form-group">
                <label for="nama">Nama Pengguna:</label>
                <input type="text" name="nama" class="form-control" id="nama" value="<?php echo $data_pengguna['nama']; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php echo $data_pengguna['email']; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" id="password">
            </div><br>
            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
            <a href="beranda.php" class="btn btn-primary">Batal</a>
        </form>
    </div>

    <!-- Memuat JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
