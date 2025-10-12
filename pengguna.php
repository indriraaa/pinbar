<?php
session_start();
include("koneksi.php");

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// Hanya admin yang boleh akses
if (strtolower($role) !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk admin.');window.location='beranda.php';</script>";
    exit;
}

// Ambil stok dari database
$stok_hp = 0;
$query_hp = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang");
if ($row = mysqli_fetch_assoc($query_hp)) {
    $stok_hp = $row['total'] ?? 0;
}

// Ambil semua data pengguna
$sql_pengguna = "SELECT * FROM pengguna ORDER BY no ASC";
$result_pengguna = $db->query($sql_pengguna);

// Notifikasi status
$status_msg = "";
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case "sukses":
            $status_msg = '<div class="alert alert-success">✅ Data pengguna berhasil ditambahkan!</div>';
            break;
        case "gagal":
            $status_msg = '<div class="alert alert-danger">❌ Gagal menambahkan data pengguna!</div>';
            break;
        case "email_sudah_ada":
            $status_msg = '<div class="alert alert-warning">⚠ Email sudah digunakan, silakan pakai email lain!</div>';
            break;
        case "hapus_sukses":
            $status_msg = '<div class="alert alert-success">🗑 Data pengguna berhasil dihapus!</div>';
            break;
        case "edit_sukses":
            $status_msg = '<div class="alert alert-success">✏ Data pengguna berhasil diperbarui!</div>';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Barang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>

   /* Gaya untuk ikon aksi */
  .action-icon {
    cursor: pointer;
    font-size: 18px;
    margin: 0 6px;
    transition: color 0.3s ease, transform 0.2s ease;
  }

  /* Warna ikon edit */
  .icon-edit {
    color: #007bff; /* biru */
  }

  .icon-edit:hover {
    color: #0056b3;
    transform: scale(1.2);
  }

  /* Warna ikon hapus */
  .icon-delete {
    color: #dc3545; /* merah */
  }

  .icon-delete:hover {
    color: #a71d2a;
    transform: scale(1.2);
  }
  
body { font-family: 'Poppins', sans-serif; background: #f5f7f7; margin: 0; padding: 0; }
.navbar { background-color: #3AB0A2; color: white; }
.navbar-brand { font-weight: 500; font-size: 14px; color: #fff; }
.nav-link { color: white; }
.sidebar-card { border-radius: 12px; background: #ffffff; padding: 15px; }
.sidebar-card .card { border: none; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); transition: 0.3s; cursor: pointer; text-align:center; }
.sidebar-card .card:hover { background-color: #3AB0A2; color: #fff; transform: translateY(-2px); }
.sidebar-card a { text-decoration: none; color: inherit; display:block; }
.sidebar-card i { font-size:26px; transition:0.2s; }
.sidebar-card a:hover i { transform:scale(1.2);}
.content-card { border-radius: 12px; background: #ffffff; padding: 20px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
.table thead { background: linear-gradient(90deg, #6faea7, #8BC6BF); color: #fff; text-align: center;}
.table tbody tr:hover { background-color: #f1fdfc; transition:0.2s;}
.table td, .table th { vertical-align: middle; text-align: center;}
.qr-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap:15px;}
.qr-item { text-align:center; border:1px solid #ddd; padding:10px; border-radius:8px;}
.qr-item img { width:120px; height:120px;}
.qr-item .nama { margin-top:6px; font-weight:600;}
@media print { .no-print { display:none;} .qr-item {border:none;}}
</style>
</head>
<body>

<!-- Navbar dengan Hamburger di kiri -->
<nav class="navbar navbar-expand-lg">
  <div class="container d-flex align-items-center">
    
   <!-- Hamburger (mobile) -->
    <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
      <i class="fa fa-bars"></i>
    </button>
    
    <a class="navbar-brand" href="#"><i class="fa-solid fa-location-dot me-1"></i> Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung</a>
    
    <div class="flex-grow-1"></div>

    <!-- Spacer untuk dorong menu desktop ke kanan -->
    <div class="flex-grow-1"></div>
    
    <!-- Menu desktop -->
    <ul class="navbar-nav d-none d-lg-flex">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?= $nama ?></a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
    
  </div>
</nav>

<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body sidebar-card">
    <div class="text-center mb-3"><img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain;"></div>
    <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
    <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
    <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Pegawai</div></div></a>
    <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
    <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
    <?php if($role=='admin'||$role=='Admin'): ?>
    <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
    <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>
    <?php endif; ?>
  </div>
</div>

<div class="container-fluid mt-3">
  <div class="row">
    <!-- Sidebar Desktop -->
<!-- Sidebar Desktop -->
<div class="col-lg-3 d-none d-lg-block">
  <div class="sidebar-card">
    <div class="text-center mb-3"><img src="img/Logo.png" alt="Logo" style="height:50px;"></div>
    <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
    <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
    <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Pegawai</div></div></a>
    <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
    <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
    <?php if($role=='admin'||$role=='Admin'): ?>
    <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
    <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>
    <?php endif; ?>
  </div>
</div>
   <!-- Konten Kanan -->
    <div class="col-md-8">
      <div class="content-card">
        <h5 class="mb-3 text-center fw-bold">Data Pengguna</h5>
        <?= $status_msg; ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead>
              <tr>
                <th style="width:50px;">No</th>
                <th>Nama</th>
                <th>NIP/NIM</th>
                <th>Role</th>
                <th>Email</th>
                <th style="width:90px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if($result_pengguna->num_rows > 0): ?>
                <?php 
                $i = 1; // counter manual
                while($row = $result_pengguna->fetch_assoc()): ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['nip']; ?></td>
                    <td><?= $row['ROLE']; ?></td>
                    <td><?= $row['email']; ?></td>

<td>
  <i class="fa-regular fa-pen-to-square action-icon icon-edit"
     data-bs-toggle="modal" data-bs-target="#editModal"
     data-no="<?= $row['NO']; ?>"
     data-nama="<?= $row['nama']; ?>"
     data-nip="<?= $row['nip']; ?>"
     data-role="<?= $row['ROLE']; ?>"
     data-email="<?= $row['email']; ?>"></i>

  <i class="fa-solid fa-trash action-icon icon-delete"
     onclick="if(confirm('Apakah yakin ingin menghapus?')){window.location.href='proses_pengguna.php?hapus_no=<?= $row['NO']; ?>'}"></i>
</td>

                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-muted text-center">Belum ada data pengguna</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="proses_pengguna.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="no" id="edit-no">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" id="edit-nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">NIP</label>
            <!-- NIP tidak bisa diedit -->
            <input type="text" name="nip" id="edit-nip" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <!-- Role tidak bisa diedit -->
            <input type="text" name="role" id="edit-role" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="edit-email" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="update" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<footer class="text-center py-3 mt-auto">
  <div class="container">
    <p>&copy; Direktorat Poltekkes Bandung</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto hide alert setelah 3 detik
setTimeout(function() {
    var alertBox = document.getElementById('alert-msg');
    if (alertBox) {
        alertBox.style.transition = "opacity 0.5s ease";
        alertBox.style.opacity = "0";
        setTimeout(function(){ alertBox.remove(); }, 500);
    }
}, 3000);

// Isi data ke modal saat tombol edit ditekan
var editModal = document.getElementById('editModal');
editModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  document.getElementById('edit-no').value = button.getAttribute('data-no');
  document.getElementById('edit-nama').value = button.getAttribute('data-nama');
  document.getElementById('edit-nip').value = button.getAttribute('data-nip');
  document.getElementById('edit-role').value = button.getAttribute('data-role');
  document.getElementById('edit-email').value = button.getAttribute('data-email');
});
</script>
</body>
</html>

<?php $db->close(); ?>
