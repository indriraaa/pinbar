<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #f5f7f7, #dfeeee);
      color: #2F4858;
    }
    .navbar {
      background-color: #3AB0A2;
      padding: 20px 35px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #ffffff;
      font-size: 14px;
      font-weight: 500;
    }
    .navbar a {
      color: white;
      text-decoration: none;
      font-weight: 500;
    }
    .container {
      max-width: 900px;
      margin: 60px auto;
      background: #fff;
      padding: 50px 40px;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
      animation: fadeIn 0.7s ease-in-out;
    }
    h2 {
      font-weight: 600;
      text-align: center;
      margin-bottom: 40px;
      color: #3AB0A2;
      font-size: 28px;
    }
    .contact-info {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }
    .contact-item {
      background: #f9fdfd;
      padding: 20px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 17px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
        .contact {
      background: #f9fdfd;
      padding: 20px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 17px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .contact-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }
    .icon-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: #3AB0A2;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 20px;
      flex-shrink: 0;
    }
    .contact-item span, 
    .contact-item a {
      font-size: 15px;
      color: #2F4858;
      text-decoration: none;
    }
    .contact-item a:hover {
      color: #3AB0A2;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <span class="d-flex align-items-center gap-2">
    <i class="fa-solid fa-location-dot"></i>
    Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
  </span>
  <a href="index.php"><i class="fa-solid fa-house"></i> Beranda</a>
</div>

<!-- Konten -->
<div class="container">
  <h2>Hubungi Kami</h2>
 
<div class="contact-item">
  <div class="icon-circle"><i class="fa-solid fa-envelope"></i></div>
  <a href="mailto:info@poltekkesbandung.ac.id">info@poltekkesbandung.ac.id</a>
</div>

<div class="contact-item">
  <div class="icon-circle"><i class="fa-brands fa-whatsapp"></i></div>
  <a href="https://wa.me/6289587654567?text=Halo%20Admin%2C%20saya%20ingin%20bertanya." target="_blank">
    +62 89587654567
  </a>
</div>
    <div class="contact">
      <div class="icon-circle"><i class="fa-solid fa-map-marker-alt"></i></div>
      <span>Jl. Padjajaran No 56, Bandung</span>
    </div>
  </div>
</div>

</body>
</html>
