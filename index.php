<?php
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
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        header { background-color: #4CAF50; color: white; padding: 1rem; text-align: center; }
        nav { background-color: #333; color: white; padding: 0.5rem; }
        nav a { color: white; margin: 0 1rem; text-decoration: none; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 1rem; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .summary { display: flex; justify-content: space-around; margin-bottom: 2rem; }
        .summary div { text-align: center; padding: 1rem; border: 1px solid #ddd; border-radius: 5px; flex: 1; margin: 0 0.5rem; }
        .summary h3 { margin: 0; color: #4CAF50; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table th, table td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
        table th { background-color: #f2f2f2; }
        footer { text-align: center; padding: 1rem; background-color: #333; color: white; margin-top: 2rem; }
    </style>
</head>
<body>
    <header>
        <h1>Manajemen Keuangan Pribadi</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="tambah_transaksi.php">Tambah Transaksi</a>
        <a href="laporan.php">Laporan</a>
    </nav>
    <div class="container">
        <h2>Ringkasan Keuangan</h2>
        <div class="summary">
            <div>
                <h3>Total Saldo</h3>
                <p>Rp <?php echo number_format($ringkasan['saldo'] ?? 0, 0, ',', '.'); ?></p>
            </div>
            <div>
                <h3>Total Pemasukan</h3>
                <p>Rp <?php echo number_format($ringkasan['total_pemasukan'] ?? 0, 0, ',', '.'); ?></p>
            </div>
            <div>
                <h3>Total Pengeluaran</h3>
                <p>Rp <?php echo number_format($ringkasan['total_pengeluaran'] ?? 0, 0, ',', '.'); ?></p>
            </div>
        </div>
        
        <h2>Transaksi Terbaru</h2>
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
                <?php while ($row = mysqli_fetch_assoc($result_transaksi)): ?>
                <tr>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo $row['nama_akun']; ?></td>
                    <td><?php echo ucfirst($row['jenis']); ?></td>
                    <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['deskripsi']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2023 Aplikasi Manajemen Keuangan.</p>
    </footer>
</body>
</html>