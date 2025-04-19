<?php
// Database connection
$con = new mysqli("localhost", "root", "", "sm");
session_start();
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get subject ID from URL (Ensure it's an integer)
$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 1;

$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 1;


// Fetch subject details
$stmt = $con->prepare("SELECT * FROM subjects WHERE ID = ?");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If no subject is found, exit gracefully
if (!$subject) {
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("Componets/Header_Files.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
body { background-color: #f8f9fa; }
        .        dropdown-menu { width: 100%; padding: 1rem; }
        .progress-container { margin-top: 15px; }
        .bar { background-color:#4EF40B; color:#000; }
        .radio-button { width: 20px; height: 20px; border-radius: 50%; display: inline-block; }
        .size { height:50px; background-color:}
    </style>
</head>
<body>
    <form action="Demo_Teacher.php?subject_id='<?php echo"'.$subject_id.'"?>&class_id=<?php echo"'.$class_id.'"?>" method="POST">
<div class="container-scroller">
    <?php include("Componets/navbar.php"); ?>
    <div class="container-fluid page-body-wrapper">
        <?php include("Componets/sidebar.php"); ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <h2 align="center">Subject Name : <?php echo htmlspecialchars($subject['name']); ?></h2>
                <br>
                <?php
                // Fetch chapters for this subject
                $stmt = $con->prepare("SELECT * FROM chapters WHERE subject_id = ?");
                $stmt->bind_param("i", $subject_id);
                $stmt->execute();
                $chapters = $stmt->get_result();
                $stmt->close();
                
                while ($chapter = $chapters->fetch_assoc()) {
                ?>
                <div class="mb-3 ">
                    <div class="dropdown w-100">
                        <button class="form-control btn-secondary btn-outline-dark btn-lg dropdown-toggle size btn-outline-dark" type="button" id="dropdownMenu<?php echo $chapter['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="radio-button bg-danger" id="chapterStatus<?php echo $chapter['id']; ?>"></span><b>
                            &nbsp;&nbsp;&nbsp;&nbsp;Chapter: <?php echo htmlspecialchars($chapter['name']); ?></b>
                        </button>
                        <ul class="dropdown-menu w-100 p-3" aria-labelledby="dropdownMenu<?php echo $chapter['id']; ?>">
                            <li>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>Complete</th>
                                                <th>Topic</th>
                                                <th>Teacher</th>
                                                <th>Test Completed</th>
                                                <th>Time (mins)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $con->prepare("SELECT * FROM topics WHERE chapter_id = ?");
                                            $stmt->bind_param("i", $chapter['id']);
                                            $stmt->execute();
                                            $topics = $stmt->get_result();
                                            $stmt->close();
                                            while ($topic = $topics->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><input type="checkbox" class="topic-checkbox" data-chapter="<?php echo $chapter['id']; ?>"></td>
                                                    <td><?php echo htmlspecialchars($topic['name']); ?></td>
                                                    <td>
                                                        
    <select class="form-control" name="user_id">
        
        <option value="<?php echo htmlspecialchars($row_users['user_id']);?>"><?php echo htmlspecialchars($row_users['first_name'] . " " . $row_users['last_name']); ?></option>';
    </select>
</td>

                                                    <td><input type="checkbox" class="test-checkbox"></td>
                                                    <td><input type="number" class="time-input form-control" name="time" min="0"></td>
                                                    
                                                    <p align="center" >
                                                        <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>" readonly>
                                                        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>"  readonly>
                                                         <?php
                                            $stmt = $con->prepare("SELECT * FROM topics WHERE chapter_id = ?");
                                            $stmt->bind_param("i", $chapter['id']);
                                            $stmt->execute();
                                            $topics = $stmt->get_result();
                                            $stmt->close();
                                            while ($topic = $topics->fetch_assoc()) { 
                                                echo'
                                                
<input type="hidden" name="chapter_id" value="'.$chapter['id'].'">
<input type="hidden" name="topic_id" value="'.$topic['id'].'">

                                                ';}?>

                                                    </p></td>
                                                </tr>
                                            <?php } ?>
                                            <input type="hidden" value="Completed" name="status" id="status">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="progress-container">
                                    <div class="progress">
                                        <div id="progressBar<?php echo $chapter['id']; ?>" class="progress-bar bar" role="progressbar" style="width: 0%">0%</div>
                                    </div>
                                    <input type="submit" class="btn btn-success btn-sm mt-3"  value="Submit Progress"  name="submit">
                              
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php include("Componets/footer.php"); ?>
        </div>
    </div>
</div>
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.topic-checkbox').change(function() {
        let chapterId = $(this).data("chapter");
        let totalTopics = $('.topic-checkbox[data-chapter="' + chapterId + '"]').length;
        let completedTopics = $('.topic-checkbox[data-chapter="' + chapterId + '"]:checked').length;
        let progress = (completedTopics / totalTopics) * 100;
        $('#progressBar' + chapterId).css('width', progress + '%').text(progress.toFixed(0) + '%');
        if (progress === 100) {
            $('#chapterStatus' + chapterId).removeClass('bg-danger').addClass('bg-success');
        } else {
            $('#chapterStatus' + chapterId).removeClass('bg-success').addClass('bg-danger');
        }
    });
    
});
</script>
</form>
</body>
</html>
<?php

    
if(isset($_POST['submit'])) {
    // Debugging: Print all form data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Ensure variables are set before using them
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;
    $subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : null;
    $chapter_id = isset($_POST['chapter_id']) ? $_POST['chapter_id'] : null;
    $topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;

    // Check if required values are set
    if (!$user_id || !$chapter_id || !$topic_id) {
        echo '<script>alert("Error: Missing required fields (User ID, Chapter ID, or Topic ID).");</script>';
        exit();
    }

    // Prepare and execute the query properly
    $stmt = $con->prepare("INSERT INTO progress1 (user_id, class_id, subject_id, chapter_id, topic_id, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiis", $user_id, $class_id, $subject_id, $chapter_id, $topic_id, $status);

    if ($stmt->execute()) {
        echo '<script>
            alert("Data inserted successfully!");
            window.location.href="Classes_Chapter.php?subject_id='.$subject_id.'&class_id='.$class_id.'";
        </script>';
    } else {
        echo '<script>
            alert("Error inserting data: '.$stmt->error.'");
        </script>';
    }
    $stmt->close();
}

?>



