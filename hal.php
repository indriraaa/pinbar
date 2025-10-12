<?php
session_start();
include "koneksi.php";
require_once __DIR__ . "/phpqrcode/qrlib.php";

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// --- Proses update dari modal popup ---
if (isset($_POST['update'])) {
    $kd_barang = $_POST['kd_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];

    $update = mysqli_query($db, "UPDATE barang SET nama_barang='$nama_barang', jumlah='$jumlah' WHERE kd_barang='$kd_barang'");
    if ($update) {
        echo "<script>alert('Data berhasil diperbarui'); window.location='hal.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data');</script>";
    }
}

// Ambil stok total
$stok_hp = 0;
$query_hp = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang");
if ($row = mysqli_fetch_assoc($query_hp)) {
    $stok_hp = $row['total'] ?? 0;
}

// Query barang, Telor Asin selalu di atas
$sql_barang = "SELECT * FROM barang ORDER BY (nama_barang='Telor Asin') DESC, kd_barang ASC";
$query_barang = mysqli_query($db, $sql_barang);

// Semua barang untuk modal QR
$result_qr = mysqli_query($db, "SELECT kd_barang, nama_barang FROM barang ORDER BY kd_barang ASC");

// Folder sementara QR
$tempDir = __DIR__ . "/qrcodes/";
if (!file_exists($tempDir)) mkdir($tempDir, 0755, true);
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
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?= htmlspecialchars($nama) ?></a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Sidebar -->
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

    <!-- Konten -->
    <div class="col-lg-9">
      <div class="content-card">
        <h5 class="mb-3 text-center fw-bold">Daftar Stok Barang</h5>

        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Jumlah</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              <?php
              $no=1;
              while($barang=mysqli_fetch_assoc($query_barang)){
                  $highlight=($barang['nama_barang']=='Telor Asin')?'fw-bold text-success':'';
                  echo "<tr class='{$highlight}'>";
                  echo "<td>{$no}</td>";
                  echo "<td>{$barang['kd_barang']}</td>";
                  echo "<td>{$barang['nama_barang']}</td>";
                  echo "<td>{$barang['jumlah']}</td>";
                  echo "<td>
                    <button class='btn btn-sm text-primary' data-bs-toggle='modal' data-bs-target='#editModal' 
                    data-kd='{$barang['kd_barang']}' data-nama='".htmlspecialchars($barang['nama_barang'],ENT_QUOTES)."' data-jumlah='{$barang['jumlah']}'><i class='fa-regular fa-pen-to-square'></i></button>
                    <a href='hapus.php?kd_barang={$barang['kd_barang']}' class='text-danger ms-2'><i class='fa-solid fa-trash'></i></a>
                  </td>";
                  echo "</tr>";
                  $no++;
              }
              ?>
            </tbody>
          </table>
        </div>

       <!-- Tombol Aksi -->
<div class="mt-3 text-end">
  <a href="qrcode.php" class="btn btn-primary me-2">
    <i class="fa-solid fa-qrcode me-1"></i> Print QR
  </a>

  <a href="download_template.php" class="btn btn-warning me-2">
    <i class="fa-solid fa-download me-1"></i> Template CSV
  </a>

  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
    <i class="fa-solid fa-file-csv me-1"></i> Import CSV
  </button>
</div>


        <!-- Modal Import CSV -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Import CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form action="import_csv.php" method="post" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label for="csvFile" class="form-label">Pilih File CSV</label>
                    <input type="file" name="csvFile" id="csvFile" class="form-control" accept=".csv" required>
                  </div>
                  <button type="submit" class="btn btn-success">Upload</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-3 bg-light">&copy; Direktorat Poltekkes Bandung</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Modal edit
var editModal=document.getElementById('editModal');
if(editModal){
  editModal.addEventListener('show.bs.modal',function(e){
    var btn=e.relatedTarget;
    document.getElementById('edit_kd_barang').value=btn.getAttribute('data-kd');
    document.getElementById('edit_nama_barang').value=btn.getAttribute('data-nama');
    document.getElementById('edit_jumlah').value=btn.getAttribute('data-jumlah');
  });
}

// Tutup sidebar otomatis
document.querySelectorAll('.offcanvas a').forEach(link=>{
  link.addEventListener('click',()=>{
    var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasSidebar'));
    if(offcanvas) offcanvas.hide();
  });
});
</script>

</body>
</html>
