<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";

// pastikan file ini benar-benar ada: phpqrcode/qrlib.php
require_once __DIR__ . "/phpqrcode/qrlib.php";

// Buat folder qr jika belum ada (dan cek writable)
$qrDir = __DIR__ . "/qr";
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0777, true);
}
if (!is_writable($qrDir)) {
    die("Folder 'qr' tidak bisa ditulis. Ubah permission-nya (misal chmod 0777 sementara).");
}

// Cek apakah class tersedia
if (!class_exists('QRcode')) {
    die("Class QRcode tidak ditemukan. Pastikan file phpqrcode/qrlib.php betul-betul termuat.");
}

// Ambil semua barang dari DB
$sql = "SELECT kd_barang, nama_barang FROM barang";
$result = mysqli_query($db, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($db));
}

while ($row = mysqli_fetch_assoc($result)) {
    $kd_barang   = $row['kd_barang'];
    $nama_barang = $row['nama_barang'];

    // Nama file PNG
    $filename = $qrDir . "/" . $kd_barang . "test.png";

    // Data QR (isi QR = kode barang)
    $data = $kd_barang;

    // ✅ Pakai class yang benar: QRcode (Q dan R besar)
    // Param: data, filename, level(0=L), size(6), margin(2)
    QRcode::png($data, $filename, 'L', 6, 2);

    echo "<p>QR untuk <b>{$nama_barang}</b> berhasil dibuat: 
          <a href='qr/{$kd_barang}.png' target='_blank'>Lihat QR</a></p>";
}
