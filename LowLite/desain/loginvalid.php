<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<?php 
include 'config.php';
 
$username = $_POST['username'];
$password = ($_POST['password']);
 
$login = mysqli_query($conn, "select * from akunadmin where username='$username' and password='$password'");
$cek = mysqli_num_rows($login);
 	
if($cek > 0){
	  $data = mysqli_fetch_assoc($login);
	if ($data['role']=="admin"){
	session_start();
	$_SESSION['status'] = "login";
	$_SESSION['role'] = "admin";	
	$_SESSION['status'] = "login";
	header("location:index.php");
	} else {
		header("location:indexuser.php");
		
	}
}else{
//	header("location:login.php");
	 echo "<script type='text/javascript'>alert('Salah Password/Username, Anda akan diarahkan ke halaman Login'); window.location.href='login.php';</script>";
}
 
?>
											
<body>
</body>
</html>