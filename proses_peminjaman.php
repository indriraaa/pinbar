<?php
session_start();
include "koneksi.php";

// Ambil role dari session
$role = $_SESSION['role'] ?? '';

// Ambil data peminjam
$nama_peminjam  = $_POST['nama_peminjam'] ?? '';
$nip            = $_POST['nip'] ?? '';
$kontak         = $_POST['kontak'] ?? '';
$univ_jurusan   = $_POST['univ_jurusan'] ?? '';
$tanggal_pinjam = $_POST['tanggal_pinjam'] ?? null;

// Ambil data barang (array)
$kd_barang_arr   = $_POST['kd_barang'] ?? [];
$nama_barang_arr = $_POST['nama_barang'] ?? [];
$jumlah_arr      = $_POST['jumlah'] ?? [];
$keterangan_arr  = $_POST['keterangan'] ?? []; // ✅ tambahkan biar tidak undefined

// Validasi minimal 1 barang
if (count($kd_barang_arr) == 0) {
    echo "<script>alert('Belum ada barang yang dipinjam!'); window.location='form_peminjaman.php';</script>";
    exit;
}

// Loop tiap barang
foreach ($kd_barang_arr as $index => $kd_barang) {
    $kd_barang   = mysqli_real_escape_string($db, $kd_barang);
    $jumlah      = (int)($jumlah_arr[$index] ?? 0);
    $nama_barang = mysqli_real_escape_string($db, $nama_barang_arr[$index] ?? '');
    $keterangan  = mysqli_real_escape_string($db, $keterangan_arr[$index] ?? '');

    // Cek kode barang
    $cekBarang = mysqli_query($db, "SELECT jumlah FROM barang WHERE kd_barang='$kd_barang'");
    if (mysqli_num_rows($cekBarang) == 0) {
        echo "<script>alert('Kode barang $kd_barang tidak ditemukan!'); window.location='form_peminjaman.php';</script>";
        exit;
    }

    $data = mysqli_fetch_assoc($cekBarang);

    // Cek stok
    if ($data['jumlah'] < $jumlah) {
        echo "<script>alert('Stok barang $nama_barang tidak mencukupi!'); window.location='form_peminjaman.php';</script>";
        exit;
    }

    // Simpan ke tabel peminjaman
    $query = "INSERT INTO peminjaman (kd_barang, nama_barang, nama_peminjam, nip, kontak, univ_jurusan, jumlah, tanggal_pinjam, status)
              VALUES ('$kd_barang', '$nama_barang', '$nama_peminjam', '$nip', '$kontak', '$univ_jurusan', $jumlah, '$tanggal_pinjam', 'Dipinjam')";

    if (mysqli_query($db, $query)) {
        // Kurangi stok barang
        mysqli_query($db, "UPDATE barang SET jumlah = jumlah - $jumlah WHERE kd_barang='$kd_barang'");
    } else {
        echo "<script>alert('Gagal menyimpan data barang $nama_barang'); window.location='form_peminjaman.php';</script>";
        exit;
    }
}

// Semua berhasil → arahkan sesuai role
if ($role === 'pegawai') {
    echo "<script>alert('Peminjaman berhasil disimpan!'); window.location='beranda.php';</script>";
} else {
    echo "<script>alert('Peminjaman berhasil disimpan!'); window.location='riwayat.php';</script>";
}
exit;
