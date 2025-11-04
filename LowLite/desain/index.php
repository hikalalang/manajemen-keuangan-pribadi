<?php
// Asumsikan 'koneksi.php' berisi $conn (koneksi MySQLi)
include 'koneksi.php';

// Query ringkasan
$query_ringkasan = "SELECT 
    SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE 0 END) AS total_pemasukan,
    SUM(CASE WHEN jenis = 'pengeluaran' THEN jumlah ELSE 0 END) AS total_pengeluaran,
    (SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = 'pengeluaran' THEN jumlah ELSE 0 END)) AS saldo
    FROM transaksi";
$result_ringkasan = mysqli_query($conn, $query_ringkasan);
$ringkasan = mysqli_fetch_assoc($result_ringkasan);

// Query transaksi terbaru
$query_transaksi = "SELECT t.*, a.nama_akun FROM transaksi t JOIN akun a ON t.id_akun = a.id ORDER BY t.tanggal DESC LIMIT 5";
$result_transaksi = mysqli_query($conn, $query_transaksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Keuangan</title>
    <style>
        /* Menggunakan font Inter untuk tampilan yang lebih bersih */
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background-color: #f3f4f6; } 
        header { 
            background-color: #1f2937; /* Dark Gray */
            color: white; 
            padding: 1rem; 
            text-align: center; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Gaya Navigasi Baru */
        nav { 
            background-color: #374151; /* Gray gelap untuk kontras */
            color: white; 
            padding: 0.75rem 0; 
            display: flex;
            justify-content: space-between; /* Untuk memisahkan navigasi utama dan tombol auth */
            align-items: center;
        }
        .nav-links, .auth-links { 
            display: flex; 
            align-items: center; 
            padding: 0 1rem;
        }
        nav a { 
            color: white; 
            margin: 0 1rem; 
            text-decoration: none; 
            padding: 0.5rem 0.75rem; 
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        nav a:hover { 
            background-color: #4b5563; 
        }
        
        /* Gaya Tombol Auth (Signup/Login) */
        .auth-links .btn-login {
            background-color: transparent;
            border: 1px solid white;
        }
        .auth-links .btn-signup {
            background-color: #4CAF50; /* Hijau */
            font-weight: bold;
            margin-left: 0.5rem;
            border: none;
        }
        .auth-links .btn-signup:hover {
            background-color: #45a049;
        }

        /* Container dan komponen lainnya */
        .container { max-width: 1200px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1.5rem; color: #1f2937; }
        .summary { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .summary div { 
            text-align: center; 
            padding: 1.5rem; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            flex: 1; 
            min-width: 250px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            background-color: #ffffff;
        }
        .summary h3 { margin: 0 0 0.5rem 0; color: #10b981; } /* Hijau cerah untuk judul */
        .summary p { font-size: 1.5rem; font-weight: bold; color: #374151; }
        .summary-saldo { background-color: #d1fae5; border-color: #a7f3d0; } /* Highlight Saldo */
        
        /* Saldo Color Logic */
        .saldo-amount.positive { color: #10b981; }
        .saldo-amount.negative { color: #ef4444; }
        .pemasukan-amount { color: #10b981; }
        .pengeluaran-amount { color: #ef4444; }

        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table th, table td { border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; }
        table th { background-color: #f9fafb; color: #374151; font-weight: 600; }
        .jenis-pemasukan { color: #10b981; font-weight: bold; }
        .jenis-pengeluaran { color: #ef4444; font-weight: bold; }
        footer { text-align: center; padding: 1rem; background-color: #374151; color: white; margin-top: 2rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header>
        <h1>LowLite
        </h1>
    </header>
    
    <!-- Peningkatan Struktur NAV -->
    <nav>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="tambah_transaksi.php">Tambah Transaksi</a>
            <a href="laporan.php">Laporan</a>
        </div>
        <div class="auth-links">
            <!-- Tautan Login dan Signup ditambahkan di sini -->
            <a href="login.php" class="btn-login">Login</a>
            <a href="register.php" class="btn-signup">Signup</a>
        </div>
    </nav>
    
    <div class="container">
        <h2>Ringkasan Keuangan</h2>
        <div class="summary">
            <div class="summary-saldo">
                <h3>Total Saldo</h3>
                <p class="saldo-amount <?php echo ($ringkasan['saldo'] >= 0 ? 'positive' : 'negative'); ?>">
                    Rp <?php echo number_format($ringkasan['saldo'] ?? 0, 0, ',', '.'); ?>
                </p>
            </div>
            <div>
                <h3>Total Pemasukan</h3>
                <p class="pemasukan-amount">
                    Rp <?php echo number_format($ringkasan['total_pemasukan'] ?? 0, 0, ',', '.'); ?>
                </p>
            </div>
            <div>
                <h3>Total Pengeluaran</h3>
                <p class="pengeluaran-amount">
                    Rp <?php echo number_format($ringkasan['total_pengeluaran'] ?? 0, 0, ',', '.'); ?>
                </p>
            </div>
        </div>
        
        <h2>Transaksi Terbaru (5 Data)</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_transaksi) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_transaksi)): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_akun']); ?></td>
                        <td class="<?php echo ($row['jenis'] == 'pemasukan' ? 'jenis-pemasukan' : 'jenis-pengeluaran'); ?>">
                            <?php echo ucfirst($row['jenis']); ?>
                        </td>
                        <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #9ca3af;">Belum ada data transaksi yang tercatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2023 Aplikasi Manajemen Keuangan LowLite.</p>
    </footer>
</body>
</html>
