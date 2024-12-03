<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['feedback']) && !empty($_POST['feedback'])) {
        $feedback = $_POST['feedback'];
        $user_id = $_SESSION['user_id'];
        $mentor_id = $_GET['mentor_id'];


        $query = "INSERT INTO mentor_feedback (mentor_id, mentee_id, feedback_text, date) VALUES (:mentor_id, :user_id, :feedback, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mentor_id', $mentor_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

    
        if ($stmt->execute()) {
            echo "Feedback sent successfully!";
            header("Location: dashboard.php");
        } else {
            echo "Error: ";
        }
    } else {
        echo "Feedback cannot be empty.";
    }
}
?>

<form method="POST">
    <div class="form-group">
        <label for="feedback">Your Feedback</label>
        <textarea class="form-control" id="feedback" name="feedback" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Feedback</button>
</form>
<?php 
include_once "includes/footer.php";
?>