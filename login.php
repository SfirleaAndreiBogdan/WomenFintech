<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
$database = new database();
$db = $database->getConnection();

$error = "";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, email,first_name, roles, password FROM members WHERE email = :email AND password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['roles'];
        $_SESSION['success_message'] = "Logare reușită! Bine ai revenit, " . htmlspecialchars($user['first_name']) . "!";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email sau parolă incorectă.";
    }
}
?>

<label id="dark-change"></label>
<div class="form-container">
    <h2>Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Login</button>
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
<?php
include_once "includes/footer.php";
?>

