<?php
include "koneksi.php"; // koneksi ke database

if (isset($_POST['Register'])) {
    $nama = mysqli_real_escape_string($db, $_POST['nama']);
    $nip = mysqli_real_escape_string($db, $_POST['nip']);
    $role = mysqli_real_escape_string($db, $_POST['role']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['confirm_password']);

    // 1. Cek konfirmasi password
    if ($password !== $confirm_password) {
        header("Location: register.php?msg=password");
        exit();
    }

    // 2. Cek apakah email sudah ada
    $check_email = mysqli_query($db, "SELECT * FROM pengguna WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        header("Location: register.php?msg=exist");
        exit();
    }

    // 3. Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insert ke database (pakai hash, bukan password asli)
    $query = "INSERT INTO pengguna (nama, NIP, role, email, password) 
              VALUES ('$nama', '$nip', '$role', '$email', '$hashed_password')";

    if (mysqli_query($db, $query)) {
        header("Location: register.php?msg=success");
        exit();
    } else {
        echo "Error: " . mysqli_error($db);
    }

} else {
    header("Location: register.php");
    exit();
}
?>