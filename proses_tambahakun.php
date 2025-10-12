<?php
include("koneksi.php");

if (isset($_POST['tambah'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash password

    // cek apakah email sudah ada
    $cek = mysqli_query($db, "SELECT * FROM pengguna WHERE email='$email'");
    if(mysqli_num_rows($cek) > 0){
        echo '<div class="alert alert-warning" role="alert">
                Email sudah digunakan, silakan pakai email lain!
              </div>';
        exit;
    }

    // query insert
    $sql = "INSERT INTO pengguna (email, password) VALUES ('$email','$password')";
    $query = mysqli_query($db, $sql);

    if ($query) {
        echo '<div class="alert alert-success" role="alert">
                Data pengguna berhasil ditambahkan!
              </div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Gagal menambahkan data pengguna!
              </div>';
    }
} else {
    die("Akses tidak diizinkan");
}
?>
