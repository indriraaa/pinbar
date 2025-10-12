<?php
include("koneksi.php");

if (!isset($_GET['kd_barang'])) {
    header("Location: hal.php?status=error_no_id");
    exit;
}

$kd_barang = intval($_GET['kd_barang']);

$stmt = mysqli_prepare($db, "DELETE FROM barang WHERE kd_barang = ?");
mysqli_stmt_bind_param($stmt, "i", $kd_barang);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    header("Location: hal.php?status=sukses_delete");
} else {
    header("Location: hal.php?status=gagal_delete");
}
exit;
?>
