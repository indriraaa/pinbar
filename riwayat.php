<?php
session_start();
include "koneksi.php";

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

$result = mysqli_query($db, "SELECT p.*, b.nama_barang 
                                FROM peminjaman p 
                                JOIN barang b ON p.kd_barang = b.kd_barang 
                                ORDER BY p.tanggal_pinjam DESC");

// Total stok
$total_stok = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang"))['total'] ?? 0;
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
.badge-pinjam { background-color: red; color: white; }
.badge-kembali { background-color: #2ecc71; color: white; }
@media print { .no-print { display:none;} }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container d-flex align-items-center">
    <!-- Tombol Hamburger -->
    <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
      <i class="fa fa-bars"></i>
    </button>
    
    <a class="navbar-brand" href="#">
      <i class="fa-solid fa-location-dot me-1"></i> Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </a>

    <div class="flex-grow-1"></div>
    <ul class="navbar-nav d-none d-lg-flex">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?= htmlspecialchars($nama) ?></a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Sidebar Offcanvas -->
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

<!-- Konten -->
<div class="container-fluid mt-3">
  <div class="row">
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

    <!-- Konten Riwayat -->
    <div class="col-md-8">
      <div class="content-box p-4 bg-white rounded shadow-sm">
        <h2 class="mb-4">Riwayat Peminjaman</h2>
        <div class="table-responsive" style="max-height:500px; overflow-y:auto;">
          <table class="table table-bordered table-hover align-middle text-center">
            <thead style="background: linear-gradient(90deg, #3AB0A2, #66c2bc); color: #fff; font-weight:600;">
              <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>NIP</th>
                <th>Kontak</th>
                <th>Unit/Jurusan</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                  <td><?= htmlspecialchars($row['nip']) ?></td>
                  <td><?= htmlspecialchars($row['kontak']) ?></td>
                  <td><?= htmlspecialchars($row['univ_jurusan']) ?></td>
                  <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                  <td><?= htmlspecialchars($row['jumlah']) ?></td>
                  <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                  <td><?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                  <td>
                    <?php if($row['STATUS']=='Dipinjam'): ?>
                      <span class="badge badge-pinjam">Dipinjam</span>
                    <?php else: ?>
                      <span class="badge badge-kembali">Dikembalikan</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          <a href="beranda.php" class="btn btn-danger w-100"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-4">
  <div class="container">
    <p>&copy; Direktorat Poltekkes Bandung</p>
  </div>
</footer>

<!-- ✅ Tambahkan ini agar hamburger berfungsi -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
