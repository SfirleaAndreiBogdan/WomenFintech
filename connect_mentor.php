<?php
session_start();
include_once "conf/database.php";

if (isset($_GET['mentor_id'])) {
    $mentor_id = $_GET['mentor_id'];
    $member_id = $_SESSION['user_id'];
    
    $database = new database();
    $db = $database->getConnection();

    $query = "SELECT * FROM mentor_mentee WHERE mentor_id = :mentor_id AND mentee_id = :member_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':mentor_id', $mentor_id);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Te-ai conectat deja la acest mentor.";
        exit();
    }

    $query = "INSERT INTO mentor_mentee (mentor_id, mentee_id) VALUES (:mentor_id, :member_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':mentor_id', $mentor_id);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->execute();

    header('Location: dashboard.php');
    exit();
} else {
    echo "ID-ul mentorului nu a fost specificat.";
}
?>
