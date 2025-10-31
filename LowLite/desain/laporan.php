<?php
// =========================================================
// MOCKING & SETUP (Pengganti config.php dan functions.php)
// Bagian ini harus diganti dengan koneksi database dan fungsi otentikasi nyata 
// setelah Anda memiliki struktur file lengkap.
// =========================================================
session_start();

// MOCK: Fungsi Otentikasi
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = 1; // User ID tiruan
    }
}
requireLogin();

$user_id = $_SESSION['user_id'];
$username = 'AdminFlowlite'; // Username tiruan

// MOCK: Fungsi untuk mengambil data laporan dari 'database'
function getReportData($type, $start_date, $end_date) {
    // Data tiruan yang akan dikembalikan
    $mock_data = [
        [
            'tanggal' => '2025-10-01', 
            'deskripsi' => 'Gaji Bulanan', 
            'kategori' => 'Pekerjaan', 
            'jenis' => 'Pemasukan', 
            'jumlah' => 7500000
        ],
        [
            'tanggal' => '2025-10-03', 
            'deskripsi' => 'Belanja bulanan di supermarket', 
            'kategori' => 'Kebutuhan Primer', 
            'jenis' => 'Pengeluaran', 
            'jumlah' => 1200000
        ],
        [
            'tanggal' => '2025-10-15', 
            'deskripsi' => 'Uang Lembur Proyek', 
            'kategori' => 'Pekerjaan', 
            'jenis' => 'Pemasukan', 
            'jumlah' => 500000
        ],
        [
            'tanggal' => '2025-10-20', 
            'deskripsi' => 'Bayar listrik dan air', 
            'kategori' => 'Tagihan', 
            'jenis' => 'Pengeluaran', 
            'jumlah' => 350000
        ]
    ];
    
    // Logika filter sederhana berdasarkan jenis laporan yang diminta (untuk demonstrasi)
    if ($type === 'income') {
        return array_filter($mock_data, fn($item) => $item['jenis'] === 'Pemasukan');
    } elseif ($type === 'expense') {
        return array_filter($mock_data, fn($item) => $item['jenis'] === 'Pengeluaran');
    }
    
    // Jika 'all' atau 'Pilih Jenis Laporan', kembalikan semua data tiruan
    return $mock_data;
}

// =========================================================
// LOGIKA PEMROSESAN FORMULIR PHP
// =========================================================
$report_data = [];
$total_rows = 0;
$report_title = "Data Laporan Terbaru";
$selected_type = 'all';
$start_date_value = date('Y-m-01');
$end_date_value = date('Y-m-t');
$report_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan bersihkan input dari pengguna
    $selected_type = htmlspecialchars($_POST['jenis-laporan'] ?? 'all');
    $start_date_input = htmlspecialchars($_POST['start-date'] ?? date('Y-m-01'));
    $end_date_input = htmlspecialchars($_POST['end-date'] ?? date('Y-m-t'));

    // Validasi Tanggal (sederhana)
    if (empty($start_date_input) || empty($end_date_input)) {
        $report_error = "Pilih tanggal awal dan akhir yang valid.";
    } elseif (strtotime($start_date_input) > strtotime($end_date_input)) {
        $report_error = "Tanggal awal tidak boleh melebihi tanggal akhir.";
    } else {
        // Data siap diambil
        $report_data = getReportData($selected_type, $start_date_input, $end_date_input);
        $total_rows = count($report_data);
        
        $type_text = match($selected_type) {
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
            default => 'Semua Transaksi',
        };

        $report_title = "Laporan $type_text dari " . date('d M Y', strtotime($start_date_input)) . " sampai " . date('d M Y', strtotime($end_date_input));
        
        $start_date_value = $start_date_input;
        $end_date_value = $end_date_input;
    }
} else {
    // Muat data default saat pertama kali dimuat (misalnya, semua data bulan ini)
    $report_data = getReportData('all', $start_date_value, $end_date_value);
    $total_rows = count($report_data);
}

// =========================================================
// FUNGSI UNTUK MENGHASILKAN TABEL LAPORAN (HTML Dinamis)
// =========================================================
function generateReportTable($data) {
    if (empty($data)) {
        return '<p class="text-center text-gray-500 py-10">Tidak ada data transaksi yang ditemukan untuk kriteria ini.</p>';
    }

    $html = '<div class="overflow-x-auto rounded-lg shadow-md">';
    $html .= '<table class="min-w-full divide-y divide-gray-200">';
    
    // Header Tabel
    $html .= '<thead class="bg-gray-50">';
    $html .= '<tr>';
    $headers = ['Tanggal', 'Deskripsi', 'Kategori', 'Jenis', 'Jumlah (Rp)'];
    foreach ($headers as $header) {
        $html .= '<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">' . $header . '</th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';
    
    // Isi Tabel
    $html .= '<tbody class="bg-white divide-y divide-gray-200">';
    $grand_total = 0;
    foreach ($data as $row) {
        $amount_class = ($row['jenis'] === 'Pemasukan') ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold';
        $grand_total += ($row['jenis'] === 'Pemasukan' ? $row['jumlah'] : -$row['jumlah']);

        $html .= '<tr>';
        $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($row['tanggal']) . '</td>';
        $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($row['deskripsi']) . '</td>';
        $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['kategori']) . '</td>';
        $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . htmlspecialchars($row['jenis']) . '</td>';
        $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm ' . $amount_class . '">' . number_format($row['jumlah'], 2, ',', '.') . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    // Footer Ringkasan
    $total_class = $grand_total >= 0 ? 'text-green-700' : 'text-red-700';
    $html .= '<div class="mt-4 p-4 bg-gray-50 border-t border-gray-200 rounded-b-lg flex justify-end">';
    $html .= '<span class="text-lg font-bold">Saldo Akhir: </span>';
    $html .= '<span class="text-lg font-bold ml-2 ' . $total_class . '">Rp' . number_format($grand_total, 2, ',', '.') . '</span>';
    $html .= '</div>';
    
    return $html;
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FlowLite - Laporan</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Variabel dan Base Styling */
        :root {
            --primary-color: #f44336; /* Merah FlowLite */
            --highlight-active: #ffc107; /* Kuning terang */
            --background-light: #f7f9fc;
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
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        
        /* Form dan Kotak Konten */
        .content-box {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .input-form-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap; /* Untuk responsif */
        }
        .input-form-group label {
            font-weight: 600;
            color: #333;
            min-width: 120px;
        }
        select, .date-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            flex-grow: 1;
            max-width: 250px;
        }
        .btn-proses {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-proses:hover {
            background-color: #d32f2f;
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
            .input-form-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            .input-form-group label, select, .date-input, .btn-proses {
                width: 100%;
                max-width: none;
            }
            .input-form-group span {
                align-self: flex-start;
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
class="fas fa-lightbulb"
Style="color: var(--highlight-active)"></i></div>
<a href="index.html" class="logout-btn">Out <i class="fas fa-arrow-right"></i></a>
</header>

<aside class="sidebar">
<div class="sidebar-profile">
<i class="fas fa-user-circle"></i>
<span><?php echo htmlspecialchars($username); ?></span>
</div>
<nav class="sidebar-nav">
<a href="dompet.php" class="nav-dompet">DOMPET</a>
<a href="#" class="nav-input">INPUT DATA</a>
<a href="input_pemasukan.php" class="nav-pemasukan ml-4" style="font-size: 0.85rem">PEMASUKKAN</a>
<a href="input_pengeluaran.php" class="nav-pengeluaran ml-4" style="font-size: 0.85rem">PENGELUARAN</a>
<a href="listbelanja.php" class="nav-list">LIST BELANJA</a>
<a href="laporan.php" class="nav-laporan active">LAPORAN</a>
</nav>
</aside>

<main class="main-content">
<h2>Laporan Keuangan</h2>
<div class="content-box">
            <!-- Formulir menggunakan metode POST untuk memicu PHP processing -->
            <form method="POST" action="laporan.php">
                <div class="input-form-group">
<label for="jenis-laporan">Jenis Laporan :</label>
<select id="jenis-laporan" name="jenis-laporan">
<option value="all" <?php echo $selected_type === 'all' ? 'selected' : ''; ?>>Semua Transaksi</option>
                        <option value="income" <?php echo $selected_type === 'income' ? 'selected' : ''; ?>>Pemasukan Saja</option>
                        <option value="expense" <?php echo $selected_type === 'expense' ? 'selected' : ''; ?>>Pengeluaran Saja</option>
</div>

<div class="input-form-group">
<label>Pilih Tanggal :</label>
<input type="date" class="date-input" name="start-date" value="<?php echo $start_date_value; ?>" />
<span>Sampai</span>
      <input type="date" class="date-input" name="end-date" value="<?php echo $end_date_value; ?>" />
     <button type="submit" class="btn-proses">Proses</button>
 </div>
            </form>
            
            <?php if ($report_error): ?>
                <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded mb-4">
                    <?php echo $report_error; ?>
                </div>
            <?php endif; ?>

            <h3 class="text-xl font-semibold mb-3 pt-4 border-t mt-4"><?php echo $report_title; ?></h3>
            <p class="text-sm text-gray-600 mb-4">Ditemukan: <?php echo $total_rows; ?> Transaksi</p>

<div class="table-placeholder">
                <!-- Data tabel dihasilkan oleh PHP -->
                <?php echo generateReportTable($report_data); ?>
            </div>
 </div>
</main>
</div>
 </body>
</html>
