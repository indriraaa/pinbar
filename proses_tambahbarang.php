<?php
include("koneksi.php");

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO barang (nama_barang, jumlah, satuan, tanggal_masuk) 
            VALUES ('$nama', '$stok', '$deskripsi', '$tanggal')";
    $query = mysqli_query($db, $sql);

    if ($query) {
        header('Location: beranda.php?status=sukses_tambah');
    } else {
        die("Query Error: " . mysqli_error($db));
    }
} else {
    die("Akses tidak diizinkan");
}
