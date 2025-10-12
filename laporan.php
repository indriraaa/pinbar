<?php
session_start();
include("koneksi.php");

// Pastikan session sudah berisi data
$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? 'Pengguna';

// Tahun sekarang
$tahunSekarang = date("Y");

// Jika form disubmit
if (isset($_POST['cetak'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    // Query laporan peminjaman per bulan
    $sql_laporan = "
        SELECT p.*, b.nama_barang 
        FROM peminjaman p
        JOIN barang b ON p.kd_barang = b.kd_barang
        WHERE MONTH(p.tanggal_pinjam) = '$bulan' 
          AND YEAR(p.tanggal_pinjam) = '$tahun'
        ORDER BY p.tanggal_pinjam ASC
    ";
    $query_laporan = mysqli_query($db, $sql_laporan);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Peminjaman</title>
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
.content-box { border-radius: 12px; background: #ffffff; padding: 20px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
.table thead { background: linear-gradient(90deg, #6faea7, #8BC6BF); color: #fff; text-align: center;}
.table tbody tr:hover { background-color: #f1fdfc; transition:0.2s;}
.table td, .table th { vertical-align: middle; text-align: center;}
@media print { .no-print { display:none;} }
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
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?= htmlspecialchars($nama) ?></a>
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
    <div class="text-center mb-3"><img src="img/Logo.png" alt="Logo" style="height:50px;"></div>
    <a href="beranda.php"><div class="card"><div class="card-body"><i class="fa-solid fa-house text-primary mb-1"></i><br>Beranda</div></div></a>
    <a href="hal.php"><div class="card"><div class="card-body"><i class="fa-solid fa-box text-success mb-1"></i><br>Data Barang</div></div></a>
    <a href="pengguna.php"><div class="card"><div class="card-body"><i class="fa-solid fa-user text-primary mb-1"></i><br>Pegawai</div></div></a>
    <a href="peminjaman.php"><div class="card"><div class="card-body"><i class="fa-solid fa-hand-holding text-success mb-1"></i><br>Peminjaman</div></div></a>
    <a href="pengembalian.php"><div class="card"><div class="card-body"><i class="fa-solid fa-rotate-left text-danger mb-1"></i><br>Pengembalian</div></div></a>
    <?php if (strtolower($role) == 'admin'): ?>
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
        <?php if (strtolower($role) == 'admin'): ?>
        <a href="riwayat.php"><div class="card"><div class="card-body"><i class="fa-solid fa-clock-rotate-left text-warning mb-1"></i><br>Riwayat</div></div></a>
        <a href="laporan.php"><div class="card"><div class="card-body"><i class="fa-solid fa-file-alt text-info mb-1"></i><br>Laporan</div></div></a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Konten Utama -->
    <div class="col-lg-9 col-md-12">
      <div class="content-box p-4">
        <div class="card no-print mb-4">
          <div class="card-header">
            <h5><i class="fa fa-file-alt me-2"></i>Laporan Peminjaman Barang</h5>
          </div>
          <div class="card-body">
            <form method="POST">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="bulan" class="form-label">Bulan</label>
                  <select class="form-control" id="bulan" name="bulan" required>
                    <option value="">-- Pilih Bulan --</option>
                    <?php
                    $bulan_list = [
                        1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",
                        5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",
                        9=>"September",10=>"Oktober",11=>"November",12=>"Desember"
                    ];
                    foreach ($bulan_list as $num => $namaBulan) {
                        $selected = (isset($bulan) && $bulan == $num) ? "selected" : "";
                        echo "<option value='$num' $selected>$namaBulan</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="tahun" class="form-label">Tahun</label>
                  <input type="number" class="form-control" id="tahun" name="tahun" min="2000" max="2100" value="<?= $tahunSekarang; ?>" required>
                </div>
              </div>
              <div class="d-flex justify-content-between mt-3">
                <button type="submit" name="cetak" class="btn btn-success"><i class="fa fa-search"></i> Tampilkan Laporan</button>
                <a href="beranda.php" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
            </form>
          </div>
        </div>

        <!-- Hasil Laporan -->
        <?php if (isset($query_laporan)) { ?>
        <div class="card">
          <div class="card-body">
            <h5 class="text-center mb-4">
              LAPORAN PEMINJAMAN BARANG <br>
              BULAN <?= strtoupper($bulan_list[$bulan]); ?> <?= $tahun; ?>
            </h5>

            <div class="table-responsive">
              <table id="tabel-laporan" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Univ/Jurusan</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  if (mysqli_num_rows($query_laporan) > 0) {
                    while ($lap = mysqli_fetch_assoc($query_laporan)) {
                        echo "<tr>
                                <td>$no</td>
                                <td>{$lap['nama_peminjam']}</td>
                                <td>{$lap['nama_barang']}</td>
                                <td>{$lap['jumlah']}</td>
                                <td>{$lap['univ_jurusan']}</td>
                                <td>{$lap['tanggal_pinjam']}</td>
                                <td>" . ($lap['tanggal_kembali'] ?? '-') . "</td>
                                <td><span class='badge ".($lap['STATUS']=='Dipinjam'?'bg-warning':'bg-success')."'>".$lap['STATUS']."</span></td>
                              </tr>";
                        $no++;
                    }
                  } else {
                    echo "<tr><td colspan='8' class='text-center text-muted'>Tidak ada data</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>

            <div class="mt-3 no-print d-flex justify-content-between">
              <a href="laporan.php" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Batal</a>
              <div>
                <button onclick="window.print()" class="btn btn-success me-2"><i class="fa fa-print"></i> Cetak</button>
                <button onclick="exportTableToExcel('tabel-laporan', 'Laporan_Peminjaman')" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<script>
function exportTableToExcel(tableID, filename = '') {
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    filename = filename?filename+'.xls':'excel_data.xls';
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Footer -->
<footer class="text-center py-3 mt-4">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

</body>
</html>
