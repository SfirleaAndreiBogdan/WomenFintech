<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
$database = new database();
$db = $database->getConnection();


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM jobs WHERE 1=1";


if (!empty($_GET['title'])) {
    $query .= " AND title LIKE :title";
}
if (!empty($_GET['location'])) {
    $query .= " AND location LIKE :location";
}

$stmt = $db->prepare($query);

if (!empty($_GET['title'])) {
    $titleFilter = '%' . $_GET['title'] . '%';
    $stmt->bindParam(':title', $titleFilter, PDO::PARAM_STR);
}
if (!empty($_GET['location'])) {
    $locationFilter = '%' . $_GET['location'] . '%';
    $stmt->bindParam(':location', $locationFilter, PDO::PARAM_STR);
}

$stmt->execute();
?>


<label id="dark-change"></label>
<a href="jobs_applied.php" class="btn">See your applications</a>
<a href="for_you.php" class="btn">Recomended for you</a>

<form method="GET" action="">
    <div class="form-group">
        <label for="title">Job Title:</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Enter job title" value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" name="location" id="location" class="form-control" placeholder="Enter location" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<label id="dark-change"></label>
<div class="joburi">
    <?php if ($stmt->rowCount() > 0): ?>
        <?php while($job = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <p>
                <strong><?php echo htmlspecialchars($job['title']); ?></strong>
                <br>
                <span><?php echo htmlspecialchars($job['description']); ?></span>
                <br>
                <small><?php echo htmlspecialchars($job['location']); ?></small>
                <a href="form_aplicare.php?job_id=<?php echo $job['id']?>" class="btn">Apply here</a>
            </p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No jobs found matching your criteria.</p>
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
