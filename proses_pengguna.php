<?php
// proses_pengguna.php
session_start();

// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "stokbarang";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ============================
// HAPUS pengguna
// ============================
if (isset($_GET['hapus_no'])) {
    $no = mysqli_real_escape_string($conn, $_GET['hapus_no']);
    $sql = "DELETE FROM pengguna WHERE NO='$no'";

    if ($conn->query($sql)) {
        $_SESSION['status'] = "hapus_sukses";
    } else {
        $_SESSION['status'] = "gagal";
    }
    header("Location: pengguna.php");
    exit;
}

// ============================
// EDIT pengguna
// ============================
if (isset($_POST['update'])) {
    $no    = mysqli_real_escape_string($conn, $_POST['no']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Role & NIP tidak bisa diedit (readonly di form)
    $sql = "UPDATE pengguna SET nama='$nama', email='$email' WHERE NO='$no'";

    if ($conn->query($sql)) {
        $_SESSION['status'] = "edit_sukses";
    } else {
        $_SESSION['status'] = "gagal";
    }
    header("Location: pengguna.php");
    exit;
}

// ============================
// TAMBAH pengguna
// ============================
if (isset($_POST['tambah'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip      = mysqli_real_escape_string($conn, $_POST['nip']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek NIP unik
    $cek = $conn->query("SELECT * FROM pengguna WHERE nip='$nip'");
    if ($cek && $cek->num_rows > 0) {
        $_SESSION['status'] = "NIP_sudah_ada";
        header("Location: pengguna.php");
        exit;
    }

    $sql = "INSERT INTO pengguna (nama, nip, email, PASSWORD, ROLE) 
            VALUES ('$nama','$nip','$email','$password_hash','$role')";

    if ($conn->query($sql)) {
        $_SESSION['status'] = "sukses";
    } else {
        $_SESSION['status'] = "gagal";
    }
    header("Location: pengguna.php");
    exit;
}

$conn->close();
?>
