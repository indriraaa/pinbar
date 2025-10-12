<?php
include("koneksi.php");

// cek apakah tombol tambah sudah diklik atau belum
if (isset($_POST['tambah'])) {

    // ambil data dari masing-masing field
    $idbarang = $_POST['idbarang']; // Assuming you have a form field for selecting the product
    $tanggal = $_POST['tanggal'];
    $jmlkeluar = $_POST['jmlkeluar'];
    $penerima = $_POST['penerima'];

    // buat query insert
    $sql = "INSERT INTO `keluar`(`idbarang`, `namabarang`, `tanggal`, `jmlkeluar`, `penerima`) 
            VALUES ('$idbarang', (SELECT `namabarang` FROM `stock` WHERE `idbarang`='$idbarang'), '$tanggal', '$jmlkeluar', '$penerima')";
    $query = mysqli_query($db, $sql);

    $sqlkurang = "UPDATE `stock` SET `stock`=`stock` - $jmlkeluar WHERE idbarang = $idbarang";
    $querykurang = mysqli_query($db, $sqlkurang);

    // cek apakah berhasil
    if ($query) {
        // Set pesan notifikasi
        $_SESSION['notif'] = "Barang berhasil ditambahkan.";

        // alihkan ke halaman stok_keluar.php dengan pesan data sukses disimpan
        header('Location: stok_keluar.php?status=sukses_tambah');
    } else {
        // Set pesan notifikasi
        $_SESSION['notif'] = "Gagal menambahkan barang.";

        // alihkan ke halaman stok_keluar.php dengan pesan data gagal disimpan
        header('Location: stok_keluar.php?status=gagal_tambah');
    }
} else {
    die("Akses tidak diizinkan");
}
?>
