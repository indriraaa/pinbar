<?php
session_start();
include "koneksi.php";
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $_SESSION['flash_message'] = ['text' => 'Email tidak boleh kosong!', 'color' => 'red'];
        header("Location: reset_password.php");
        exit();
    }

    // Path config_smtp.php di subfolder reset_password
    $config_path = __DIR__ . "/reset_password/config_smtp.php";

    if (!file_exists($config_path)) {
        die("File config_smtp.php tidak ditemukan di: " . $config_path);
    }

    $config = include $config_path;

    // Validasi konfigurasi SMTP
    $required_keys = ['host','port','encryption','username','password','from_email','from_name'];
    foreach ($required_keys as $key) {
        if (empty($config[$key])) {
            die("Konfigurasi SMTP tidak lengkap! Missing: $key");
        }
    }

    // Cek email terdaftar
    $stmt = mysqli_prepare($db, "SELECT * FROM pengguna WHERE email = ?");
    if (!$stmt) die("Prepare SELECT gagal: " . mysqli_error($db));
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['flash_message'] = ['text' => 'Email tidak ditemukan dalam sistem!', 'color' => 'red'];
        header("Location: reset_password.php");
        exit();
    }

    // Generate token dan expire time
    $token = bin2hex(random_bytes(50));
    $expire_time = date("Y-m-d H:i:s", strtotime("+30 minutes"));

    // Insert ke reset_password
    $stmt = mysqli_prepare($db, "INSERT INTO reset_password (email, token, expire) VALUES (?, ?, ?)");
    if (!$stmt) die("Prepare INSERT gagal: " . mysqli_error($db));
    mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expire_time);
    if (!mysqli_stmt_execute($stmt)) die("Execute INSERT gagal: " . mysqli_stmt_error($stmt));

    // Link reset password
    $reset_link = "http://localhost/UAS/ubah_password.php?token=$token";

    // Kirim email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPDebug = 2; // Debug SMTP
        $mail->Debugoutput = 'html';
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port       = $config['port'];

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Password Akun Anda';
        $mail->Body = "
            <h3>Permintaan Reset Password</h3>
            <p>Hai, kami menerima permintaan reset password untuk akun Anda.</p>
            <p>Silakan klik tombol di bawah untuk mengubah password:</p>
            <p><a href='$reset_link' style='background:#4CAF50;color:#fff;padding:10px 15px;text-decoration:none;border-radius:6px;'>Reset Password</a></p>
            <p>Link ini hanya berlaku selama <b>30 menit</b>.</p>
        ";

        $mail->send();

        $_SESSION['flash_message'] = ['text' => 'Link reset password telah dikirim ke email Anda!', 'color' => 'green'];
    } catch (Exception $e) {
        $_SESSION['flash_message'] = ['text' => 'Gagal mengirim email. Error: ' . $mail->ErrorInfo, 'color' => 'red'];
    }

    header("Location: reset_password.php");
    exit();
}
