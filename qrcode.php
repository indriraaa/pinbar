<?php
require_once __DIR__ . '/phpqrcode/qrlib.php';
include "koneksi.php";

// Folder QR
$dir = __DIR__ . "/qrcodes/";
if (!file_exists($dir)) mkdir($dir, 0755, true);

// Ambil semua data barang
$query = mysqli_query($db, "SELECT * FROM barang ORDER BY kd_barang ASC");
while ($row = mysqli_fetch_assoc($query)) {
    $file = $dir . $row['kd_barang'] . '.png';
    if (!file_exists($file)) {
        $text = "{$row['kd_barang']}";
        QRcode::png($text, $file, QR_ECLEVEL_L, 6);
    }
}
mysqli_data_seek($query, 0); // Reset pointer query untuk loop kedua
?>
<!DOCTYPE html>
<html>
<head>
    <title>QR Code Barang</title>
    <style>
        body { font-family: Arial; text-align: center; background: #f8f8f8; }
        table { border-collapse: collapse; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; }
        .no-print button {
            padding: 6px 12px;
            background: #1976d2;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .no-print button:hover { background: #0f5cb4; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<h2>Daftar QR Code Barang</h2>
<table>
    <thead>
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th class="no-print">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['kd_barang']) ?></td>
            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
            <td><?= htmlspecialchars($row['jumlah']) ?></td>
            <td class="no-print">
                <button onclick="printSingleQR('<?= htmlspecialchars($row['kd_barang']) ?>')">🖨 Print QR</button>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="no-print">
    <button onclick="printAllQR()">🖨 Print Semua QR</button>
    <button onclick="window.location.href='beranda.php'">⬅️ Kembali</button>
</div>

<script>
function printSingleQR(kd) {
    const win = window.open('', '_blank', 'width=400,height=500');
    win.document.write(`
        <html>
        <head>
            <title>QR ${kd}</title>
            <style>
                body { margin:0; text-align:center; }
                img { width: 250px; height: 250px; margin-top: 50px; }
                button {
                    padding: 6px 12px;
                    background: #1976d2;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    margin-top: 20px;
                }
                button:hover { background: #0f5cb4; }
                @media print { button { display: none; } body { background: #fff; } }
            </style>
        </head>
        <body>
            <img src="qrcodes/${kd}.png" alt="QR Code">
            <div>
                <button onclick="window.print()">🖨 Print</button>
                <button onclick="window.close()">⬅️ Kembali</button>
            </div>
        </body>
        </html>
    `);
}

function printAllQR() {
    const win = window.open('', '_blank');
    win.document.write(`
        <html>
        <head>
            <title>QR Semua Barang</title>
            <style>
                body { font-family: Arial; text-align: center; margin: 0; padding: 20px; }
                .qr-grid {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                .qr-item {
                    padding: 10px;
                    margin: 5px;
                }
                img { width: 200px; height: 200px; }
                button {
                    padding: 6px 12px;
                    background: #1976d2;
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    margin: 10px 5px;
                }
                button:hover { background: #0f5cb4; }
                @media print { button { display: none; } body { background: #fff; } }
            </style>
        </head>
        <body>
            <div class="qr-grid">
    `);
    <?php
    $q2 = mysqli_query($db, "SELECT * FROM barang ORDER BY kd_barang ASC");
    while ($r = mysqli_fetch_assoc($q2)) {
        echo "win.document.write(`<div class='qr-item'><img src='qrcodes/" . htmlspecialchars($r['kd_barang']) . ".png'></div>`);";
    }
    ?>
    win.document.write(`
            </div>
            <div>
                <button onclick="window.print()">🖨 Print Semua</button>
                <button onclick="window.close()">⬅️ Kembali</button>
            </div>
        </body>
        </html>
    `);
}
</script>

</body>
</html>
