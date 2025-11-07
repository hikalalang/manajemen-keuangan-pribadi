<!DOCTYPE html>
<html lang="en">

<head>

 <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">


  <title>Login Manajemen Keuangan Hikari</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image" ><img src="bapak2.jpg" height="655px" width="480px"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h3 class="h4 text-gray-900 mb-4">Form Lupa Password!</h3>
                  </div>
                  <form class="user"  method="post" onSubmit="return validasi()" >
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="nama" aria-describedby="nama" placeholder="Enter Namamu!">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="username" placeholder="Username">
					  </div>
					  <div class="form-group">
                    <label for="message">Your Comments</label>
                    <textarea id="comment" cols="30" rows="10" name="comment" class="form-control" required></textarea>
                  </div>
                    <input type="submit" value="Laporkan Admin" name="lapor_admin"  class="btn btn-primary btn-user btn-block"><br>
					</form>
					
<?php
					
include 'config.php';
  if (isset($_POST['lapor_admin'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
	$comment = $_POST['comment'];


    $insertComment = mysqli_query($conn, "INSERT INTO laporadmin (nama,username,comment,status) VALUES ('$nama','$username','$comment',0)");

    if ($insertComment) {
      ?>
      <script type="text/javascript">
        alert("Admin siap bekerja! ");
		window.location.href='login.php';	
      </script>
		
      <?php
    } else {
      ?>
      <script type="text/javascript">
        alert("Admin lagi males");
		window.location.href='login.php';
      </script>
      <?php
    }
  }
?>
						
<!--
				<script type="text/javascript">
						function validasi() {
							var username = document.getElementById("username").value;
							var password = document.getElementById("password").value;		
							if (username != "" && password!="") {
								return true;
								alert('ok !');
							}else{
								alert('Username dan Password harus di isi !');
								return false;
							}
						}

					</script>  
-->
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
	

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
