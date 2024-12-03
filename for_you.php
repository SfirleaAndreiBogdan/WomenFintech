<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$query = "SELECT profession FROM members WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();


$user_profession = $stmt->fetchColumn();


$query = "SELECT * FROM jobs WHERE title = :profession";
$stmt = $db->prepare($query);
$stmt->bindParam(':profession', $user_profession, PDO::PARAM_STR);
$stmt->execute();

$recommended_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<label id="dark-change"></label>
    <h2>Recommended Jobs for You</h2>

<div class="joburi">

    <?php if (!empty($recommended_jobs)): ?>
        <?php foreach ($recommended_jobs as $job): ?>
            <div class="job">
                <strong><?php echo htmlspecialchars($job['title']); ?></strong>
                <p><?php echo htmlspecialchars($job['description']); ?></p>
                <small><?php echo htmlspecialchars($job['location']); ?></small>
                <a href="form_aplicare.php?job_id=<?php echo $job['id']; ?>" class="btn">Apply here</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recommendations available at the moment.</p>
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
