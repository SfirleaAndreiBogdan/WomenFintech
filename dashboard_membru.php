<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
include_once "roles.php";

$database = new Database();
$db = $database->getConnection();

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM members WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
 

$events_mentor = "
    SELECT 
        events.id AS event_id, 
        events.title, 
        events.event_date, 
        events.location, 
        events.event_type 
    FROM 
        event_registrations
    JOIN 
        events ON event_registrations.event_id = events.id
    WHERE 
        event_registrations.member_id = :user_id
";
$stmt = $db->prepare($events_mentor);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
?>

<label id="dark-change"></label>

<div class="container">
    <h2>Dashboard Membru</h2>

    <a href="edit_member.php?id=<?php echo $user['id']; ?>" class="btn">Edit profile</a>

    <div class="stat-box">
        <h3>Your Profile:</h3>
        <p><strong>Foto Profil:</strong> <img id="photo" src="<?php echo "img/" . htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="img-fluid"></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Profession:</strong> <?php echo htmlspecialchars($user['profession']); ?></p>
        <p><strong>Education:</strong> <?php echo htmlspecialchars($user['education']); ?></p>
        <p><strong>Biography:</strong> <?php echo htmlspecialchars($user['biography']); ?></p>
        <p><strong>Company:</strong> <?php echo htmlspecialchars($user['company']); ?></p>
        <p><strong>Expertise:</strong> <?php echo htmlspecialchars($user['expertise']); ?></p>
        <p><strong>LinkedIn:</strong> <a href="<?php echo htmlspecialchars($user['linkedin_profile']); ?>" target="_blank">to LinkedIn profile</a></p>
      </div>

    <div class="stat-box">
        <h3>Events that you are enroled</h3>

        <ul>
                <?php while ($event = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <li>
                        <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                        <p><strong>Data:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p><strong>Locație:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                        <p><strong>Tip Eveniment:</strong> <?php echo htmlspecialchars($event['event_type']); ?></p>
                    </li>
                <?php endwhile; ?>
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
