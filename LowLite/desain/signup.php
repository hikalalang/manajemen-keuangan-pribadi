<?php
// Wajib: Mulai session di awal skrip
session_start();

// Asumsikan 'koneksi.php' berisi $conn (koneksi MySQLi)
// PASTIKAN FILE KONEKSI.PHP JUGA ADA DAN BENAR
include 'koneksi.php';

// --- FUNGSI SANITIZE (DIBUTUHKAN) ---
// Definisi fungsi untuk membersihkan data input
if (!function_exists('sanitize')) {
    function sanitize($conn, $data) {
        if (!isset($conn) || !$conn) {
            return trim(stripslashes($data));
        }
        $data = trim($data);
        $data = stripslashes($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }
}

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Menggunakan Null Coalescing Operator (?? '') untuk mencegah Notice PHP
    $username = sanitize($conn, $_POST['username'] ?? '');
    $email = sanitize($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi input dasar
    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($password !== $password_confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password harus minimal 6 karakter.";
    } else {
        // Cek apakah username atau email sudah terdaftar
        $check_query = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username atau Email sudah terdaftar.";
        } else {
            // Hash password sebelum disimpan (Wajib untuk keamanan!)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan data pengguna baru
            $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_query)) {
                unset($_POST['password'], $_POST['password_confirm']);
                $message = "Registrasi berhasil! Silakan <a href='login.php' class='text-green-600'>Login</a>.";
            } else {
                $error = "Registrasi gagal: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun</title>
    <style>
        /* Menggunakan font Inter untuk tampilan yang lebih bersih */
        body { font-family: 'Inter', sans-serif; background-color: #3b82f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        
        /* Kontainer Formulir */
        .form-container { 
            background: white; 
            padding: 2.5rem; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); 
            width: 380px; 
            max-width: 90%;
            transition: transform 0.3s ease;
        }
        
        /* Judul */
        h2 { 
            text-align: center; 
            color: #1f2937; 
            margin-bottom: 1.5rem; 
            border-bottom: 2px solid #e5e7eb; 
            padding-bottom: 0.8rem; 
            font-size: 1.5rem;
        }
        
        /* Input dan Label */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-weight: 600; color: #4b5563; font-size: 0.9rem; }
        .form-group input { 
            width: 100%; 
            padding: 0.85rem; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            box-sizing: border-box; 
            transition: border-color 0.3s, box-shadow 0.3s; 
            font-size: 1rem;
        }
        .form-group input:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); 
            outline: none; 
        }
        
        /* Tombol Utama (Daftar) */
        .btn-primary { 
            width: 100%; 
            padding: 0.9rem; 
            background-color: #3b82f6; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: 700; 
            transition: background-color 0.3s, box-shadow 0.3s; 
            margin-top: 1rem;
        }
        .btn-primary:hover { 
            background-color: #2563eb; 
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        /* Link Bawah */
        .link-text { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #6b7280; }
        .link-text a { 
            color: #3b82f6; 
            text-decoration: none; 
            font-weight: 700; 
            transition: color 0.3s;
        }
        .link-text a:hover { color: #2563eb; text-decoration: underline; }
        
        /* Pesan Status */
        .message { padding: 0.75rem; margin-bottom: 1.25rem; border-radius: 8px; text-align: center; font-weight: 600; font-size: 0.95rem; }
        .success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .text-green-600 { color: #059669; font-weight: 700; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>SIGN UP</h2>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit" class="btn-primary">Daftar Sekarang</button>
        </form>
        <p class="link-text">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
