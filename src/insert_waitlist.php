<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");
require("MailFiles/PHPMailerAutoload.php");

$query = "SELECT _id
          FROM reservation 
          WHERE conference_room_id = :room_id 
               AND date = :get_date
               AND time_slot_id = :timeslot";

$query_params = array(
    ':room_id' => $_GET['room_id'],
    ':get_date' => $_GET['date'],
    ':timeslot' => $_GET['time_slot']
);

try {
    $stmt = $db->prepare($query);
    $result = $stmt->execute($query_params);
} catch(PDOException $ex) {
    die("Failed to run query: " . $ex->getMessage());
}

$row = $stmt->fetch();

$insertStatement = "INSERT INTO waitlist (`blocking_reservation_id`, `user_id`) 
                    VALUES (:reservation_id, :user_id)";
$insertParams = array(
                ':reservation_id' => $row['_id'],
                ':user_id' => $_SESSION['user']['_id']
            );

try {
    $stmt = $db->prepare($insertStatement);
    $result = $stmt->execute($insertParams);

    $mailer = new SendEmail();
    $mailer->SendEmail($_SESSION['user']['email'],
        "Conference Room Scheduler",
        "You have been added to a waitlist.<br/>If the room becomes available, you will be notified immediately.",
        false);

    header("Location: home.php");
    die("Redirecting to home.php");
} catch(PDOException $ex) {
    echo "query: " . $insertStatement . "</br>";
    print_r($insertParams);
    echo "<br/>exception: " . $ex->getMessage();
}