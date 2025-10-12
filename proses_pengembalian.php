<?php
include "koneksi.php";

$id_peminjaman   = $_POST['id_peminjaman'];
$tanggal_kembali = $_POST['tanggal_kembali'];

// ambil data peminjaman
$cek = mysqli_query($db, "SELECT kd_barang, jumlah FROM peminjaman WHERE kd_barang='$id_peminjaman'");
$data = mysqli_fetch_assoc($cek);
$kd_barang = $data['kd_barang'];
$jumlah    = $data['jumlah'];

// update status peminjaman
$query = "UPDATE peminjaman SET tanggal_kembali='$tanggal_kembali', status='Dikembalikan' WHERE kd_barang='$id_peminjaman'";

if (mysqli_query($db, $query)) {
    // tambah stok barang kembali
    mysqli_query($db, "UPDATE barang SET jumlah = jumlah + $jumlah WHERE kd_barang='$kd_barang'");
    echo "<script>alert('Pengembalian berhasil diproses'); window.location='riwayat.php';</script>";
} else {
    echo "<script>alert('Gagal memproses pengembalian'); window.location='pengembalian.php';</script>";
}
?>
