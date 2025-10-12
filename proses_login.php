<?php
session_start();
include "koneksi.php";

if (isset($_POST['Login'])) {
    // Ambil data dari form dengan aman
    $role = mysqli_real_escape_string($db, $_POST['role']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Simpan input lama supaya tidak hilang saat error
    $_SESSION['old'] = [
        'email' => $email,
        'role'  => $role
    ];

    // Query cek pengguna
    $query = "SELECT * FROM pengguna WHERE email='$email' AND ROLE='$role' LIMIT 1";
    $result = mysqli_query($db, $query);

    // Pastikan query tidak error
    if (!$result) {
        $_SESSION['flash_message'] = [
            'text' => 'Terjadi kesalahan sistem: ' . mysqli_error($db),
            'color' => 'red'
        ];
        header("Location: index.php");
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        $pengguna = mysqli_fetch_assoc($result);

        // Cek password:
        $password_valid = false;
        if (password_verify($password, $pengguna['PASSWORD'])) {
            $password_valid = true;
        } elseif ($password === $pengguna['PASSWORD']) {
            // Jika masih plain text, izinkan login
            $password_valid = true;

            // OPSIONAL: otomatis update password ke hash agar lebih aman
            // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // mysqli_query($db, "UPDATE pengguna SET PASSWORD='$hashed_password' WHERE NO='{$pengguna['NO']}'");
        }

        if ($password_valid) {
            // Buat session
            $_SESSION['pengguna'] = $pengguna['NO'];
            $_SESSION['nama']     = $pengguna['nama'];
            $_SESSION['email']    = $pengguna['email'];
            $_SESSION['role']     = $pengguna['ROLE'];

            // Clear data lama
            unset($_SESSION['old']);

            // Redirect ke beranda
            header("Location: beranda.php");
            exit();
        } else {
            // Password salah
            $_SESSION['flash_message'] = [
                'text' => 'Password salah!',
                'color' => 'red'
            ];
            header("Location: index.php");
            exit();
        }
    } else {
        // Email/role tidak ditemukan
        $_SESSION['flash_message'] = [
            'text' => 'Email atau role tidak ditemukan!',
            'color' => 'red'
        ];
        header("Location: index.php");
        exit();
    }
} else {
    // Jika akses langsung tanpa login
    header("Location: index.php");
    exit();
}
