<?php
session_start();
include "koneksi.php";

// Ambil token dari URL
$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Token tidak ditemukan!");
}

// Cek token di database
$stmt = mysqli_prepare($db, "SELECT email, expire FROM reset_password WHERE token = ?");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Token tidak valid!");
}

// Cek apakah token sudah expired
$current_time = date("Y-m-d H:i:s");
if ($current_time > $row['expire']) {
    die("Token sudah kadaluwarsa!");
}

// Jika form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        $error = "Password dan konfirmasi tidak boleh kosong!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak cocok!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update password pengguna
        $stmt = mysqli_prepare($db, "UPDATE pengguna SET password = ? WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $row['email']);
        mysqli_stmt_execute($stmt);

        // Hapus token dari reset_password
        $stmt = mysqli_prepare($db, "DELETE FROM reset_password WHERE token = ?");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);

        // Set flash message dan redirect ke login
        $_SESSION['flash_message'] = [
            'text' => 'Password berhasil diubah! Silakan login.',
            'color' => 'green'
        ];
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ubah Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7f7;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
}
.container {
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}
.error-message {
    background-color: #ffe5e5;
    color: #d93025;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
}
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7f7;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
}
.container {
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #333;
}
.form-label {
    font-weight: 500;
}
input.form-control {
    border-radius: 8px;
    padding: 10px;
}
button {
    background-color: #3AB0A2;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}
button:hover {
    background-color: #2a8076;
}
.error-message {
    background-color: #ffe5e5;
    color: #d93025;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
}
</style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Ubah Password</h2>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <table class="table table-borderless" style="max-width: 400px; margin: 0 auto;">
            <!-- Password Baru -->
            <tr>
                <td colspan="2" style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control pe-5" placeholder="Password Baru" required style="width: 100%;">
                    <span style="position: absolute; right: 35px; top: 50%; transform: translateY(-50%); cursor:pointer;" onclick="togglePassword('password', this)">
                        <i class="fa fa-eye"></i>
                    </span>
                </td>
            </tr>

            <!-- Konfirmasi Password -->
            <tr>
                <td colspan="2" style="position: relative;">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control pe-5" placeholder="Konfirmasi Password" required style="width: 100%;">
                    <span style="position: absolute; right: 35px; top: 50%; transform: translateY(-50%); cursor:pointer;" onclick="togglePassword('confirm_password', this)">
                        <i class="fa fa-eye"></i>
                    </span>
                </td>
            </tr>

            <!-- Tombol Submit -->
            <tr>
                <td colspan="2" class="text-center">
                    <button type="submit" class="btn btn-success w-100">Simpan Password</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    const icon = el.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>


<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const iconElem = icon.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        iconElem.classList.remove('fa-eye');
        iconElem.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        iconElem.classList.remove('fa-eye-slash');
        iconElem.classList.add('fa-eye');
    }
}
</script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7f7;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
}
.container {
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}
.error-message {
    background-color: #ffe5e5;
    color: #d93025;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
}
</style>

    </form>
</div>
</body>
</html>
