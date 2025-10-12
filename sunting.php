<?php
include('koneksi.php');
include('cek_login.php');

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

//ambil id dari url
$id = $_GET['id'];

$sql = "SELECT * FROM stock WHERE idbarang = $id";
$query = mysqli_query($db, $sql);
$data = mysqli_fetch_assoc($query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunting</title>
    <!-- Memuat CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h2>Edit Produk</h2>
        <form action="proses_sunting.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="namaBarang">Nama Barang:</label>
                <input type="text" value="<?php echo $data['namabarang'] ?>" name="nama" class="form-control"
                    id="namaBarang" placeholder="Masukkan Nama Barang">
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" value="<?php echo $data['tanggal'] ?>" name="tanggal" class="form-control"
                    id="tanggal">
            </div>
            <div class="form-group">
                <label for="deskripsi">Foto Produk:</label>
                <input type="file" name="foto" id="foto">
            </div>
            <div class="form-group">
                <label for="stokBarang">Stok Barang:</label>
                <input type="number" name="stok" value="<?php echo $data['stock'] ?>" class="form-control"
                    id="stokBarang" placeholder="Masukkan Jumlah Stok Barang">
            </div>
            <div class="form-group">
                <label for="hargaBarang">Harga Barang:</label>
                <input type="text" name="harga" value="<?php echo $data['harga'] ?>" class="form-control"
                    id="hargaBarang" placeholder="Masukkan Harga Barang">
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <input type="submit" name="sunting" value="Sunting" class="btn btn-primary">
        </form>
        <button class="btn btn-primary text-decoration-none"><a href="stokbarang.php" class="text-decoration-none"
                style="color: white;">Kembali</a></button>
    </div>
    </div>

    <!-- Memuat JavaScript Bootstrap (jika diperlukan) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>