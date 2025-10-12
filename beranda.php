<?php
session_start();
include "koneksi.php";

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// Total stok barang
$total_stok = 0;
$q_stok = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang");
if ($row = mysqli_fetch_assoc($q_stok)) {
    $total_stok = $row['total'] ?? 0;
}

// Total barang dipinjam
$total_pinjam = 0;
$q_pinjam = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM peminjaman WHERE status='Dipinjam'");
if ($row = mysqli_fetch_assoc($q_pinjam)) {
    $total_pinjam = $row['total'] ?? 0;
}

// Riwayat peminjaman
$q_riwayat = mysqli_query($db, "
    SELECT p.*, b.nama_barang 
    FROM peminjaman p 
    JOIN barang b ON p.kd_barang = b.kd_barang 
    ORDER BY p.NO DESC LIMIT 10
");
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

    <?php if ($role == 'admin' || $role == 'Admin'): ?>
      <!-- Sidebar untuk Admin -->
      <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
      <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
      <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Pegawai</div></div></a>
      <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
      <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
      <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
      <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>
    <?php else: ?>
      <!-- Sidebar untuk Pegawai -->
      <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
      <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
      <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
    <?php endif; ?>
  </div>
</div>


<div class="container-fluid mt-3">
  <div class="row gx-3"> <!-- gx-3 untuk jarak antar kolom -->
    
    <!-- Sidebar Desktop -->
    <div class="col-lg-3 d-none d-lg-block">
      <div class="sidebar-card">
        <div class="text-center mb-3">
          <img src="img/Logo.png" alt="Logo" style="height:50px;">
        </div>

        <?php if ($role == 'admin' || $role == 'Admin'): ?>
          <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
          <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
          <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Pegawai</div></div></a>
          <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
          <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
          <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
          <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>
        <?php else: ?>
          <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
          <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
          <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Konten kanan -->
    <div class="col-lg-9 col-md-12">
      <div class="content-card mb-4">
        <h5 class="text-center fw-bold mb-3">Ringkasan Data Barang</h5>
        <div class="row text-center g-3">
          <div class="col-md-4">
            <div class="p-3 rounded" style="background-color:#e8f0ff; color:#0d6efd;">
              <h6>Total Stok</h6>
              <h3 class="fw-bold"><?= $total_stok; ?></h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 rounded" style="background-color:#ffeaea; color:#dc3545;">
              <h6>Dipinjam</h6>
              <h3 class="fw-bold"><?= $total_pinjam; ?></h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 rounded" style="background-color:#e9f9f1; color:#198754;">
              <h6>Tersedia</h6>
              <h3 class="fw-bold"><?= $total_stok - $total_pinjam; ?></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Riwayat Peminjaman -->
      <div class="content-card">
        <h5 class="text-center fw-bold mb-3">Riwayat Peminjaman Barang</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while($r = mysqli_fetch_assoc($q_riwayat)): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($r['nama_peminjam']); ?></td>
                <td><?= htmlspecialchars($r['nama_barang']); ?></td>
                <td><?= $r['jumlah']; ?></td>
                <td><?= $r['tanggal_pinjam']; ?></td>
                <td><?= $r['tanggal_kembali'] ?: '-'; ?></td>
                <td>
                  <span class="badge <?= $r['STATUS']=='Dipinjam'?'bg-danger':'bg-success'; ?>">
                    <?= $r['STATUS']; ?>
                  </span>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

            <tbody>
              <?php $no = 1; while($r = mysqli_fetch_assoc($q_riwayat)): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($r['nama_peminjam']); ?></td>
                <td><?= htmlspecialchars($r['nama_barang']); ?></td>
                <td><?= $r['jumlah']; ?></td>
                <td><?= $r['tanggal_pinjam']; ?></td>
                <td><?= $r['tanggal_kembali'] ?: '-'; ?></td>
                <td>
                  <span class="badge <?= $r['STATUS']=='Dipinjam'?'bg-danger':'bg-success'; ?>">
                    <?= $r['STATUS']; ?>
                  </span>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<footer class="text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

toggleBtn.addEventListener('click', () => {
  sidebar.classList.toggle('show');
  overlay.classList.toggle('show');
});

overlay.addEventListener('click', () => {
  sidebar.classList.remove('show');
  overlay.classList.remove('show');
});
</script>
</body>
</html>
