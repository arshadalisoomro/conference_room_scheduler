<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");
require("MailFiles/PHPMailerAutoload.php");

$deleteStatement;
$deleteParams;

if (!empty($_GET["recurrence_id"])) {
    $deleteStatement = "DELETE FROM reservation 
                    WHERE recurrence_id = :recurrence_id 
                         AND date > CURDATE()";

    $deleteParams = array(
            ':recurrence_id' => $_GET['recurrence_id']
        );

    $query = "SELECT _id
              FROM reservation
              WHERE recurrence_id = :id";
    $query_params = array(
        ':id' => $_GET['recurrence_id']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $query = "SELECT email, conference_room_id, w.user_id AS user_id, date
                      FROM waitlist w 
                            JOIN user u ON w.user_id = u._id 
                            JOIN reservation res ON w.blocking_reservation_id = res._id
                      WHERE blocking_reservation_id = :id";
            $query_params = array(
                ':id' => $row['_id']
            );

            try {
                $stmt2 = $db->prepare($query);
                $result2 = $stmt2->execute($query_params);

                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    $mailer = new SendEmail();
                    $mailer->SendEmail($row['email'],
                        "Conference Room Scheduler",
                        'One of your waitlisted rooms is now available. To claim it, visit <a href="http://dbsystems-engproject.rhcloud.com/src/pick_time.php?submitted=false&date=' . $row['date'] . '&room_id=' . $row['conference_room_id'] . '&user_id=' . $row['user_id'] . '">here</a>',
                        false);
                }
            } catch(PDOException $ex) {
                die("Failed to run query: " . $ex->getMessage());
            }
        }
    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
} else {
    $deleteStatement = "DELETE FROM reservation 
                    WHERE _id = :reservation_id";

    $deleteParams = array(
            ':reservation_id' => $_GET['reservation_id']
    );

    $query = "SELECT email, conference_room_id, w.user_id AS user_id, date
              FROM waitlist w 
                    JOIN user u ON w.user_id = u._id 
                    JOIN reservation res ON w.blocking_reservation_id = res._id
              WHERE blocking_reservation_id = :id";
    $query_params = array(
        ':id' => $_GET['reservation_id']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mailer = new SendEmail();
            $mailer->SendEmail($row['email'],
                "Conference Room Scheduler",
                'One of your waitlisted rooms is now available. To claim it, visit <a href="http://dbsystems-engproject.rhcloud.com/src/pick_time.php?submitted=false&date=' . $row['date'] . '&room_id=' . $row['conference_room_id'] . '&user_id=' . $row['user_id'] . '">here</a>',
                false);
        }
    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
}

try {
    $stmt = $db->prepare($deleteStatement);
    $result = $stmt->execute($deleteParams);

	header("Location: home.php");
	die("Redirecting to home.php");
} catch(PDOException $ex) {
	echo "query: " . $deleteStatement . "</br>";
	print_r($deleteParams);
    echo "<br/>exception: " . $ex->getMessage();
}