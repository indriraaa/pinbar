<?php
include('koneksi.php');

// Fetch stock data from the database
$sql = "SELECT * FROM stock";
$query = mysqli_query($db, $sql);

// Fetch stock masuk data from the database
$sql_masuk = "SELECT * FROM stok_masuk";
$query_masuk = mysqli_query($db, $sql_masuk);

// Fetch stock keluar data from the database
$sql_keluar = "SELECT * FROM stok_keluar";
$query_keluar = mysqli_query($db, $sql_keluar);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    <!-- Memuat CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-4">
        <h2>Laporan Stok Barang</h2>

        <!-- Stock data -->
        <h3>Stok Saat Ini</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                while ($data = mysqli_fetch_array($query)) { ?>
                    <tr>
                        <td><?php echo $nomor++ ?></td>
                        <td><?php echo $data['namabarang'] ?></td>
                        <td><?php echo $data['tanggal'] ?></td>
                        <td><?php echo $data['deskripsi'] ?></td>
                        <td><?php echo $data['stock'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Stock masuk data -->
        <h3>Stok Masuk</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Masuk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                while ($data_masuk = mysqli_fetch_array($query_masuk)) { ?>
                    <tr>
                        <td><?php echo $nomor++ ?></td>
                        <td><?php echo $data_masuk['namabarang'] ?></td>
                        <td><?php echo $data_masuk['tanggal_masuk'] ?></td>
                        <td><?php echo $data_masuk['jumlah_masuk'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Stock keluar data -->
        <h3>Stok Keluar</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Keluar</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                while ($data_keluar = mysqli_fetch_array($query_keluar)) { ?>
                    <tr>
                        <td><?php echo $nomor++ ?></td>
                        <td><?php echo $data_keluar['namabarang'] ?></td>
                        <?php echo isset($data['tanggal_keluar']) ? $data['tanggal_keluar'] : ''; ?>
                        <?php echo isset($data['jumlah_keluar']) ? $data['jumlah_keluar'] : ''; ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="beranda.php" class="btn btn-primary">Kembali</a>
    </div>

    <!-- Memuat JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>
