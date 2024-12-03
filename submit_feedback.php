<?php 
session_start();
include_once "conf/database.php";
include_once "includes/header.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $event_id = $_POST['event_id'];
        $rating = $_POST['rating'];
        $comments = $_POST['comments'];
        $member_id = $_SESSION['user_id'];

        $database = new Database();
        $db = $database->getConnection();

        $query = "INSERT INTO feedback (member_id, event_id, rating, comments) VALUES (:member_id, :event_id, :rating, :comments)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comments', $comments, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Feedback-ul a fost trimis cu succes!";
            header("Location: events.php");
            exit();
        } else {
            echo "Eroare la trimiterea feedback-ului.";
        }
    }
?>