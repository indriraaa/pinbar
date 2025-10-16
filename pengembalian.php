<?php
session_start();
include 'koneksi.php';

// ===== CEK LOGIN =====
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
} 

$role = $_SESSION['role'];
$nama = $_SESSION['nama'] ?? 'User';

// Query peminjaman yang masih dipinjam
$query = "
    SELECT p.kd_barang AS id_peminjaman, p.kd_barang, b.nama_barang, 
           p.nama_peminjam, p.jumlah, p.tanggal_pinjam, 
           p.tanggal_kembali, p.status
    FROM peminjaman p
    JOIN barang b ON p.kd_barang = b.kd_barang
    WHERE p.status = 'Dipinjam'
";
$peminjaman = mysqli_query($db, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengembalian Barang</title>

<!-- Bootstrap & Icon -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #f5f7f7;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  .navbar {
    background-color: #3AB0A2;
  }
  .navbar-brand { font-weight: 500; font-size: 14px; color: #fff; }
  .nav-link { color: white; }

  .sidebar-card {
    border-radius: 12px;
    background: #fff;
    padding: 15px;
  }

  .sidebar-card .card {
    border: none;
    border-radius: 10px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    transition: 0.3s;
    text-align:center;
  }

  .sidebar-card .card:hover {
    background-color: #3AB0A2;
    color: #fff;
    transform: translateY(-2px);
  }

  .sidebar-card a {
    text-decoration: none;
    color: inherit;
    display:block;
  }

  .sidebar-card i { font-size:26px; transition:0.2s; }
  .sidebar-card a:hover i { transform:scale(1.2); }

  .container-form {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease-in-out;
    padding: 25px;
  }

  .container-form:hover { box-shadow: 0 12px 28px rgba(0,0,0,0.12); }

  .form-label { font-size: 14px; color:#333; font-weight:500; }
  .form-select, .form-control { border-radius:10px; padding:12px; border:1px solid #b2dfdb; background:#fdfdfd; }
  .form-select:focus, .form-control:focus { border-color:#3AB0A2; box-shadow:0 0 8px rgba(58,176,162,0.3); outline:none; }

  .btn-success { font-weight:500; display:flex; align-items:center; }
  .btn-success i { font-size:16px; margin-right:5px; }

  footer {
    text-align: center;
    padding: 10px 0;
    background: #fff;
    margin-top: auto;
    border-top: 1px solid #ddd;
  }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container d-flex align-items-center">
    <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
      <i class="fa fa-bars"></i>
    </button>
    <a class="navbar-brand" href="#">
      <i class="fa-solid fa-location-dot me-1"></i> Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </a>
    <div class="flex-grow-1"></div>
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

<!-- Sidebar Mobile -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body sidebar-card">
    <div class="text-center mb-3">
      <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain;">
    </div>

    <!-- Menu Pegawai & Admin -->
    <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
    <?php if ($role === 'admin'): ?>
      <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
      <a href="pegawai.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user-tie text-info mb-1"></i><br>Data Pegawai</div></div></a>
      <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
      <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-lines text-secondary mb-1"></i><br>Laporan</div></div></a>
    <?php endif; ?>
    <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
    <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
  </div>
</div>

<!-- Konten -->
<div class="container-fluid mt-3 flex-grow-1">
  <div class="row">
    <!-- Sidebar Desktop -->
    <div class="col-lg-3 d-none d-lg-block">
      <div class="sidebar-card">
        <div class="text-center mb-3">
          <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain;">
        </div>

        <!-- Menu Desktop -->
        <?php if($role === 'admin'): ?>
          <a href="beranda.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda
              </div>
            </div>
          </a>
          <a href="hal.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang
              </div>
            </div>
          </a>
          <a href="pengguna.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-user text-primary mb-1"></i><br>Data Pegawai
              </div>
            </div>
          </a>
          <a href="peminjaman.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman
              </div>
            </div>
          </a>
          <a href="pengembalian.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian Barang
              </div>
            </div>
          </a>
          <a href="riwayat.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat
              </div>
            </div>
          </a>
          <a href="laporan.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan
              </div>
            </div>
          </a>

        <?php elseif($role === 'pegawai'): ?>
          <a href="beranda.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda
              </div>
            </div>
          </a>
          <a href="peminjaman.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman
              </div>
            </div>
          </a>
          <a href="pengembalian.php">
            <div class="card mb-2 text-center">
              <div class="card-body">
                <i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian Barang
              </div>
            </div>
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Form Pengembalian -->
    <div class="col-lg-9 col-md-12">
      <div class="bg-white p-4 shadow-sm rounded">
        <h2 class="mb-4">Pengembalian Barang</h2>
        <form action="proses_pengembalian.php" method="POST">
          <table class="table table-bordered align-middle bg-white">
            <tbody>
              <tr>
                <td>
                  <select name="id_peminjaman" id="id_peminjaman" class="form-select" required>
                    <option value="">-- Pilih Peminjaman --</option>
                    <?php while($row = mysqli_fetch_assoc($peminjaman)): ?>
                      <option value="<?= $row['id_peminjaman'] ?>">
                        <?= $row['nama_peminjam'] ?> - <?= $row['nama_barang'] ?> (<?= $row['jumlah'] ?>)
                        | Pinjam: <?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                    class="form-control" value="<?= date('Y-m-d') ?>" required>
                </td>
              </tr>
              <tr>
                <td class="text-end">
                  <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<footer>
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
