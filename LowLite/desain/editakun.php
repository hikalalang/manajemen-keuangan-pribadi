<!DOCTYPE html>
<html lang="en">

<head>

	
<?php 
  include 'config.php';
  // mengaktifkan session
  session_start();
  
  error_reporting(0);
  
  // cek apakah user telah login, jika belum login maka di alihkan ke halaman login
  if($_SESSION['status'] !="login"){
    header("location:login.php");
  } else {
    
  }

 
 
?>

 

  
  <title>Manajemen Keuangan Hikari</title>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
        </div>
        <div class="sidebar-brand-text mx-3">Manajemen Keuangan Hikari </div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      

      
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Addons
      </div>

      <!-- Nav Item - Header Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHeader" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-list"></i>
          <span>Akun</span>
        </a>
        <div id="collapseHeader" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="buatakun.php">Buat Akun user</a>
            <a class="collapse-item" href="manageakun.php">Manage Akun user</a>
<!--
            <a class="collapse-item" href="update-background.php">Update Background</a>
            <a class="collapse-item" href="update-logo.php">Update Logo</a>  
-->
          </div>
        </div>
      </li>

      

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php
					// menampilkan pesan selamat datang
					echo "Hai, selamat datang ". $_SESSION['username']; ?></span>
                <img class="img-profile rounded-circle" src="img/Capture.jpg">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <div class="container-fluid">

          <!-- Page Heading -->
  <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Edit Akun</h1>

          <!-- Submit Post -->

          <?php
            $partnerId = $_GET['id'];

            if($partnerId == null) {
              ?>
                <script type="text/javascript">
                  window.location.replace("manageakun.php");
                </script>
              <?php
            }

            $selectPartner = mysqli_query($conn, "SELECT * FROM akunadmin WHERE id = '$partnerId'");

            if ($selectPartner) {
              $rowPartner = mysqli_fetch_array($selectPartner);
            }

            if (isset($_POST['submit'])) {
              $nama = $_POST['nama'];
              $username = $_POST['username'];
			  $password = $_POST['password'];
              
              $updateQuery = mysqli_query($conn, "UPDATE akunadmin SET nama = '$nama', username = '$username', password = '$password' WHERE id = '$partnerId'");

              if($updateQuery) {
                ?>

                <script type="text/javascript">
                  window.location.replace("manageakun.php");
                </script>

                <?php
                $msg = "Successfully to update partners";
              } else {
                $msg = "Error update partners";
              }
            }
          ?>

          <?php
            if($msg != null) {
                ?>
                <div class="alert alert-success" role="alert">
                    <strong>Well done!</strong> <?php echo htmlentities($msg);?>
                </div>
                <?php
            }

            if($error != null) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Oh snap!</strong> <?php echo htmlentities($error);?></div>
                </div>
                <?php
            }
          ?>

          <form action="#" method="post" enctype="multipart/form-data">
            <div class="col-lg-7 container-fluid">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo $rowPartner['nama'] ?>" class="form-control" required>
              </div>

              <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $rowPartner['username'] ?>" class="form-control" required>
              </div>

               <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" value="<?php echo $rowPartner['password'] ?>" class="form-control" required>
              </div>

              <div class="form-group">
                <input type="submit" name="submit" value="Update" class="btn btn-primary">
              </div>
            </div>
          </form>
        </div>
  <!-- /.container-fluid -->
             

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Hikare Are</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
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

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
