<?php
// index.php - Homepage aplikasi FlowLite Manajemen Keuangan Pribadi
// Kode ini adalah versi PHP dari HTML yang Anda berikan. Saya menambahkan logika sederhana untuk session (untuk login/logout) agar lebih dinamis.
// Jika Anda ingin mengintegrasikan dengan database atau fitur lengkap (seperti yang saya buat sebelumnya), beri tahu saya.
// Pastikan file ini disimpan sebagai index.php dan dijalankan di server web dengan PHP (misalnya, XAMPP).
session_start();  // Mulai session untuk mendukung autentikasi user
// require_once 'functions.php';  // Uncomment jika ada file functions.php dari aplikasi lengkap; hapus jika tidak ada
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlowLite Manajemen Keuangan Pribadi</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
  </head>
  <body class="dashboard-body">
    <header class="header">
      <div class="logo">
        <span class="logo-text-nav">FLOWLITE</span>  <!-- Diperbaiki dari "LOWLITE" ke "FLOWLITE" untuk konsistensi dengan title -->
      </div>
      <nav class="navbar">
        <a href="#home">HOME</a>
        <a href="#dompet">DOMPETKU</a>
        <a href="#contact">CONTACT</a> 
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Jika user sudah login, tampilkan Dashboard dan Logout -->
          <a href="dashboard.php" class="btn-login-form">Dashboard</a>
          <a href="logout.php" class="btn-login-form">Logout</a>
        <?php else: ?>
          <!-- Jika belum login, tampilkan Sign in -->
          <a href="signup.html" class="btn-login-form">Sign in</a>
        <?php endif; ?>
      </nav>
    </header>

    <main>
      <section class="hero-section" id="home">
        <div class="hero-content">
          <h1>WELCOME TO FLOWLITE</h1>
          <p>
            "Dengan FlowLite, catat pemasukan dan pengeluaran Anda dalam
            hitungan detik, rencanakan pengeluaran bulanan dengan kiat yang
            mudah diakses, dan lihat overview keuangan melalui diagram
            interaktif. Aplikasi web kami dirancang untuk membuat manajemen
            keuangan sederhana, aman, dan menyenangkan - tanpa ribet!"
          </p>
          <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Jika belum login, tampilkan tombol Get Started -->
            <a href="login.html" class="btn-start">Get Started</a>
          <?php else: ?>
            <!-- Jika sudah login, arahkan ke dashboard -->
            <a href="dashboard.php" class="btn-start">Go to Dashboard</a>
          <?php endif; ?>
        </div>
        <div class="hero-image">
          <img src="LowLite.jpg" alt="Ilustrasi Uang" class="tint-image" />
        </div>
      </section>

      <section class="feature-section" id="home">
        <h2>FEATURE</h2>

        <div class="feature-item feature-reverse">
          <div class="feature-image">
            <img src="money.jpg" alt="Ilustrasi Anggaran" />
          </div>
          <div class="feature-text">
            <p>
              "Dengan FlowLite, catat pemasukan dan pengeluaran Anda dalam
              hitungan detik, rencanakan pengeluaran bulanan dengan kiat yang
              mudah diakses, dan lihat overview keuangan melalui diagram
              interaktif. Aplikasi web kami dirancang untuk membuat manajemen
              keuangan sederhana, aman, dan menyenangkan - tanpa ribet!"
            </p>
          </div>
        </div>

        <div class="feature-item">
          <div class="feature-image">
            <img src="savemoney.jpg" alt="Ilustrasi Daftar Belanja" />
          </div>
          <div class="feature-text">
            <p>
              "Dengan FlowLite, catat pemasukan dan pengeluaran Anda dalam
              hitungan detik, rencanakan pengeluaran bulanan dengan kiat yang
              mudah diakses, dan lihat overview keuangan melalui diagram
              interaktif. Aplikasi web kami dirancang untuk membuat manajemen
              keuangan sederhana, aman, dan menyenangkan - tanpa ribet!"
            </p>
          </div>
        </div>

        <div class="feature-item feature-reverse">
          <div class="feature-image">
            <img src="moneycat.jpg" alt="Ilustrasi Pertumbuhan Keuangan" />
          </div>
          <div class="feature-text">
            <p>
              "Dengan FlowLite, catat pemasukan dan pengeluaran Anda dalam
              hitungan detik, rencanakan pengeluaran bulanan dengan kiat yang
              mudah diakses, dan lihat overview keuangan melalui diagram
              interaktif. Aplikasi web kami dirancang untuk membuat manajemen
              keuangan sederhana, aman, dan menyenangkan - tanpa ribet!"
            </p>
          </div>
        </div>
      </section>

      <section class="contact-section" id="contact">
        <h2>CONTACT US</h2>
        <!-- Tambahkan form contact jika diperlukan -->
        <p>Jika Anda memiliki pertanyaan atau saran, hubungi kami melalui informasi di bawah atau gunakan formulir berikut.</p>
        <form action="process_contact.php" method="post">  <!-- Asumsi ada file process_contact.php -->
          <label for="name">Nama:</label>
          <input type="text" id="name" name="name" required>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
          <label for="message">Pesan:</label>
          <textarea id="message" name="message" rows="5" required></textarea>
          <button type="submit">Kirim</button>
        </form>
      </section>
    </main>

    <footer class="footer">
      <div class="footer-contact">
        <p><i class="fas fa-phone-alt"></i> 081278374985</p>
        <p><i class="far fa-envelope"></i> Flowlite@gmail.com</p>
      </div>
      
      <div class="footer-copyright">
        <span class="logo-text-footer">FLOWLITE</span>  <!-- Diperbaiki untuk konsistensi -->
      </div>
    </footer>
  </body>
</html>
