<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");
require("MailFiles/PHPMailerAutoload.php");

$insertStatement = "INSERT INTO reservation 
                    (`user_id`, `conference_room_id`, `time_slot_id`, `recurrence_id`, `date`) 
                    VALUES (:user_id,:conference_room_id,:time_slot_id,:recurrence_id,:date_val)";

$resParams;

$maxReservations = "UPDATE user SET max_number_reservations= (SELECT COUNT(_id)
		    FROM reservation r
		    WHERE r.user_id = :user_id)
		    WHERE _id = :id";

$insertParams;


if ($_GET['max_number_reservations'] < 10) {
     $resParams= array(
		':user_id => $_GET['user_id']
		':_id => %_GET['_id']
            );
     try {
	 
	$stmt = $db->prepare($maxReservations);
	$result = $stmt->execute($resParams);	
	} catch(PDOException $ex) {
            echo "query: " . $maxReservations . "</br>";
            print_r($resParams);
            echo "<br/>exception: " . $ex->getMessage();
	 }
}
if ($_GET['max_number_reservations'] > 10) {
	echo '<script type="text/jscript"> alert("You have exceded the maximum number of reservations") </script>';
        header("Location: home.php");
        die("Redirecting to home.php");
}
if (empty($_GET['recurrence'])) {
    $insertParams = array(
                ':user_id' => $_GET['user_id'],
                ':conference_room_id' => $_GET['room_id'],
                ':time_slot_id' => $_GET['time_slot'],
                ':recurrence_id' => '1',
                ':date_val' => $_GET['date']
            );

    try {
        $stmt = $db->prepare($insertStatement);
        $result = $stmt->execute($insertParams);

        $mailer = new SendEmail();
        $mailer->SendEmail($_SESSION['user']['email'],
            "Conference Room Scheduler",
            "A new reservation has been scheduled for you!<br/>To view your reservations, please use the following link:<br/><br/>http://dbsystems-engproject.rhcloud.com/src/view_meetings.php?type=me",
            false);

        header("Location: home.php");
        die("Redirecting to home.php");
    } catch(PDOException $ex) {
        echo "query: " . $insertStatement . "</br>";
        print_r($insertParams);
        echo "<br/>exception: " . $ex->getMessage();
    }
} else {
    $error = false;

    $createRecurrence = "INSERT INTO recurrence (`recurrence_type_id`) VALUES (:_id)";
    $createParams = array(':_id' => $_GET['recurrence']);
    $stmt = $db->prepare($createRecurrence);
    $result = $stmt->execute($createParams);
    
    $recurrenceId = $db->lastInsertId();

    $typeId = "SELECT increment_string FROM recurrence r JOIN recurrence_type rt ON r.recurrence_type_id = rt._id WHERE r._id = " . $recurrenceId;
    $stmt = $db->prepare($typeId);
    $result = $stmt->execute();
    $row = $stmt->fetch();

    $incrementString = $row['increment_string'];

    $currentDate = strtotime($_GET['date']);
    $recurrenceEndDate = strtotime($_GET['rec_end']);
    while ($currentDate <= $recurrenceEndDate) {
        $insertParams = array(
                ':user_id' => $_GET['user_id'],
                ':conference_room_id' => $_GET['room_id'],
                ':time_slot_id' => $_GET['time_slot'],
                ':recurrence_id' => $recurrenceId,
                ':date_val' => date("Y-n-d", $currentDate)
            );

        try {
            $stmt = $db->prepare($insertStatement);
            $result = $stmt->execute($insertParams);
        } catch(PDOException $ex) {
            $error = true;

            echo "query: " . $insertStatement . "</br>";
            print_r($insertParams);
            echo "<br/>exception: " . $ex->getMessage();
        }

        $currentDate = strtotime($incrementString, $currentDate);
    }

    if (!$error) {
        $mailer = new SendEmail();
        $mailer->SendEmail($_SESSION['user']['email'],
            "Conference Room Scheduler",
            "A new reservation has been scheduled for you!<br/>To view your reservations, please use the following link:<br/><br/>http://dbsystems-engproject.rhcloud.com/src/view_meetings.php?type=me",
            false);

        header("Location: home.php");
        die("Redirecting to home.php");
    }
}
?>