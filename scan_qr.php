<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Scan QR Code</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
    .scan-container { max-width: 600px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    #reader { width: 100%; border: 2px dashed #3AB0A2; border-radius: 10px; }
  </style>
</head>
<body>
<div class="container scan-container">
  <h2 class="text-center mb-4">📷 Pindai QR Code Barang</h2>

  <div id="reader"></div>

  <script>
    function onScanSuccess(decodedText) {
      alert("QR Berhasil Dibaca: " + decodedText);

      // Simpan hasil scan di localStorage
      localStorage.setItem("scannedQR", decodedText);

      // Redirect ke form peminjaman
      window.location.href = "peminjaman.php";
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: 250 },
      onScanSuccess
    ).catch(err => { console.log("Gagal akses kamera: " + err); });
  </script>

  <div class="text-center mt-4">
    <a href="peminjaman.php" class="btn btn-secondary">⬅ Kembali</a>
  </div>
</div>
</body>
</html>
