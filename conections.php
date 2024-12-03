<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
include_once "roles.php";

$database = new Database();
$db = $database->getConnection();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

$user_id = $_SESSION['user_id'];

if($_SESSION['role'] === 'membru'){
$mentor_connections = "
    SELECT 
        members.id AS mentor_id,
        members.first_name AS mentor_first_name,
        members.last_name AS mentor_last_name,
        members.photo AS mentor_photo,
        members.profession AS mentor_profession,
        members.company AS mentor_company,
        members.linkedin_profile AS mentor_linkedin,
        members.biography AS biography,
        members.skills AS skills,
        members.education AS education,
        members.portfolio_links AS portfolio_links,
        members.social_links AS social_links
    FROM 
        mentor_mentee
    JOIN 
        members ON mentor_mentee.mentor_id = members.id
    WHERE 
        mentor_mentee.mentee_id = :user_id
";
$stmt = $db->prepare($mentor_connections);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
}elseif($_SESSION['role'] === 'mentor'){
    $member_connections = "
    SELECT 
        members.id AS mentor_id,
        members.first_name AS mentor_first_name,
        members.last_name AS mentor_last_name,
        members.photo AS mentor_photo,
        members.profession AS mentor_profession,
        members.company AS mentor_company,
        members.linkedin_profile AS mentor_linkedin,
        members.biography AS biography,
        members.skills AS skills,
        members.education AS education,
        members.portfolio_links AS portfolio_links,
        members.social_links AS social_links
    FROM 
        mentor_mentee
    JOIN 
        members ON mentor_mentee.mentee_id = members.id
    WHERE 
        mentor_mentee.mentor_id = :user_id
";
$stmt = $db->prepare($member_connections);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();   
}elseif($_SESSION['role'] === 'admin'){
    echo "<p>You are not connected to any members yet.</p>";
    exit();
}
?>

<label id="dark-change"></label>

<div class="container">

    <?php if ($stmt->rowCount() > 0): ?>
        <?php if($_SESSION['role'] === 'mentor'): ?>
        <h2>Members you are Connected with:</h2>
        <?php else: ?>
        <h2>Mentors you are Connected with:</h2>
        <?php endif; ?>
        <div class="row">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-4">
                    <div class="card member-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['mentor_first_name'] . ' ' . $row['mentor_last_name']); ?></h5>
                            <img id="photo" src="<?php echo "img/" . htmlspecialchars($row['mentor_photo']); ?>" alt="Profile Photo" class="img-fluid">
                            <p class="card-text">
                                <strong>Profession:</strong> <?php echo htmlspecialchars($row['mentor_profession']); ?><br>
                                <strong>Company:</strong> <?php echo htmlspecialchars($row['mentor_company']); ?><br>

                                <strong>Biography:</strong> <?php echo htmlspecialchars($row['biography']); ?><br>
                    
                                <strong>Skills:</strong> <?php echo htmlspecialchars($row['skills']); ?><br>
                        
                                <strong>Education:</strong> <?php echo htmlspecialchars($row['education']); ?><br>
                            
                                <strong>Portfolio:</strong> <a href="<?php echo htmlspecialchars($row['portfolio_links']); ?>" target="_blank">View Portfolio</a><br>
                                
                                <strong>Social Links:</strong> <a href="<?php echo htmlspecialchars($row['social_links']); ?>" target="_blank">View Social Links</a><br>
                                
                            </p>
                            <?php if($_SESSION['role'] !== 'mentor'): ?>
                             <a href="schedule_session.php?mentor_id=<?php echo $row['mentor_id']; ?>" class="btn btn-primary">Schedule a Session</a>    
                            <?php endif; ?> 
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <?php if($_SESSION['role'] === 'mentor'): ?>
        <p>You are not connected to any members yet.</p>
        <?php endif; ?>
        <p>You are not connected to any mentors yet.</p>
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
