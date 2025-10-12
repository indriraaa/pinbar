<?php
session_start();
include("koneksi.php");

if (!isset($_GET['no'])) {
    header("Location: pengguna.php?status=gagal");
    exit;
}

$no = intval($_GET['no']);
$sql = "SELECT * FROM pengguna WHERE no = $no";
$result = $db->query($sql);

if ($result->num_rows == 0) {
    header("Location: pengguna.php?status=gagal");
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Pengguna</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background: #f5f7f7; margin: 0; padding: 0; }
.container { max-width: 700px; margin-top: 50px; }
.card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.card-header { background: #3AB0A2; color: #fff; border-radius: 12px 12px 0 0; }
.btn-custom { background: #3AB0A2; color: #fff; }
.btn-custom:hover { background: #2d8378; color: #fff; }
</style>
</head>
<body>

<div class="container">
  <div class="card">
    <div class="card-header text-center">
      <h4><i class="fa-solid fa-user-pen me-2"></i>Edit Data Pengguna</h4>
    </div>
    <div class="card-body">
      <form action="proses_pengguna.php" method="POST">
        <input type="hidden" name="no" value="<?= $data['no']; ?>">

        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">NIP</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($data['nip']); ?>" disabled>
          <input type="hidden" name="NIP" value="<?= htmlspecialchars($data['nip']); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($data['role']); ?>" disabled>
          <input type="hidden" name="role" value="<?= htmlspecialchars($data['role']); ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password (kosongkan jika tidak ingin ubah)</label>
          <input type="password" name="password" class="form-control" placeholder="••••••">
        </div>

        <div class="d-flex justify-content-between">
          <a href="pengguna.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" name="edit" class="btn btn-custom">
            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
