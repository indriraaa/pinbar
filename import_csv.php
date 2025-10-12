<?php
include "koneksi.php";

if (isset($_FILES['csvFile']['tmp_name'])) {
    $file = $_FILES['csvFile']['tmp_name'];
    $handle = fopen($file, "r");

    // Lewati baris header
    fgetcsv($handle);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $nama_barang = mysqli_real_escape_string($db, $data[0]);
        $jumlah = (int)$data[1];

        // Cek apakah nama barang sudah ada
        $cek = mysqli_query($db, "SELECT * FROM barang WHERE nama_barang='$nama_barang'");
        if (mysqli_num_rows($cek) == 0) {
            mysqli_query($db, "INSERT INTO barang (nama_barang, jumlah) VALUES ('$nama_barang', $jumlah)");
        }
    }

    fclose($handle);
    echo "<script>alert('Import selesai!'); window.location='hal.php';</script>";
} else {
    echo "<script>alert('File tidak ditemukan!'); window.location='hal.php';</script>";
}
?>
