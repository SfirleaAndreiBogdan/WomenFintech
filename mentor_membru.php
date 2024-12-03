<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
$database = new database();
$db = $database->getConnection();


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}


$query = "SELECT * FROM members WHERE roles = 'mentor' AND id != :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id',$_SESSION['user_id']);
$stmt->execute();

?>

<label id="dark-change"></label>
<a href="recomended_connections.php" class="btn">Recomended connections</a>
<h2>Selecteaza un mentor:</h2>
<div class="row">
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
 <div class="col-md-4">
     <div class="card member-card">
         <div class="card-body">
             <h5 class="card-title"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h5>
             <img id="photo" src="<?php echo "img/" . htmlspecialchars($row['photo']); ?>" alt="Profile Photo" class="img-fluid">
             <p class="card-text">
                 <strong>Profession:</strong> <?php echo htmlspecialchars($row['profession']); ?><br>
                 <strong>Company:</strong> <?php echo htmlspecialchars($row['company']); ?><br>
                 <?php if (!empty($row['biography'])): ?>
                     <strong>Biography:</strong> <?php echo htmlspecialchars($row['biography']); ?><br>
                 <?php endif; ?>
                 <?php if (!empty($row['skills'])): ?>
                     <strong>Skills:</strong> <?php echo htmlspecialchars($row['skills']); ?><br>
                 <?php endif; ?>
                 <?php if (!empty($row['education'])): ?>
                     <strong>Education:</strong> <?php echo htmlspecialchars($row['education']); ?><br>
                 <?php endif; ?>
                 <?php if (!empty($row['portfolio_links'])): ?>
                     <strong>Portfolio:</strong> <?php echo htmlspecialchars($row['portfolio_links']); ?><br>
                 <?php endif; ?>
                 <?php if (!empty($row['social_links'])): ?>
                     <strong>Social Links:</strong> <?php echo htmlspecialchars($row['social_links']); ?><br>
                 <?php endif; ?>
             </p>

             <a href="connect_mentor.php?mentor_id=<?php echo $row['id']; ?>" class="btn btn-primary">Connect</a>
        </div>
     </div>
 </div>
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
include_once "includes/header.php";
?>