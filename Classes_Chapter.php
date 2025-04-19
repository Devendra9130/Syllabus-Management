<?php
// Database connection
$con = new mysqli("localhost", "root", "", "sm");
session_start();
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : 1;
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 1;

// Use session user_id or default
$user_id = $_SESSION['user_id'] ?? 1;

// Fetch subject details
$stmt = $con->prepare("SELECT * FROM subjects WHERE ID = ?");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get completed topic IDs
$completed_topics = [];
$stmt = $con->prepare("SELECT DISTINCT topic_id FROM progress1 WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $completed_topics[] = $row['topic_id'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("Componets/Header_Files.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .dropdown-menu { width: 100%; padding: 1rem; }
        .progress-container { margin-top: 15px; }
        .bar { background-color: #4EF40B; color: #000; }
        .radio-button { width: 20px; height: 20px; border-radius: 50%; display: inline-block; }
        .size { height: 50px; }
    </style>
</head>
<body>
<form method="POST">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<div class="container-scroller">
    <?php include("Componets/navbar.php"); ?>
    <div class="container-fluid page-body-wrapper">
        <?php include("Componets/sidebar.php"); ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <h2 align="center">Subject Name : <?php echo htmlspecialchars($subject['name']); ?></h2>
                <br>

                <?php
                $stmt = $con->prepare("SELECT * FROM chapters WHERE subject_id = ?");
                $stmt->bind_param("i", $subject_id);
                $stmt->execute();
                $chapters = $stmt->get_result();
                $stmt->close();

                while ($chapter = $chapters->fetch_assoc()) {
                    $chapter_id = $chapter['id'];

                    // Fetch topic IDs for chapter
                    $stmt = $con->prepare("SELECT id FROM topics WHERE chapter_id = ?");
                    $stmt->bind_param("i", $chapter_id);
                    $stmt->execute();
                    $topic_ids_result = $stmt->get_result();
                    $stmt->close();

                    $chapter_topic_ids = [];
                    while ($row = $topic_ids_result->fetch_assoc()) {
                        $chapter_topic_ids[] = $row['id'];
                    }

                    $is_completed = !array_diff($chapter_topic_ids, $completed_topics);
                ?>
                <div class="mb-3">
                    <div class="dropdown w-100">
                        <button class="form-control btn-secondary btn-outline-dark btn-lg dropdown-toggle size" type="button"
                            id="dropdownMenu<?php echo $chapter_id; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="radio-button <?php echo $is_completed ? 'bg-success' : 'bg-danger'; ?>" id="chapterStatus<?php echo $chapter_id; ?>"></span>
                            <b>&nbsp;&nbsp;&nbsp;&nbsp;Chapter: <?php echo htmlspecialchars($chapter['name']); ?></b>
                        </button>
                        <ul class="dropdown-menu w-100 p-3" aria-labelledby="dropdownMenu<?php echo $chapter_id; ?>">
                            <li>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th>Complete</th>
                                                <th>Topic</th>
                                                <th>Teacher Name</th>
                                                <th>Test Completed</th>
                                                <th>Time (mins)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $con->prepare("SELECT * FROM topics WHERE chapter_id = ?");
                                            $stmt->bind_param("i", $chapter_id);
                                            $stmt->execute();
                                            $topics = $stmt->get_result();
                                            $stmt->close();

                                            while ($topic = $topics->fetch_assoc()) {
                                                $topic_id = $topic['id'];
                                                $topic_completed = in_array($topic_id, $completed_topics);
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="completed[]" value="<?php echo $topic_id; ?>" class="topic-checkbox" data-chapter="<?php echo $chapter_id; ?>" <?php echo $topic_completed ? 'disabled' : ''; ?>>
                                                    <input type="hidden" name="topic_ids_all[]" value="<?php echo $topic_id; ?>">
                                                </td>
                                                <td><?php echo htmlspecialchars($topic['name']); ?></td>
                                                
                                                <td>
                                                    <select class="form-control" name="user_id" <?php echo $topic_completed ? 'disabled' : ''; ?>>
                                                        <option value="<?php echo $user_id; ?>"><?php echo htmlspecialchars($row_users['first_name'] . " " . $row_users['last_name']); ?></option>
                                                    </select>
                                                </td>
                                                
                                                <td>
                                                    <input type="checkbox" class="test-checkbox" name="test_completed[<?php echo $topic_id; ?>]" value="1" <?php echo $topic_completed ? 'disabled' : ''; ?>>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control time-input" name="time[<?php echo $topic_id; ?>]" min="0" <?php echo $topic_completed ? 'disabled' : ''; ?>>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (!$is_completed) { ?>
                                <div class="progress-container">
                                    <div class="progress">
                                        <div id="progressBar<?php echo $chapter_id; ?>" class="progress-bar bar" role="progressbar" style="width: 0%">0%</div>
                                    </div>
                                    <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                    <input type="hidden" name="chapter_id" value="<?php echo $chapter_id; ?>">
                                    <input type="hidden" name="status" value="Completed">
                                    <input type="submit" class="btn btn-success btn-sm mt-3" value="Submit Progress" name="submit">
                                </div>
                                <?php } else { ?>
                                    <div class="text-success">Unable to Edit</div>
                                <?php } ?>
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
</form>

<script>
$(document).ready(function () {
    $('.topic-checkbox').change(function () {
        let chapterId = $(this).data("chapter");
        let total = $('.topic-checkbox[data-chapter="' + chapterId + '"]').length;
        let done = $('.topic-checkbox[data-chapter="' + chapterId + '"]:checked').length;
        let percent = (done / total) * 100;
        $('#progressBar' + chapterId).css('width', percent + '%').text(percent.toFixed(0) + '%');

        if (percent === 100) {
            $('#chapterStatus' + chapterId).removeClass('bg-danger').addClass('bg-success');
        } else {
            $('#chapterStatus' + chapterId).removeClass('bg-success').addClass('bg-danger');
        }
    });
});
</script>

<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    $user_id = intval($_POST['user_id']);
    $class_id = intval($_POST['class_id']);
    $subject_id = intval($_POST['subject_id']);
    $chapter_id = intval($_POST['chapter_id']);
    $status = $_POST['status'];

    if (!empty($_POST['completed'])) {
        $completed = $_POST['completed'];
        $topic_ids_all = $_POST['topic_ids_all'];
        $time_inputs = $_POST['time'];
        $test_inputs = $_POST['test_completed'] ?? [];

        foreach ($completed as $completed_topic_id) {
            $completed_topic_id = intval($completed_topic_id);

            $time = isset($time_inputs[$completed_topic_id]) ? intval($time_inputs[$completed_topic_id]) : 0;
            $test_completed = isset($test_inputs[$completed_topic_id]) ? 1 : 0;

            // Only insert if not already done
            $check = $con->prepare("SELECT id FROM progress1 WHERE user_id = ? AND topic_id = ?");
            $check->bind_param("ii", $user_id, $completed_topic_id);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows == 0) {
                $stmt = $con->prepare("INSERT INTO progress1 (user_id, class_id, subject_id, chapter_id, topic_id, status, test_taken, time_taken) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiiisii", $user_id, $class_id, $subject_id, $chapter_id, $completed_topic_id, $status, $test_completed, $time);
                $stmt->execute();
                $stmt->close();
            }
            $check->close();
        }

        echo '<script>
            alert("Progress submitted!");
            window.location.href = "Classes_Chapter.php?subject_id=' . $subject_id . '&class_id=' . $class_id . '";
        </script>';
    } else {
        echo '<script>alert("Please select at least one topic.");</script>';
    }
}
?>
