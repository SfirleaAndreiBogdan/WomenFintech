<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

$database = new database();
$db = $database->getConnection();


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}


$currentDate = date('d-m-Y');

$query = "
    SELECT 
        events.id,
        events.event_date, 
        events.title, 
        events.description, 
        events.location, 
        events.event_type, 
        events.max_participants, 
        members.first_name AS authors_name
    FROM events
    JOIN members ON events.created_by = members.id
    ORDER BY events.event_date DESC
";
$stmt = $db->prepare($query);
$stmt->execute();

if (isset($_SESSION['success_message'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['success_message']) . '</p>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
    unset($_SESSION['error_message']);
}

?>
<label id="dark-change"></label>
<div class="event">
<?php while ($events = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <h2>Eveniment pe data de: <?php echo htmlspecialchars($events['event_date']); ?></h2>
    <h3><?php echo htmlspecialchars($events['title']); ?></h3>
    <p><?php echo htmlspecialchars($events['description']); ?></p>
    <p><strong>Locație:</strong> <?= htmlspecialchars($events['location']); ?></p>
    <p><strong>Tip Eveniment:</strong> <?= htmlspecialchars($events['event_type']); ?></p>
    <p><strong>Maxim Participanți:</strong> <?= htmlspecialchars($events['max_participants']); ?></p>
    <p><strong>Creat de:</strong> <?= htmlspecialchars($events['authors_name']); ?></p>
    <a href="inscriere.php?events_id=<?php echo $events['id']; ?>" class="btn">Inscrie-te</a>
    <a href="feedback_form.php?event_id=<?php echo $events['id']; ?>" class="btn">Give us a feedback</a>
<?php endwhile; ?>
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
