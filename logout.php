<?php

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
}

session_start();


session_unset();


session_destroy();


header("Location: login.php");
exit; 
?>
