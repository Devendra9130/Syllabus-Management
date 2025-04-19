<?php
	$con=mysqli_connect("localhost","root","","sm")or die("Server Not Response");
	$class_id=$_GET['class_id'];
	$res=mysqli_query($con,"SELECT * FROM subjects WHERE ID='".$class_id."'");
	$row=mysqli_fetch_row($res);
  session_start();
    if(isset($_SESSION['users']))
    {
    }
    else
    {
       echo'
            <script>
                window.location.href"Login.php";
            </script>
            ';
    }
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("Componets/Header_Files.php") ?>
    <style>
        .font{
            font-size:25px !important;
            color:#000000;
        }
        .font:hover
        {

          background-color: #252c46 !important;
          color:#fff;
        }
        
    </style>
  </head>
  <body class="bg-secondary text-center">
    <div class="container-scroller">
      <?php include("Componets/navbar.php") ?>
      <div class="container-fluid page-body-wrapper">
        <?php include("Componets/sidebar.php") ?>
        <div class="main-panel">
          <div class="content-wrapper">
            <h1 class="text-dark mt-4">Choose Your Subject</h1>
            <div class="row justify-content-center mt-4 text-dark">
              <?php
                $res=mysqli_query($con,"SELECT * FROM subjects WHERE CLASS_ID='".$row[2]."'");
                while($row=mysqli_fetch_row($res)) {
                  echo '
                  <div class="col-md-4 col-lg-5 mb-5">
                    <a href="Classes_Chapter.php?subject_id='.$row[0].'&class_id='.$row[2].'" class="btn btn-outline-dark w-100 py-5 font "><b>'.$row[1].'</b></a>
                  </div> ';
                }
              ?>
            </div>
            <a href="Classes_Main.php" class="btn btn-warning mt-3">Go to Class Page</a>
          </div>
          <?php include("Componets/footer.php") ?>
        </div>
      </div>
    </div>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/js/vendor.bundle.addons.js"></script>
    <script src="assets/js/shared/off-canvas.js"></script>
    <script src="assets/js/demo_1/dashboard.js"></script>
    <script src="assets/js/shared/jquery.cookie.js" type="text/javascript"></script>
  </body>
</html>
