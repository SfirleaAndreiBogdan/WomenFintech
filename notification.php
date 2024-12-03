<?php 
include_once "conf/database.php";
include_once "includes/header.php";

$database = new Database();
$db = $database->getConnection();


if (isset($_POST['submit'])) {
    $message = trim($_POST['message']);

    session_start();
    if (isset($_SESSION['user_id'])) {
        $member_id = $_SESSION['user_id'];
    } else {
        $member_id = null;
    }

    $read_status = 0; 
    $created_at = date('d-m-Y s:i:H');

    $query = "INSERT INTO notifications (member_id, message, read_status, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $member_id);
    $stmt->bindParam(2, $message);
    $stmt->bindParam(3, $read_status);
    $stmt->bindParam(4, $created_at);

    if ($stmt->execute()) {
        echo "Notificarea a fost trimisă cu succes!";
    } else {
        echo "Eroare la trimiterea notificării.";
    }
}
?>
<label id="dark-change"></label>

<div class="container">
    <form action="" method="POST">
        <label for="message">Mesaj:</label>
        <textarea name="message" id="message" rows="4" cols="50" placeholder="Scrieți mesajul..." required></textarea>
        <button type="submit" name="submit">Trimite notificare</button>
    </form>
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
