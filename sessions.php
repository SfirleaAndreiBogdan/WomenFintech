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
$role = $_SESSION['role'];

if ($role === 'mentor') {
    $query = "SELECT 
                session_bookings.id AS session_id,
                session_bookings.session_date,
                session_bookings.topic,
                members.first_name AS mentee_first_name,
                members.last_name AS mentee_last_name
              FROM 
                session_bookings
              JOIN 
                members ON session_bookings.mentee_id = members.id
              WHERE 
                session_bookings.mentor_id = :user_id
              ORDER BY 
                session_bookings.session_date DESC";
} else {
    $query = "SELECT 
                session_bookings.id AS session_id,
                session_bookings.session_date,
                session_bookings.mentor_id,
                session_bookings.topic,
                members.first_name AS mentor_first_name,
                members.last_name AS mentor_last_name
              FROM 
                session_bookings
              JOIN 
                members ON session_bookings.mentor_id = members.id
              WHERE 
                session_bookings.mentee_id = :user_id
              ORDER BY 
                session_bookings.session_date DESC";
}

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
?>

<label id="dark-change"></label>
<div class="container">
    <?php if ($role === 'mentor'): ?>
    <h2>Your Scheduled Sessions with Members</h2>
    <?php else: ?>
    <h2>Your Scheduled Sessions with Mentors</h2>
    <?php endif; ?>
    <?php if ($stmt->rowCount() > 0): ?>
        
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <h5 class="mb-1">
                        <?php if ($role === 'mentor'): ?>
                            Session with <?php echo htmlspecialchars($row['mentee_first_name'] . ' ' . $row['mentee_last_name']); ?>
                        <?php else: ?>
                            Session with <?php echo htmlspecialchars($row['mentor_first_name'] . ' ' . $row['mentor_last_name']); ?>
                        <?php endif; ?>
                    </h5>
                    <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($row['session_date']); ?></p>
                    <p class="mb-1"><strong>Topic:</strong> <?php echo htmlspecialchars($row['topic']); ?></p>
                    <?php if ($role === 'membru'): ?>
                    <a href="show_progress.php?session_id=<?php echo $row['session_id'];?>" class="btn">See your progress</a>
                    <a href="mentor_feedback.php?mentor_id=<?php echo $row['mentor_id'];?>" class="btn">Send a feedback</a>
                    <?php elseif ($role === 'mentor'): ?>
                    <a href="tracking_progress.php?session_id=<?php echo $row['session_id']; ?>" class="btn">Update progress</a>  
                    <?php endif; ?>
            <?php endwhile; ?>
        
    <?php else: ?>
        <p>No sessions scheduled yet.</p>
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
<?php include_once "includes/footer.php"; ?>
