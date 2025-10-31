<?php
// =========================================================
// MOCKING & SETUP (Pengganti koneksi database dan otentikasi nyata)
// Data di bawah adalah tiruan dan harus diganti dengan logika database
// (misalnya Firestore) dan otentikasi nyata.
// =========================================================
session_start();

// MOCK: Fungsi Otentikasi
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        // Jika belum ada sesi, tetapkan ID tiruan
        $_SESSION['user_id'] = 1; 
    }
}
requireLogin();

$user_id = $_SESSION['user_id'];
$username = 'AdminFlowlite'; // Username tiruan
$current_month = date('F Y'); // Nama bulan saat ini (misalnya: October 2025)

// MOCK: Data Transaksi
$mock_transactions = [
    // Pemasukan (Income) - Total Rp 8.000.000
    ['type' => 'income', 'amount' => 7500000, 'date' => '2025-10-01'],
    ['type' => 'income', 'amount' => 500000, 'date' => '2025-10-15'],
    // Pengeluaran (Expense) - Total Rp 1.650.000
    ['type' => 'expense', 'amount' => 1200000, 'date' => '2025-10-03'],
    ['type' => 'expense', 'amount' => 350000, 'date' => '2025-10-20'],
    ['type' => 'expense', 'amount' => 100000, 'date' => '2025-10-25'],
];

$total_income = 0;
$total_expense = 0;

// Hitung total pemasukan dan pengeluaran
foreach ($mock_transactions as $t) {
    // Dalam aplikasi nyata, Anda akan menambahkan filter tanggal di sini
    if ($t['type'] === 'income') {
        $total_income += $t['amount'];
    } else {
        $total_expense += $t['amount'];
    }
}

$net_balance = $total_income - $total_expense;

// =========================================================
// LOGIKA DIAGRAM BATANG
// =========================================================

// Tentukan nilai maksimum untuk penskalaan diagram (mencegah pembagian nol)
$max_value = max($total_income, $total_expense, 1); 

// Hitung persentase lebar untuk diagram batang
$income_width_percent = ($total_income / $max_value) * 100;
$expense_width_percent = ($total_expense / $max_value) * 100;

// Fungsi untuk format mata uang Rupiah
function formatRupiah($amount) {
    // Menghilangkan tanda negatif jika ada, karena formatRupiah seringkali digunakan untuk tampilan saja
    $abs_amount = abs($amount); 
    return 'Rp' . number_format($abs_amount, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlowLite - Dompet</title>
    <!-- Font Poppins -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <!-- Font Awesome Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CSS Internal -->
    <style>
        /* Variabel dan Base Styling */
        :root {
            --primary-color: #f44336; /* Merah FlowLite */
            --highlight-active: #ffc107; /* Kuning terang */
            --background-light: #f7f9fc;
            --income-color: #4caf50; /* Hijau */
            --expense-color: #f44336; /* Merah */
            --logo-font: 'Poppins', sans-serif;
            --sidebar-width: 250px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-light);
            margin: 0;
            padding: 0;
        }

        /* Container Utama */
        .app-container {
            display: grid;
            grid-template-areas:
                "header header"
                "sidebar main";
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
        }

        /* Header */
        .app-header {
            grid-area: header;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .app-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .logout-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .logout-btn:hover { background-color: #d32f2f; }

        /* Sidebar */
        .sidebar {
            grid-area: sidebar;
            width: var(--sidebar-width);
            background-color: #2c3e50; /* Dark blue/grey */
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .sidebar-profile {
            padding: 0 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        .sidebar-profile i {
            font-size: 2.5rem;
            margin-bottom: 5px;
            color: var(--highlight-active);
        }
        .sidebar-nav {
            display: flex;
            flex-direction: column;
        }
        .sidebar-nav a {
            padding: 12px 20px;
            text-decoration: none;
            color: #ecf0f1;
            border-left: 5px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .sidebar-nav a:hover {
            background-color: #34495e;
        }
        .sidebar-nav a.active {
            background-color: #34495e;
            border-left-color: var(--highlight-active);
            font-weight: 700;
            color: white;
        }

        /* Konten Utama */
        .main-content {
            grid-area: main;
            padding: 2rem;
            overflow-y: auto;
        }
        .main-content h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 10px;
        }
        
        /* Summary Cards */
        .dompet-summary {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .dompet-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 250px;
            text-align: center;
            transition: transform 0.3s;
        }
        .dompet-card:hover {
            transform: translateY(-5px);
        }
        .dompet-card h3 {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .dompet-card p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }
        .dompet-card:first-child h3 {
            color: var(--income-color);
        }
        .dompet-card:nth-child(2) h3 {
            color: var(--expense-color);
        }
        .net-balance-detail {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 10px;
            color: <?php echo $net_balance >= 0 ? 'var(--income-color)' : 'var(--expense-color)'; ?>;
        }


        /* Chart Container */
        .content-box {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .bar-chart-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
        }
        .chart-bar {
            background-color: #ddd;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 0 10px;
            font-weight: 600;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            transition: width 1s ease-out;
            max-width: 100%; /* Safety max width */
        }
        .bar-pemasukan {
            background-color: var(--income-color);
        }
        .bar-pengeluaran {
            background-color: var(--expense-color);
        }
        .bar-label {
            margin-left: auto;
            padding-left: 10px;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .app-container {
                grid-template-areas:
                    "header header"
                    "main main";
                grid-template-columns: 1fr;
            }
            .sidebar {
                display: none;
            }
            .dompet-summary {
                flex-direction: column;
                gap: 1rem;
            }
            .main-content {
                padding: 1rem;
            }
        }
</style>
</head>
<body>
<div class="app-container">
<header class="app-header">
  <div class="app-logo">
<span style="font-family: var(--logo-font)">FLOWLITE</span> <!-- Diperbaiki dari LOWLITE -->
<i
class="fas fa-lightbulb
style="color: var(--highlight-active)"
></i>
</div>
<a href="index.html" class="logout-btn"
>Out <i class="fas fa-arrow-right"></i
></a>
</header>

<aside class="sidebar">
<div class="sidebar-profile">
<i class="fas fa-user-circle"></i>
<span><?php echo htmlspecialchars($username); ?></span>
</div>
<nav class="sidebar-nav">
<a href="dompet.php" class="nav-dompet active">DOMPET</a>
<a href="#" class="nav-input">INPUT DATA</a>
<a href="input_pemasukan.php" class="nav-pemasukan ml-4" style="font-size: 0.85rem">PEMASUKKAN</a>
<a href="input_pengeluaran.php" class="nav-pengeluaran ml-4" style="font-size: 0.85rem">PENGELUARAN</a>
<a href="listbelanja.php" class="nav-list">LIST BELANJA</a>
<a href="laporan.php" class="nav-laporan">LAPORAN</a>
</nav>
</aside>

<main class="main-content">
  <h2><?php echo htmlspecialchars($current_month); ?></h2>
<div class="dompet-summary">
<div class="dompet-card">
<h3><?php echo formatRupiah($total_income); ?></h3>
<p>Total Pemasukkan</p>
</div>
<div class="dompet-card">
<h3><?php echo formatRupiah($total_expense); ?></h3>
<p>Total Pengeluaran</p>
<p class="net-balance-detail">
                Sisa Saldo: <?php echo formatRupiah($net_balance); ?>
            </p>
</div>
</div>
 <div class="content-box diagram-placeholder">
<h3 class="chart-title text-lg font-semibold mb-4">
Perbandingan Pemasukan vs. Pengeluaran
</h3>

<div class="bar-chart-container">
<div class="chart-bar bar-pemasukan" style="width: <?php echo $income_width_percent; ?>%">
Pemasukkan
<span class="bar-label"><?php echo formatRupiah($total_income); ?></span>
</div>

<div class="chart-bar bar-pengeluaran" style="width: <?php echo $expense_width_percent; ?>%">
Pengeluaran
<span class="bar-label"><?php echo formatRupiah($total_expense); ?></span>
</div>
</div>
</div>
</main>
</div>
    </main>
</div>
</body>
</html>
