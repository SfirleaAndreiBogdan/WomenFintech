<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";


$event_id = $_GET['event_id'];
$database = new database();
$db = $database->getConnection();
?>


<div>
<h2>Lasă Feedback pentru Eveniment</h2>
<form  action="submit_feedback.php" method="POST">
        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">

        <label for="rating">Rating (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" required>
        
        <label for="comments">Comentarii:</label>
        <textarea id="comments" name="comments" required></textarea>
        
        <button type="submit">Trimite Feedback</button>
</form>
</div>

<?php 
include_once "includes/footer.php";
?>