n<?php
	$con=mysqli_connect("localhost","root","","sm")or die("Server Not Response");
    $user_id=$_GET['user_id'];
    $res=mysqli_query($con,"SELECT * FROM users WHERE USER_ID='".$user_id."'");
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
    <?php include("Componets/Header_Files.php"); ?>
    
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #6610f2);
            font-family: 'Arial', sans-serif;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-top: 50px;
        }

        .btn-primary {
            width: 100%;
            background: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .form-label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .dropdown-menu {
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #007bff;
            color: white;
        }

        .subject-dropdown {
            display: none;
            margin-top: 10px;
            padding-left: 20px;
            margin-bottom: 10px; /* Adds space between classes */
        }

        .subject-dropdown input[type="checkbox"] {
            margin-left: 0px;  /* Remove left margin to align correctly */
            margin-bottom: 10px; /* Add space between each subject */
            display: block;  /* Ensures each checkbox is on a new line */
        }

        .class-option:checked + .subject-dropdown {
            display: block;
        }

        .invalid-feedback {
            color: red;
            font-size: 14px;
        }

        .btn-light {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #ccc;
        }

        .btn-light:hover {
            background-color: #e2e6ea;
        }
    </style>
</head>
<body>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <div class="container-scroller">
        <?php include("Componets/navbar.php"); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include("Componets/sidebar.php"); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="container">
                        <h2 class="text-center mb-4 text-primary"><b>Teacher Registration</b></h2>
                        <form method="POST" >
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First name" value="<?php echo$row[1];?>">
                                <div class="invalid-feedback">Please enter a valid name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last name" value="<?php echo$row[2];?>">
                                <div class="invalid-feedback">Please enter a valid name.</div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo$row[3];?>">
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="<?php echo$row[4];?>">
                                <div class="invalid-feedback">Please enter a valid phone number.</div>
                            </div>
                           


                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" value="<?php echo$row[5];?>">
                                <div class="invalid-feedback">Password is required.</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" name="submit">Register Teacher</button>
                            <br><br>
                        </form>
                    </div>
                </div>
                <?php include("Componets/footer.php"); ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let dropdownBtn = document.getElementById('dropdownBtn');
            let dropdownMenu = document.getElementById('classDropdown');
            
            // Close the dropdown if clicked outside
            document.addEventListener("click", function (event) {
                if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove("show");
                }
            });

            // Toggle the dropdown menu visibility
            dropdownBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Handle class option changes (show subjects)
            document.querySelectorAll('.class-option').forEach(option => {
                option.addEventListener('change', function () {
                    let selectedClasses = [];
                    document.querySelectorAll('.class-option:checked').forEach(checked => {
                        selectedClasses.push(checked.value);
                        // Toggle subject dropdowns based on class selection
                        const classValue = checked.value;
                        const subjectDropdown = document.getElementById('subjects-' + classValue);
                        subjectDropdown.style.display = checked.checked ? 'block' : 'none';
                    });
                    dropdownBtn.innerText = selectedClasses.length > 0 ? selectedClasses.join(', ') : 'Select Classes';
                    document.getElementById('classInput').value = selectedClasses.join(',');
                });
            });
        });
    </script>
</body>
</html>
<?php
    
               