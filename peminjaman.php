<?php
session_start();
include "koneksi.php";

// ===== CEK LOGIN =====
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

$role   = strtolower($_SESSION['role']);
$nama   = $_SESSION['nama'] ?? '';
$nip    = $_SESSION['nip'] ?? '';
$kontak = $_SESSION['kontak'] ?? '';

// ===== AMBIL DATA PENGGUNA (JIKA BELUM ADA DI SESSION) =====
if (empty($nip) || empty($kontak)) {
    $stmt = mysqli_prepare($db, "SELECT nip, kontak FROM pengguna WHERE nama = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $nama);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $nip    = $row['nip'];
        $kontak = $row['kontak'];
        $_SESSION['nip']    = $nip;
        $_SESSION['kontak'] = $kontak;
    }
    mysqli_stmt_close($stmt);
}

// ===== HITUNG JUMLAH PINJAMAN =====
$q_pinjam = mysqli_prepare($db, "SELECT SUM(jumlah) as total_pinjam FROM peminjaman WHERE nama_peminjam = ? AND status = 'Dipinjam'");
$total_pinjam_pegawai = 0;
if ($q_pinjam) {
    mysqli_stmt_bind_param($q_pinjam, "s", $nama);
    mysqli_stmt_execute($q_pinjam);
    $res = mysqli_stmt_get_result($q_pinjam);
    $r_pinjam = mysqli_fetch_assoc($res);
    $total_pinjam_pegawai = $r_pinjam['total_pinjam'] ?? 0;
    mysqli_stmt_close($q_pinjam);
}

// ===== GET BARANG (UNTUK SCAN QR) =====
if (isset($_GET['action']) && $_GET['action'] === 'get_barang') {
    header("Content-Type: application/json");
    $kd_barang = intval($_GET['kd_barang'] ?? 0);

    if ($kd_barang <= 0) {
        echo json_encode(["error" => "Kode barang tidak valid."]);
        exit;
    }

    $stmt = $db->prepare("SELECT kd_barang, nama_barang, jumlah, STATUS FROM barang WHERE kd_barang = ?");
    $stmt->bind_param("i", $kd_barang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "kd_barang"   => $row['kd_barang'],
            "nama_barang" => $row['nama_barang'],
            "jumlah"      => $row['jumlah'],
            "keterangan"  => $row['STATUS'] ?? ""
        ]);
    } else {
        echo json_encode(["error" => "Barang tidak ditemukan"]);
    }

    $stmt->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Peminjaman</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f5f7f7;
  margin:0;
  padding-bottom:70px;
}
.navbar {
  background-color: #3AB0A2;
  padding:12px;
  color:white;
  position:fixed;
  top:0;
  width:100%;
  z-index:1000;
}
.navbar-brand { font-weight:500; font-size:14px; color:#ffffff; }
.sidebar-card {
  border-radius:12px;
  background:#fff;
  padding:15px;
}
.sidebar-card .card {
  border:none;
  border-radius:10px;
  margin-bottom:15px;
  box-shadow:0 2px 4px rgba(0,0,0,0.08);
  cursor:pointer;
  text-align:center;
  transition:0.3s;
}
.sidebar-card .card:hover {
  background-color:#3AB0A2;
  color:#fff;
  transform:translateY(-2px);
}
.sidebar-card a { text-decoration:none; color:inherit; display:block; }
.sidebar-card i { font-size:26px; transition:0.2s; }
.sidebar-card a:hover i { transform:scale(1.2);}
.content-card {
  border-radius:12px;
  background:#fff;
  padding:20px;
  box-shadow:0 4px 10px rgba(0,0,0,0.1);
  margin-bottom:20px;
}
footer {
  background-color:#8BC6BF;
  color:#000;
  font-size:14px;
  position:relative;
  bottom:0;
  left:0;
  width:100%;
  height:60px;
  display:flex;
  align-items:center;
  justify-content:center;
}
.qr-scan-btn {
  position: fixed;
  bottom: 90px;
  right: 25px;
  background:#3AB0A2;
  color:white;
  border-radius:50%;
  width:65px;
  height:65px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:28px;
  box-shadow:0 6px 12px rgba(0,0,0,0.2);
  transition:all 0.3s ease;
  z-index:999;
}
.qr-scan-btn:hover {
  background:#2a8076;
  transform:scale(1.06) rotate(-4deg);
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
    <a class="navbar-brand" href="#"><i class="fa-solid fa-location-dot me-1"></i> Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung</a>
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

<div class="container-fluid mt-5 pt-4">
  <div class="row">

  <!-- Sidebar Mobile (Offcanvas) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <div class="sidebar-card">
      <div class="text-center mb-3"><img src="img/Logo.png" alt="Logo" style="height:50px;"></div>

      <?php if($role === 'admin'): ?>
        <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
        <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
        <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Data Pegawai</div></div></a>
        <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
        <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian Barang</div></div></a>
        <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
        <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>

        <?php elseif($role === 'pegawai'): ?>
        <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
        <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
        <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian Barang</div></div></a>
      <?php endif; ?>

    </div>
  </div>
</div>

   <!-- Sidebar Desktop -->
<div class="col-lg-3 d-none d-lg-block">
  <div class="sidebar-card">
    <div class="text-center mb-3"><img src="img/Logo.png" alt="Logo" style="height:50px;"></div>

    <?php if($role === 'admin'): ?>
      <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
      <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
      <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Data Pegawai</div></div></a>
      <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
      <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian Barang</div></div></a>
      <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
      <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>

      <?php elseif($role === 'pegawai'): ?>
      <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
      <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
      <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian Barang</div></div></a>
    <?php endif; ?>

  </div>
</div>

    <!-- Konten Form -->
    <div class="col-lg-9">
      <div class="content-card">
        <h3 class="text-center mb-4 fw-bold">Form Peminjaman Barang</h3>
        <form method="post" action="proses_peminjaman.php">
          <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="form-floating"><input type="text" name="nama_peminjam" class="form-control" value="<?= htmlspecialchars($nama); ?>" readonly><label>Nama Peminjam</label></div></div>
            <div class="col-md-4"><div class="form-floating"><input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($kontak); ?>" readonly><label>Kontak</label></div></div>
            <div class="col-md-4"><div class="form-floating"><input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($nip); ?>" readonly><label>NIP</label></div></div>
            <div class="col-md-6"><div class="form-floating"><input type="text" name="univ_jurusan" class="form-control" placeholder="Unit / Jurusan" required><label>Unit / Jurusan</label></div></div>
            <div class="col-md-6"><div class="form-floating"><input type="date" name="tanggal_pinjam" class="form-control" required><label>Tanggal Pinjam</label></div></div>
          </div>

          <hr>

          <h5 class="mt-3 mb-3">📱 Barang yang Dipinjam</h5>
          <div id="barang-container">
            <div class="row g-3 barang-item mb-3">
              <div class="col-md-3"><div class="form-floating"><input type="text" name="kd_barang[]" class="form-control kd_barang" placeholder="Kode Barang" required><label>Kode Barang</label><input type="hidden" name="keterangan[]" class="keterangan" value=""></div></div>
              <div class="col-md-3"><div class="form-floating"><input type="text" name="nama_barang[]" class="form-control nama_barang" placeholder="Nama Barang" readonly><label>Nama Barang</label></div></div>
              <div class="col-md-2"><div class="form-floating"><input type="number" name="jumlah[]" class="form-control jumlah" min="1" placeholder="Jumlah" value="1" required><label>Jumlah</label></div></div>
              <div class="col-md-1 d-flex align-items-center"><button type="button" class="btn btn-danger btn-sm remove-barang"><i class="fa fa-trash"></i></button></div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="beranda.php" class="btn btn-danger px-4 py-2"><i class="fa fa-arrow-left"></i> Kembali</a>
            <button type="submit" class="btn btn-success px-4 py-2"><i class="fa fa-save"></i> Simpan Peminjaman</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Tombol QR -->
<button type="button" class="qr-scan-btn" data-bs-toggle="modal" data-bs-target="#qrModal">
  <i class="fa-solid fa-qrcode"></i>
</button>

<!-- Modal QR -->
<div class="modal fade" id="qrModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-qrcode"></i> Pindai QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div id="qr-reader" style="width:100%;max-width:400px;margin:auto;"></div>
        <button id="toggleCameraBtn" class="btn btn-outline-secondary mt-3"><i class="fa-solid fa-camera-rotate"></i> Ganti Kamera</button>
        <div id="qr-alert" class="text-center mt-2"></div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

<script>
let html5QrCode;
let currentCameraIndex = 0;
let cameras = [];
let isScanning = false;

document.addEventListener("DOMContentLoaded", () => {
  html5QrCode = new Html5Qrcode("qr-reader");
  const modal = document.getElementById("qrModal");

  modal.addEventListener("shown.bs.modal", async () => {
    try {
      cameras = await Html5Qrcode.getCameras();
      if (cameras.length === 0) return alert("Tidak ada kamera ditemukan");
      startScanner(cameras[currentCameraIndex].id);
    } catch (err) {
      console.error(err);
    }
  });

  modal.addEventListener("hidden.bs.modal", () => {
    if (isScanning) {
      html5QrCode.stop().catch(console.error);
      isScanning = false;
    }
  });

  document.getElementById("toggleCameraBtn").addEventListener("click", async () => {
    if (!cameras.length) return;
    currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
    if (isScanning) await html5QrCode.stop();
    startScanner(cameras[currentCameraIndex].id);
  });
});

function startScanner(cameraId) {
  html5QrCode.start(
    cameraId,
    { fps: 10, qrbox: 250 },
    (decodedText) => {
      const kdInput = document.querySelector(".kd_barang");
      const namaInput = document.querySelector(".nama_barang");
      if (kdInput && namaInput) {
        kdInput.value = decodedText;
        fetch(?action=get_barang&kd_barang=${decodedText})
          .then(r => r.json())
          .then(data => {
            if (data.error) alert(data.error);
            else namaInput.value = data.nama_barang;
          });
      }
    }
  ).then(() => { isScanning = true; }).catch(console.error);
}
</script>
</body>
</html>
