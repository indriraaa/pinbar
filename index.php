<?php
session_start();

// Jika sudah login, langsung redirect
if (isset($_SESSION['pengguna'])) {
    header("Location: hal.php");
    exit();
}

$message = "";
$messageColor = "red";
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message']['text'];
    $messageColor = $_SESSION['flash_message']['color'];
    unset($_SESSION['flash_message']);
}

// Ambil pesan dari URL (untuk sukses register)
$msg = $_GET['msg'] ?? '';

// Simpan input lama
$old_email = $_SESSION['old']['email'] ?? '';
$old_role  = $_SESSION['old']['role'] ?? '';
unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
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

/* Navbar */
.navbar {
    background-color: #3AB0A2;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
}
.navbar .d-flex i { font-size: 18px; color: #ffffff; }

/* Main Layout */
.main { 
    flex: 1; 
    display: flex; 
    min-height: calc(100vh - 70px);
}

/* Left side (Desktop) */
.left-side {
    flex: 1;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    padding: 30px;
}
.left-side img.logo { 
    width: 250px; 
    margin-bottom: 30px; 
}
.left-side img.login-image {
    width: 600px; 
    margin-top: 20px; 
}

/* Right side */
.right-side {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #9e9f9f, #eaf4f4);
}

/* Form */
.container-form {
    background: #fff;
    padding: 30px 25px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    width: 340px;
    max-width: 90%;
    text-align: center;
}

.container-form .logo-mobile {
    display: none; /* default hidden */
}

.form-header h2 { 
    font-size: 26px; 
    font-weight: 600; 
    color: #2F4858; 
    margin-bottom: 20px; 
}

input[type="email"], 
input[type="password"], 
select {
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

input[type="email"]:focus, 
input[type="password"]:focus, 
select:focus {
    border-color: #7FB7BE;
    box-shadow: 0 0 8px rgba(127,183,190,0.4);
    outline: none;
}

input[type="submit"], 
.btn-register {
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
    margin-top: 10px; 
    display: inline-block;
    text-decoration: none; 
    box-sizing: border-box;
}

input[type="submit"]:hover, 
.btn-register:hover { 
    background: #6AA9AF; 
}

.message { 
    font-size: 13px; 
    margin-bottom: 10px; 
}

.forgot-password { 
    font-size: 13px; 
    color: #3AB0A2; 
    display: block; 
    margin-top: 18px; 
    text-decoration: none; 
}

.forgot-password:hover { 
    text-decoration: underline; 
}

/* RESPONSIVE MOBILE */
@media (max-width: 991px) {
    .main {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }
    .left-side {
        display: none; /* hide desktop left side */
    }
    .right-side {
        width: 100%;
        padding: 20px 0;
        min-height: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .container-form {
        width: 90%;
        padding: 25px 20px;
    }
    /* Logo mobile lebih besar */
    .container-form .logo-mobile {
        display: block;
        width: 270px; /* diperbesar dari 180px menjadi 220px */
        max-width: 80%;
        margin: 0 auto 25px auto;
    }
    input[type="email"], 
    input[type="password"], 
    select,
    input[type="submit"], 
    .btn-register {
        height: 50px;
        font-size: 16px;
    }
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar d-flex justify-content-between align-items-center px-3">
    <span class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-location-dot"></i>
        Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </span>
    <a href="kontak_us.php" 
       class="d-flex align-items-center gap-2" 
       style="color: white; text-decoration: none;" 
       onmouseover="this.style.color='grey'" 
       onmouseout="this.style.color='white'">
       <i class="fa-solid fa-phone"></i> Hubungi Kami
    </a>
</div>

<!-- Main Content -->
<div class="main">
    <!-- Desktop left side -->
    <div class="left-side">
        <img src="img/Logo.png" alt="Logo Poltekkes" class="logo">
        <img src="img/login.jpg" alt="Logo Login" class="login-image">
    </div>

    <!-- Right side (form) -->
    <div class="right-side">
        <div class="container-form">
            <!-- Logo mobile -->
            <img src="img/Logo.png" alt="Logo Poltekkes" class="logo-mobile">

            <div class="form-header">
                <h2>Login</h2>
            </div>

            <?php if (!empty($message)) { ?>
                <p class="message" style="color: <?= $messageColor ?>;">
                    <?= $message ?>
                </p>
            <?php } ?>

            <form action="proses_login.php" method="post" class="form-login">
                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" <?= ($old_role=='admin')?'selected':''; ?>>Admin</option>
                    <option value="pegawai" <?= ($old_role=='pegawai')?'selected':''; ?>>Pegawai</option>
                </select>

                <input type="email" name="email" placeholder="Masukan Email" 
                       value="<?= htmlspecialchars($old_email) ?>" required>

                <input type="password" name="password" placeholder="Masukan Password" required>

                <input type="submit" value="Login" name="Login">
            </form>

            <!-- Tombol Register -->
            <a href="register.php" class="btn-register">Register</a>

            <!-- Lupa Password -->
            <a href="reset_password.php" class="forgot-password">Lupa Password?</a>
        </div>
    </div>
</div>

<!-- SweetAlert jika registrasi berhasil -->
<?php if ($msg === 'success'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Registrasi Berhasil!',
    text: 'Silakan login dengan akun Anda',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

</body>
</html>
