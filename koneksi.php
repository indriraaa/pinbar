<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "stokbarang";

// Buat koneksi
$db = mysqli_connect($host, $user, $pass, $dbname);

// Cek koneksi
if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

