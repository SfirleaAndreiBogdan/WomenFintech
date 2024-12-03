<?php
session_start();
include_once "conf/database.php"; 
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM progress_tracking WHERE mentee_id = :user_id AND session_id = :session_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindParam(':session_id', $_GET['session_id'], PDO::PARAM_INT);
$stmt->execute();
?>

<label id="dark-change"></label>
<div class="start-box">
    <h2>Your Progress:</h2>
    <?php if ($stmt->rowCount() > 0): ?>
        <?php while($track = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <p><strong>Objective:</strong> <?php echo htmlspecialchars($track['objective']); ?></p>
            <p><strong>Task:</strong> <?php echo htmlspecialchars($track['task']); ?></p>
            <p><strong>Progress:</strong> <?php echo htmlspecialchars($track['progress']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($track['session_date']); ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No progress data available.</p>
    <?php endif; ?>
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
