<?php
	$con=mysqli_connect("localhost","root","","sm")or die("Server Not Response");
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
    <!-- Required meta tags -->
    <?php
      include("Componets/Header_Files.php")
    ?>
    <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f0f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      
      color: #333;
      text-align: center;
    }
    .card:hover
    {
      background-color: #252c46 !important;
      color:#fff !important;
      font-size:25px !important;
      justify-content: center;
      
      
    }
    .card:hover .img{
      opacity: 0 !important;
      transition: opacity 0.2s ease !important;

    }
    



    

    

    
  </style>
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <?php
        include("Componets/navbar.php")
      ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <?php
            include("Componets/sidebar.php")
        ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <!-- Page Title Header Starts-->
            <h1 class="mb-12"><b?>Select Your Class</b></h1>
            <br>
            
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                        <!-- Class Cards -->
                        <?php
                        $res = mysqli_query($con, "SELECT * FROM classes");
                        while ($row = mysqli_fetch_row($res)) {
                            echo '
                            <div class="col mb-5 ">
                                <a href="classes_Subject.php?class_id='.$row[0].'" class="text-decoration-none">
                                    <div class="bg-outline-dark bg-secodary text-dark shadow-sm p-3 rounded d-flex flex-column align-items-center justify-content-center card">
                                        <img src="assets\images\presentation.png" class="img-fluid mb-2 img" style="width: 40px;">
                                        <span class="fw-bold"><b>'.$row[1].'</b></span>
                                    </div>
                                </a>
                            </div>';
                        }
                        ?>
                    </div>
            <!-- Page Title Header Ends-->
            
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <?php
              include("Componets/footer.php")
          ?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="assets/js/shared/off-canvas.js"></script>
   
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="assets/js/demo_1/dashboard.js"></script>
    <!-- End custom js for this page-->
    <script src="assets/js/shared/jquery.cookie.js" type="text/javascript"></script>
  </body>
</html>