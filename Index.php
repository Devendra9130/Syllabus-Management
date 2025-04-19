<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "sm") or die("Sorry..! Unable to connect to the server");

// Check if the user is logged in
if (!isset($_SESSION['users'])) {
    header("Location: Login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['users'];

// Run the query to fetch user details
$res_users = mysqli_query($con, "SELECT * FROM users WHERE  USER_ID='$user_id'");

// Check if query was successful
if (!$res_users) {
    die("Error in query: " . mysqli_error($con)); // Debugging: Show MySQL error
}

// Fetch user data
$row_users = mysqli_fetch_assoc($res_users);

// Check if user exists
if (!$row_users) {
    die("User not found in database!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <?php include("Componets/Header_Files.php"); ?>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #E8E8E8;
        }
        .welcome-card {
            background: linear-gradient(150deg, #E8E8E8,#E8E8E8,#C5C2C2, #C5C2C2);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #000;
        }
        .dashboard-cards .card {
            transition: transform 0.3s ease-in-out;
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .dashboard-cards .card:hover {
            transform: scale(1.05);
        }
        .icon {
            font-size: 40px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <?php include("Componets/navbar.php"); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include("Componets/sidebar.php"); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- Welcome Card -->
                    <div class="welcome-card">
                        <h2><b>Welcome, <span id="teacher-name">
                            <?php echo htmlspecialchars($row_users['first_name'] . " " . $row_users['last_name']); ?>
                        </span> 🎉</b></h2>
                        <p>Here is your dashboard overview.</p>
                    </div>

                    <!-- Dashboard Cards -->
                    <div class="row mt-4 dashboard-cards">
                        <div class="col-md-6">
                            <div class="card">
                                <i class="fas fa-users icon"></i>
                                <h4 class="mt-3"><b>Total Students</b></h4>
                                <p><b>250</b></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <i class="fas fa-calendar-check icon"></i>
                                <h4 class="mt-3"><b>Today's Schedule</b></h4>
                                <p><b>3 Classes</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include("Componets/footer.php"); ?>
            </div>
        </div>
    </div>

    <!-- Plugins: JS -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/js/vendor.bundle.addons.js"></script>
    <script src="assets/js/shared/off-canvas.js"></script>
    <script src="assets/js/demo_1/dashboard.js"></script>
    <script src="assets/js/shared/jquery.cookie.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
