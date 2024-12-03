<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

$mentor_id = $_GET['mentor_id'];
$query = "SELECT * FROM members WHERE id = :mentor_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':mentor_id', $mentor_id, PDO::PARAM_INT);
$stmt->execute();
$mentor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $session_date = $_POST['session_date'];
    $session_time = $_POST['session_time'];
    $user_id = $_SESSION['user_id'];
    $topic = $_POST['topic'];
    
    
    $query = "INSERT INTO session_bookings (mentor_id, mentee_id, session_date, session_time, topic) 
              VALUES (:mentor_id, :mentee_id, :session_date, :session_time, :topic)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':mentor_id', $mentor_id, PDO::PARAM_INT);
    $stmt->bindParam(':mentee_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':session_date', $session_date, PDO::PARAM_STR);
    $stmt->bindParam(':session_time', $session_time, PDO::PARAM_STR);
    $stmt->bindParam(':topic', $topic, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        
        $session_id = $db->lastInsertId();

        
        $insert_query = "INSERT INTO progress_tracking (session_id, mentor_id,mentee_id, objective, task, progress, session_date) 
                         VALUES (:session_id, :mentor_id, :mentee_id, :objective, :task, :progress, :session_date)";
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':mentor_id', $mentor_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':mentee_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindValue(':progress', 'In progress', PDO::PARAM_STR);
        $insert_stmt->bindValue(':objective', 'Should be set by mentor', PDO::PARAM_STR);
        $insert_stmt->bindValue(':task', 'Should be set by mentor', PDO::PARAM_STR);
        $insert_stmt->bindValue(':session_date', $session_date, PDO::PARAM_STR);
        
        if ($insert_stmt->execute()) {
            $_SESSION['success_message'] = "Your session has been successfully scheduled!";
            header("Location: conections.php");
            exit();
        } else {
            echo "Error: Could not add progress tracking.";
        }
    } else {
        echo "Error: Could not schedule session.";
    }
}

?>

<div class="container">
    <h2>Schedule a Session with <?php echo htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']); ?></h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="session_date">Date</label>
            <input type="date" class="form-control" id="session_date" name="session_date" required>
        </div>
        <div class="form-group">
            <label for="session_time">Time</label>
            <input type="time" class="form-control" id="session_time" name="session_time" required>
        </div>
        <div class="form-group">
            <label for="topic">Topic</label>
            <input type="text" class="form-control" id="topic" name="topic" required>
        </div>
        <button type="submit" class="btn btn-primary">Schedule</button>
    </form>
</div>

<?php include_once "includes/footer.php"; ?>
