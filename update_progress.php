<?php
session_start();
include_once "conf/database.php";

$database = new Database();
$db = $database->getConnection();

$session_id = $_POST['session_id'];
$objective = $_POST['objective'];
$task = $_POST['task'];
$progress = $_POST['progress'];
$feedback = $_POST['feedback'];


$query = "UPDATE progress_tracking 
          SET objective = :objective, task = :task, progress = :progress, feedback = :feedback, session_date = NOW() 
          WHERE session_id = :session_id AND mentee_id = :user_id";

$stmt = $db->prepare($query);
$stmt->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindParam(':objective', $objective, PDO::PARAM_STR);
$stmt->bindParam(':task', $task, PDO::PARAM_STR);
$stmt->bindParam(':progress', $progress, PDO::PARAM_STR);
$stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Progress updated successfully!";
    header("Location: sessions.php");
    exit();
} else {
    echo "Error updating progress.";
}
?>
