<?php
function checkRole($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        header("Location: unauthorized.php");
        exit;
    }
}

?>
