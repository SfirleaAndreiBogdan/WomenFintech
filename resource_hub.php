<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";


if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

$database = new database();
$db = $database->getConnection();

$query = "SELECT * FROM articles";
$stmt = $db->prepare($query);
$stmt->execute();

$category = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<label id="dark-change"></label>
<div class="resources">
<p><h2>Articole:</h2></p>

    <form method="GET">
        <select name="filter" id="filter">
            <option value="">Alege domeniul</option>
            <option value="all">All</option>
            <option value="job">Jobs</option>
            <option value="mysql">Mysql</option>
            <option value="php">PHP</option>
        </select>
        <button type="submit" name="submit">Filtreaza</button>
    </form>

    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <?php if (isset($_GET['filter']) && $_GET['filter'] !== 'all'): ?>
        <?php if ($_GET['filter'] == $row['category']): ?>
            <article>
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['content']); ?></p>
            </article>
        <?php endif; ?>
    <?php else: ?>
        <article>
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['content']); ?></p>
        </article>
    <?php endif; ?>
<?php endwhile; ?>


<p><h2>Tutoriale:</h2></p>
<ul>
<li><a href="https://youtu.be/DwHxDlaOsyw?si=h_MeGCAt_Q3t2UKr">Link spre Tutorial PHPMyAdmin!</a></li>
<li><a href="https://youtu.be/Cz3WcZLRaWc?si=vXZ9g3Ae-3xrictD">Link spre Tutorial MySQL beginner!</a></li>
</ul>

<p><h2>Podcasturi:</h2></p>
<ul>
<li><a href="https://youtu.be/xYDzVZ1P4uc?si=J1ILoJM8gEaBrZYE">Spre Podcast!</a></li>
<li><a href="https://youtu.be/xiUTqnI6xk8?si=MkfwrIYQ9fE3Ge5_">Spre Podcast!</a></li>
</ul>

<p><h2>Prezentari:</h2></p>
<a href="res.jpg" download="res.jpg" style="text-decoration:none;">
        <button>Descarcă Fișier</button>
    </a>


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