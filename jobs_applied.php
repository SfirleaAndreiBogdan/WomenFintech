<?php
session_start();
include_once "conf/database.php"; 
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();

$query = "SELECT jobs.title, jobs.description, jobs.location 
          FROM job_applications
          JOIN jobs ON job_applications.job_id = jobs.id
          WHERE job_applications.user_id = :user_id";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
?>

<label id="dark-change"></label>
<div class="start-box">
    <h2>Your Job Applications:</h2>
    <?php if ($stmt->rowCount() > 0): ?>
        <?php while($application = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="job-box">
                <p><strong>Job Title:</strong> <?php echo htmlspecialchars($application['title']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($application['description']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($application['location']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No job applications found.</p>
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
