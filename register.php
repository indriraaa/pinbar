<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#7FB7BE,#EAF4F4);height:100vh;display:flex;justify-content:center;align-items:center}
        .container-form{background:#fff;padding:35px 30px;border-radius:16px;box-shadow:0 8px 25px rgba(0,0,0,.15);width:360px;text-align:center}
        .form-header h2{font-size:26px;font-weight:600;color:#2F4858;margin-bottom:20px}
        input[type="text"],input[type="email"],input[type="password"],select{display:block;width:100%;box-sizing:border-box;padding:12px;margin-bottom:15px;border-radius:10px;border:1px solid #ccc;font-size:14px;transition:.3s;background:#fff}
        input:focus,select:focus{border-color:#7FB7BE;box-shadow:0 0 8px rgba(127,183,190,.4);outline:none}
        input[type="submit"]{width:100%;padding:12px;border-radius:10px;border:none;background:#7FB7BE;color:#fff;font-size:15px;font-weight:500;cursor:pointer;transition:.3s}
        input[type="submit"]:hover{background:#6AA9AF}
        .index-link{margin-top:12px;display:inline-block;font-size:14px;color:#7FB7BE;text-decoration:none;transition:.3s}
        .index-link:hover{text-decoration:underline}
        .message{font-size:13px;margin-bottom:10px;padding:8px;border-radius:8px}
        .error{color:#b71c1c;background:#ffcdd2}
        .success{color:#1b5e20;background:#c8e6c9}
    </style>
</head>
<body>
    <div class="container-form">
        <div class="form-header"><h2>Register</h2></div>

        <?php
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] == "password") {
                echo "<p class='message error'>Konfirmasi password tidak sama!</p>";
            } else if ($_GET['msg'] == "exist") {
                echo "<p class='message error'>Email sudah terdaftar!</p>";
            } else if ($_GET['msg'] == "invalid_email") {
                echo "<p class='message error'>Format email tidak valid!</p>";
            } else if ($_GET['msg'] == "shortpass") {
                echo "<p class='message error'>Password minimal 6 karakter!</p>";
            } else if ($_GET['msg'] == "success") {
                echo "<p class='message success'>Registrasi berhasil! Silakan login di halaman utama.</p>";
            } else if ($_GET['msg'] == "required") {
                echo "<p class='message error'>Lengkapi semua field yang wajib!</p>";
            } else if ($_GET['msg'] == "nip_invalid") {
                echo "<p class='message error'>NIP hanya boleh berupa angka!</p>";
            } else if ($_GET['msg'] == "kontak_invalid") {
                echo "<p class='message error'>Kontak hanya boleh berupa angka!</p>";
            }
        }
        ?>

        <form action="proses_register.php" method="post" autocomplete="off">
            <input type="text" name="nama" placeholder="Masukkan Nama Lengkap"
                   value="<?php echo htmlspecialchars($_GET['nama'] ?? ''); ?>" required>

            <input type="text" name="nip" placeholder="Masukkan NIP/NIM"
                   value="<?php echo htmlspecialchars($_GET['nip'] ?? ''); ?>" required>

            <input type="text" name="kontak" placeholder="Masukkan Kontak"
                   value="<?php echo htmlspecialchars($_GET['kontak'] ?? ''); ?>" required>

            <!-- Role otomatis jadi pegawai -->
            <input type="hidden" name="role" value="pegawai">

            <!-- kode lama tetap ada tapi dikomentari -->
            <!--
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin"   <?php if(($_GET['role'] ?? '')==='admin') echo 'selected'; ?>>Admin</option>
                <option value="pegawai" <?php if(($_GET['role'] ?? '')==='pegawai') echo 'selected'; ?>>Pegawai</option>
            </select>
            -->

            <input type="email" name="email" placeholder="Masukkan Email"
                   value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required>

            <input type="password" name="password" placeholder="Masukkan Password" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            <input type="submit" value="Daftar" name="Register">
        </form>

        <a href="index.php" class="index-link">Sudah punya akun? Masuk di sini</a>
    </div>
</body>
</html>
