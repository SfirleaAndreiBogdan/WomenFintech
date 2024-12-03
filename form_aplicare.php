<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
$database = new database();
$db = $database->getConnection();


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $cover_letter = $_POST['cover_letter'];
    $cv = $_POST['cv'];
    $job_id = $_GET['job_id'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO job_applications(job_id,user_id,name,email,cover_letter,cv_file,application_date)
     VALUES(:job_id, :user_id, :name, :email, :cover_letter, :cv_file, NOW())";
     $stmt = $db->prepare($query);
     $stmt->bindParam(':job_id',$job_id,PDO::PARAM_INT);
     $stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
     $stmt->bindParam(':name',$name,PDO::PARAM_STR);
     $stmt->bindParam(':email',$email,PDO::PARAM_STR);
     $stmt->bindParam(':cover_letter',$cover_letter,PDO::PARAM_STR);
     $stmt->bindParam(':cv_file',$cv,PDO::PARAM_STR);

     if($stmt->execute()){
        echo "You Applied succesfully!";
     }else{
        echo "ERROR";
     }
}
?>
<div class="container">
<form method="POST">
    <div class="form-group">
        <label for="name">Your Name</label>
        <input type="text"  class="form-control"name="name" id="name" placeholder="Full Name" required>
    </div>
    
    <div class="form-group">
        <label for="email">Your Email</label>
        <input type="email" class="form-control" name="email" id="email" required>
    </div>
    
    <div class="form-group">
        <label for="cover_letter">Cover Letter</label>
        <textarea name="cover_letter" class="form-control" id="cover_letter" rows="4" required></textarea>
    </div>
    
    <div class="form-group">
        <label for="cv">Upload your CV</label>
        <input type="file" name="cv" class="form-control" id="cv" accept=".pdf,.doc,.docx" required>
    </div>
    
    <button type="submit" class="btn">Apply for this Job</button>
</form>

</div>

<?php 
include_once "includes/footer.php";
?>
