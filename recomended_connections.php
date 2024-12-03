<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
include_once "roles.php";

$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

$user_id = $_SESSION['user_id'];

$query_user = "SELECT * FROM members WHERE id = :user_id";
$stmt_user = $db->prepare($query_user);
$stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_user->execute();
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $query = "
        SELECT * FROM members
        WHERE (profession LIKE :profession OR skills LIKE :skills)
        AND roles = 'mentor' AND id != :user_id
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':profession', '%' . $user['profession'] . '%');
    $stmt->bindValue(':skills', '%' . $user['skills'] . '%');
    $stmt->bindParam(':user_id',$_SESSION['user_id']);
    $stmt->execute();

}
?>

<label id="dark-change"></label>
<div class="container">

<?php if ($stmt->rowCount() > 0): ?>
    <h2>Mentors recommended for you:</h2>
    <div class="row">
        <?php 
        while ($mentor = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-4">
                <div class="card member-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($mentor['first_name'] . ' ' . $mentor['last_name']); ?></h5>
                        <img id="photo" src="<?php echo "img/" . htmlspecialchars($mentor['photo']); ?>" alt="Profile Photo" class="img-fluid">
                        <p class="card-text">
                            <strong>Profession:</strong> <?php echo htmlspecialchars($mentor['profession']); ?><br>
                            <strong>Company:</strong> <?php echo htmlspecialchars($mentor['company']); ?><br>

                            <strong>Biography:</strong> <?php echo htmlspecialchars($mentor['biography']); ?><br>
                
                            <strong>Skills:</strong> <?php echo htmlspecialchars($mentor['skills']); ?><br>
                    
                            <strong>Education:</strong> <?php echo htmlspecialchars($mentor['education']); ?><br>
                        
                            <strong>Portfolio:</strong> <a href="<?php echo htmlspecialchars($mentor['portfolio_links']); ?>" target="_blank">View Portfolio</a><br>
                            

                            <strong>Social Links:</strong> <a href="<?php echo htmlspecialchars($mentor['social_links']); ?>" target="_blank">View Social Links</a><br>
                            

                        </p>
                        <?php if ($_SESSION['role'] !== 'mentor'): ?>
                            <a href="schedule_session.php?mentor_id=<?php echo $mentor['id']; ?>" class="btn btn-primary">Schedule a Session</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No mentors found who match your criteria.</p>
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
