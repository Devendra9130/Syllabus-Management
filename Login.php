<?php
    $con = mysqli_connect("localhost", "root", "", "sm") or die("Server Not Responding");
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Login Page</title>

    <!-- Plugins: CSS -->
    <link rel="stylesheet" href="assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/iconfonts/ionicons/dist/css/ionicons.css">
    <link rel="stylesheet" href="assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.addons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/shared/style.css">
    
    <link rel="shortcut icon" href="assets/images/favicon.ico">
</head>
<body>
    <form action="Login.php" method="POST">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
                <div class="row w-100">
                    <div class="col-lg-4 mx-auto">
                        <div class="auto-form-wrapper">
                            <form action="Login1.php" method="POST">
                                <div class="form-group">
                                    <label class="label">Username</label>
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control" placeholder="email" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-check-circle-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="Password" class="form-control" placeholder="Password" id="passwordField" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                                                <i class="mdi mdi-eye" id="toggleEye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary submit-btn btn-block"  name="login">
                                </div>
                                <div class="form-group d-flex justify-content-between">
                                    <div class="form-check form-check-flat mt-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" > Keep me signed in
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- Plugins: JS -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/js/vendor.bundle.addons.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/shared/off-canvas.js"></script>
    <script src="assets/js/shared/misc.js"></script>
    <script src="assets/js/shared/jquery.cookie.js"></script>

    <script>
        function togglePassword() {
            let passwordField = document.getElementById("passwordField");
            let toggleEye = document.getElementById("toggleEye");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleEye.classList.remove("mdi-eye");
                toggleEye.classList.add("mdi-eye-off");
            } else {
                passwordField.type = "password";
                toggleEye.classList.remove("mdi-eye-off");
                toggleEye.classList.add("mdi-eye");
            }
        }
    </script>
</form>
</body>
</html>
<?php 
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['Password']);

    // Check in Admins Table
    $query_admin = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $res = mysqli_query($con, $query_admin);

    // Check in Users Table (Teachers)
    $query_user = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $res1 = mysqli_query($con, $query_user);

    if (mysqli_num_rows($res) > 0) {
        $admin_row = mysqli_fetch_assoc($res);
        $_SESSION['admins'] = $admin_row['id'];
        echo '<script> window.location.href="Admin/Index.php";</script>';
    } elseif (mysqli_num_rows($res1) > 0) {
        $user_row = mysqli_fetch_assoc($res1);
        $_SESSION['users'] = $user_row['user_id'];
        echo '<script>window.location.href="Index.php";</script>';
    } else {
        echo '<script>alert("Wrong Email OR Password"); window.location.href="Login.php";</script>';
    }
}

?>
