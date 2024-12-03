<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
include_once "search.php";
include_once "roles.php";


$database = new Database();
$db = $database->getConnection();

checkRole('admin');

$rows_per_page = 3;
$records = "SELECT COUNT(*) FROM members";
$stmt = $db->prepare($records);
$stmt->execute();
$num = $stmt->fetchColumn();

$total_pages = ceil($num / $rows_per_page);
$current_page;
if(isset($_GET['page-nr'])){
    $current_page = (int)$_GET['page-nr'];
}else{
    $current_page = 1;
}
$start = ($current_page - 1) * $rows_per_page;

$search_query = "";
$search_term = "";
if (isset($_POST["search"]) && !empty($_POST["search"])) {
    $search_term = $_POST["search"];
    $search_query = " WHERE CONCAT(first_name, ' ', last_name) LIKE :search_term";
}

$query = "SELECT * FROM members" . $search_query . " ORDER BY profession,first_name LIMIT :start, :rows_per_page";


$stmt = $db->prepare($query);


if ($search_query) {
    $stmt->bindValue(':search_term', '%' . $search_term . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':rows_per_page', $rows_per_page, PDO::PARAM_INT);


$stmt->execute();

?>
<form action="" method="POST">
    <input type="text" name="search" placeholder="Search by first name"/> 
    <button type="submit">Search</button>
</form>

<label id="dark-change"></label>
<h2>Members Directory</h2>
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

             <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
             <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
         </div>
     </div>
 </div>
<?php endwhile; ?>
</div>
<div class="pagination">
    <?php echo $current_page; ?>/<?php echo $total_pages; ?> Pages</p>
</div>
<div class="page-numbers">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page-nr=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <a href="?page-nr=<?php echo max(1, $current_page - 1); ?>">Previous</a>
    <a href="?page-nr=<?php echo min($total_pages, $current_page + 1); ?>">Next</a>
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
