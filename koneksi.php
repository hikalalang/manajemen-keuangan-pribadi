<?php
$host = 'localhost';
$user = 'root'; // Ganti dengan username MySQL Anda
$pass = ''; // Ganti dengan password MySQL Anda
$db = 'keuangan_db';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>