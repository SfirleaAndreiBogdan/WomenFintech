<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
include_once "search.php";
include_once "roles.php";

$database = new Database();
$db = $database->getConnection();


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}


if($_SESSION['role'] == 'membru')
{
    header("Location: dashboard_membru.php");
    exit();
}elseif($_SESSION['role'] == 'mentor'){
    header("Location: dashboard_mentor.php");
    exit();
}

checkRole('admin');

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

$members_query = "SELECT * FROM members";
$stmt = $db->prepare($members_query);
$stmt->execute();
$total_members = $stmt->fetchColumn();

$profession_distribution_query = "SELECT profession, COUNT(*) as profession_count FROM members GROUP BY profession";
$stmt = $db->prepare($profession_distribution_query);
$stmt->execute();
$profession_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

$new_members_query = "SELECT COUNT(*) as new_members FROM members";
$stmt = $db->prepare($new_members_query);
$stmt->execute();
$new_members_per_month = $stmt->fetchAll(PDO::FETCH_ASSOC);

$top_companies_query = "SELECT company, COUNT(*) as company_count FROM members GROUP BY company ORDER BY company_count";
$stmt = $db->prepare($top_companies_query);
$stmt->execute();
$top_companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<label id="dark-change"></label>

<div class="container">
    <h2>Dashboard Admin</h2>

    <div class="stat-box">
        <h3>Members</h3>
        <p><?php echo $total_members; ?> membri</p>
    </div>

    <div class="stat-box">
        <h3>Profession</h3>
        <ul>
            <?php foreach ($profession_distribution as $row): ?>
                <li><?php echo htmlspecialchars($row['profession']) . ': ' . $row['profession_count']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="stat-box">
        <h3>Membri Noi pe Lună</h3>
        <ul>
            <?php foreach ($new_members_per_month as $row): ?>
                <li><?php echo $row['new_members']; ?> membri noi</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="stat-box">
        <h3>Top 5 Companii Reprezentate</h3>
        <ul>
            <?php foreach ($top_companies as $row): ?>
                <li><?php echo htmlspecialchars($row['company']) . ': ' . $row['company_count']; ?> membri</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <a class="btn" href="notification.php">Send Notification</a>
    <a class="btn" href="read_notifications.php">Read notifications</a>
 
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
<?php include_once "includes/footer.php"; ?>