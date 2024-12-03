<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();


    $member_id = $_SESSION['user_id'];
    $query = "SELECT * FROM notifications WHERE member_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();



?>
<label id="dark-change"></label>

<h2>Notifications:</h2>
<div>
    <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <p><?php echo htmlspecialchars($row['message']); ?></p>
    <?php endwhile; ?>
</div >

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
