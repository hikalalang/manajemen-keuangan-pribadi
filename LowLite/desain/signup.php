<?php
include 'koneksi.php';

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($conn, $_POST['username']);
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

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
        /* Gaya dasar dan pemusatan */
        body { font-family: Arial, sans-serif; background-color: #4CAF50; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        
        /* Kontainer Formulir */
        .form-container { 
            background: white; 
            padding: 2rem; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Shadow lebih kuat */
            width: 350px; 
        }
        
        /* Judul */
        h2 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 1.5rem; 
            border-bottom: 2px solid #ddd; 
            padding-bottom: 0.5rem; 
        }
        
        /* Input dan Label */
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #555; }
        .form-group input { 
            width: 100%; 
            padding: 0.75rem; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            box-sizing: border-box; 
            transition: border-color 0.3s; 
        }
        .form-group input:focus { border-color: #4CAF50; outline: none; }
        
        /* Tombol Utama (Daftar) */
        .btn-primary { 
            width: 100%; 
            padding: 0.75rem; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: bold; 
            transition: background-color 0.3s; 
            margin-top: 0.5rem;
        }
        .btn-primary:hover { background-color: #45a049; }
        
        /* Link Bawah */
        .link-text { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; }
        .link-text a { color: #007bff; text-decoration: none; font-weight: bold; }
        
        /* Pesan Status */
        .message { padding: 0.75rem; margin-bottom: 1rem; border-radius: 5px; text-align: center; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Daftar Akun Baru</h2>
        
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
