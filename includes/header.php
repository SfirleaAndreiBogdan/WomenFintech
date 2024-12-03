<?php
include_once "roles.php";
?>
<!DOCTYPE html>
<html lang="ro">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Women in FinTech</title>
 <link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <link rel="stylesheet" href="css/style.css">
</head>
<body class="header-body">
 <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
 <div class="container">
 <a class="navbar-brand" href="index.php"><img src="logo2.png" alt="Logo" id="logo"></a>
 <button class="navbar-toggler" type="button" data-toggle="collapse" datatarget="#navbarNav">
 <span class="navbar-toggler-icon"></span></button>
 <div class="collapse navbar-collapse" id="navbarNav">
 <ul class="navbar-nav">
 <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
           <li class="nav-item">
             <a class="nav-link" href="members.php">Members</a>
           </li>
<?php endif; ?>
 <li class="nav-item">
 <a class="nav-link" href="add_member.php">Register</a>
 </li>
 <li class="nav-item">
 <a class="nav-link" href="dashboard.php">Dashboard</a>
 </li>
 <li class="nav-item">
 <a class="nav-link lgin" href="resource_hub.php">Resources Hub</a>
 </li>
 <li class="nav-item">
 <a class="nav-link lgin" href="events.php">Calendar of Events</a>
 </li>
 <li class="nav-item">
 <a class="nav-link lgin" href="mentor_membru.php">Mentors</a>
 </li>
 <li class="nav-item">
 <a class="nav-link lgin" href="conections.php">Connections</a>
 </li>
 <li class="nav-item">
 <a class="nav-link" href="sessions.php">Sessions</a>
 </li>
 <li class="nav-item">
 <a class="nav-link" href="jobs_board.php">Jobs Board</a>
 </li>
 <li class="nav-item">
 <a class="nav-link lgin" href="login.php">Login</a>
 </li>
 <li class="nav-item">
 
<a href="logout.php" class="nav-link" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
 </li>
 </ul>
 </div>
 </div>
 </nav>
 <div class="container mt-4">


