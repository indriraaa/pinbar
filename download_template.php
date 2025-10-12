<?php
// Matikan semua output error agar tidak mengganggu header
error_reporting(0);
ob_clean();
ob_start();

// Paksa browser untuk download file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="template_barang.csv"');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Buka output stream
$output = fopen('php://output', 'w');

// Header kolom CSV
fputcsv($output, ['Nama Barang', 'Jumlah']);

// Data contoh
$data = [
    ['Laptop Asus', 10],
    ['Keyboard Logitech', 15],
    ['Mouse Wireless', 20],
    ['Monitor Samsung', 8],
    ['Printer Canon', 5],
    ['Flashdisk 32GB', 30],
    ['Speaker Bluetooth', 12],
    ['Proyektor Epson', 6],
    ['Kabel HDMI', 25],
    ['Headset Rexus', 18],
];

// Tulis ke CSV
foreach ($data as $row) {
    fputcsv($output, $row);
}

fclose($output);
ob_end_flush();
exit;
?>
