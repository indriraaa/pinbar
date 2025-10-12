<?php
include "koneksi.php"; // koneksi ke database

if (!isset($_POST['Register'])) {
    header("Location: register.php");
    exit();
}

// helper ambil POST
function get_post($keys, $default='') {
    foreach ($keys as $k) {
        if (isset($_POST[$k])) return trim($_POST[$k]);
    }
    return $default;
}

// Ambil input
$nama          = get_post(['nama']);
$nip           = get_post(['nip']);
$kontak        = get_post(['kontak']);
// $role          = get_post(['role']); // abaikan input user
$role          = 'pegawai'; // otomatis pegawai
$email         = get_post(['email']);
$password_raw  = get_post(['password']);
$confirm_raw   = get_post(['confirm_password']);

// simpan data lama untuk dikirim balik
$old = [
    'nama'   => $nama,
    'nip'    => $nip,
    'kontak' => $kontak,
    'role'   => $role, // tetap ikut biar gak error
    'email'  => $email
];

// Validasi field wajib
if ($nama === '' || $email === '' || $password_raw === '' || $role === '') {
    header("Location: register.php?msg=required&" . http_build_query($old));
    exit();
}

// cek konfirmasi password
if ($password_raw !== $confirm_raw) {
    header("Location: register.php?msg=password&" . http_build_query($old));
    exit();
}

// validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?msg=invalid_email&" . http_build_query($old));
    exit();
}

// password minimal 6
if (strlen($password_raw) < 6) {
    header("Location: register.php?msg=shortpass&" . http_build_query($old));
    exit();
}

// validasi nip & kontak (hanya angka)
if ($nip !== '' && !ctype_digit($nip)) {
    header("Location: register.php?msg=nip_invalid&" . http_build_query($old));
    exit();
}
if ($kontak !== '' && !ctype_digit($kontak)) {
    header("Location: register.php?msg=kontak_invalid&" . http_build_query($old));
    exit();
}

// cek email sudah ada
$check_sql = "SELECT NO FROM pengguna WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($db, $check_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    header("Location: register.php?msg=exist&" . http_build_query($old));
    exit();
}
mysqli_stmt_close($stmt);

// hash password
$hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);

// insert ke DB
$insert_sql = "INSERT INTO pengguna (nama, nip, kontak, email, PASSWORD, ROLE) VALUES (?, ?, ?, ?, ?, ?)";
$ins = mysqli_prepare($db, $insert_sql);
mysqli_stmt_bind_param($ins, "ssssss", $nama, $nip, $kontak, $email, $hashed_password, $role);
$ok = mysqli_stmt_execute($ins);

if ($ok) {
    mysqli_stmt_close($ins);
    // redirect ke index dengan pesan sukses
    header("Location: index.php?msg=success");
    exit();
} else {
    $err = mysqli_stmt_error($ins);
    mysqli_stmt_close($ins);
    echo "Insert gagal: " . htmlspecialchars($err);
    exit();
}
?>
