<?php
session_start();
include_once "conf/database.php";
include_once "includes/header.php";
$database = new database();
$db = $database->getConnection();

if (isset($_GET['events_id'])) {
    $event_id = $_GET['events_id'];
    $member_id = $_SESSION['user_id'];
    $status ='confirmed';

} else {
    echo "ID-ul evenimentului nu a fost specificat.";
}

$query = "INSERT INTO event_registrations (member_id,event_id,registration_date,status) VALUES(:member_id,:event_id,NOW(),:status)";
$stmt = $db->prepare($query);
$stmt->bindParam(':member_id',$member_id);
$stmt->bindParam(':event_id',$event_id);
$stmt->bindParam(':status',$status);


if ($stmt->execute()) {
    $_SESSION['success_message'] = "Te-ai înregistrat cu succes la eveniment!";
} else {
    $_SESSION['error_message'] = "A apărut o eroare. Încearcă din nou.";
}

header("Location: events.php");
exit();
?>

