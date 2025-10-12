<?php
session_start();

$message = "";
$messageColor = "red";

// Ambil pesan dari session jika ada
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message']['text'];
    $messageColor = $_SESSION['flash_message']['color'];
    unset($_SESSION['flash_message']);
}

// Ambil pesan sukses kirim link dari URL
$success = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7f7;
    margin: 0;
    padding: 0;
}

.navbar {
    background-color: #3AB0A2;
    padding: 25px 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
}
.navbar a {
    color: white;
    text-decoration: none;
}
.navbar a:hover { color: grey; }

.main {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #9e9f9f, #eaf4f4);
}

.container-form {
    background: #fff;
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    width: 340px;
    text-align: center;
}

.form-header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #2F4858;
    margin-bottom: 15px;
}

input[type="email"] {
    width: 100%;
    height: 45px;
    padding: 10px 12px;
    margin-bottom: 15px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: .3s;
    box-sizing: border-box;
}

input[type="email"]:focus {
    border-color: #7FB7BE;
    box-shadow: 0 0 8px rgba(127,183,190,0.4);
    outline: none;
}

input[type="submit"] {
    width: 100%;
    height: 45px;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: #7FB7BE;
    color: white;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}

input[type="submit"]:hover {
    background: #6AA9AF;
}

.message {
    font-size: 13px;
    margin-bottom: 10px;
}

.back-login {
    display: block;
    margin-top: 18px;
    font-size: 13px;
    color: #3AB0A2;
    text-decoration: none;
}
.back-login:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <span class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-location-dot"></i>
        Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </span>
    <a href="kontak_us.php"><i class="fa-solid fa-phone"></i> Hubungi Kami</a>
</div>

<!-- Main Content -->
<div class="main">
    <div class="container-form">
        <div class="form-header">
            <h2>Reset Password</h2>
            <p style="font-size:13px;color:#777;">Masukkan email Anda untuk mengatur ulang password.</p>
        </div>

        <?php if (!empty($message)) { ?>
            <p class="message" style="color: <?= $messageColor ?>;"><?= $message ?></p>
        <?php } ?>

        <form action="proses_reset_password.php" method="post">
            <input type="email" name="email" placeholder="Masukkan Email Anda" required>
            <input type="submit" value="Kirim Link Reset">
        </form>

        <a href="index.php" class="back-login">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
        </a>
    </div>
</div>

<?php if ($success === 'sent'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Link Reset Dikirim!',
    text: 'Silakan periksa email Anda untuk mengatur ulang password.',
    confirmButtonColor: '#3AB0A2',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

</body>
</html>
