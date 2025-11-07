<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<?php
$host   = 'localhost';
$db_name  = 'keuangan_db';
$db_user  = 'root';
$db_pass  = '';

$conn = mysqli_connect($host,$db_user,$db_pass,$db_name);
//if(mysqli_connect_errno($conn)){
// echo 'Koneksi Gagal';
//}
//	else{
//		echo 'Koneksi berhasil';
//	}
//	
	if ( $conn->connect_errno ) {
    die('Connect Error: ' . $db->connect_errno);
} else {
		
	}
?>
<body>
</body>
</html>