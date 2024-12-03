<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

if($_SESSION['role'] !== 'mentor'){
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    $query = "SELECT * FROM progress_tracking WHERE session_id = :session_id AND mentor_id = :mentor_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
    $stmt->bindParam(':mentor_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$session) {
        echo "Session not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $objective = $_POST['objective'];
        $task = $_POST['task'];
        $progress = $_POST['progress'];
        $feedback = $_POST['feedback'];

        if (empty($objective) || empty($task) || empty($progress) || empty($feedback)) {
            echo "All fields are required!";
            exit();
        }

        $update_query = "UPDATE progress_tracking SET objective = :objective, task = :task, progress = :progress, feedback = :feedback WHERE session_id = :session_id AND mentor_id = :mentor_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $update_stmt->bindParam(':mentor_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $update_stmt->bindParam(':objective', $objective, PDO::PARAM_STR);
        $update_stmt->bindParam(':task', $task, PDO::PARAM_STR);
        $update_stmt->bindParam(':progress', $progress, PDO::PARAM_STR);
        $update_stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Progress updated successfully!";
            header("Location: sessions.php");
            exit();
        } else {
            echo "Error updating progress!";
        }
    }
} else {
    echo "Session ID is missing.";
    exit();
}

?>

<label id="dark-change"></label>
<div class="container">
    <h2>Update Progress for Session: <?php echo htmlspecialchars($session['session_date']); ?></h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="objective">Objective</label>
            <input type="text" class="form-control" id="objective" name="objective" value="<?php echo htmlspecialchars($session['objective']); ?>" required>
        </div>
        <div class="form-group">
            <label for="task">Task</label>
            <input type="text" class="form-control" id="task" name="task" value="<?php echo htmlspecialchars($session['task']); ?>" required>
        </div>
        <div class="form-group">
            <label for="progress">Progress</label>
            <textarea class="form-control" id="progress" name="progress" rows="4"><?php echo htmlspecialchars($session['progress']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="feedback">Feedback</label>
            <textarea class="form-control" id="feedback" name="feedback" rows="4"><?php echo htmlspecialchars($session['feedback']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Progress</button>
    </form>
</div>


<script>
    var content = document.getElementsByTagName('body')[0];
    var dark = document.getElementById('dark-change');

    if (localStorage.getItem('theme') === 'night') {
        content.classList.add('night');
    }

    dark.addEventListener('click', function () {
        dark.classList.toggle('active');
        content.classList.toggle('night');

        if (content.classList.contains('night')) {
            localStorage.setItem('theme', 'night');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });
</script>
<?php 
include_once "includes/footer.php";
?>
