<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

$insertStatement = "INSERT INTO reservation 
					(`user_id`, `conference_room_id`, `time_slot_id`, `date`) 
					VALUES (:user_id,:conference_room_id,:time_slot_id,:date_val)";

$insertParams = array(
            ':user_id' => $_GET['user_id'],
            ':conference_room_id' => $_GET['room_id'],
            ':time_slot_id' => $_GET['time_slot'],
            ':date_val' => $_GET['date']
        );

try {
    $stmt = $db->prepare($insertStatement);
    $result = $stmt->execute($insertParams);

	header("Location: home.php");
	die("Redirecting to home.php");
} catch(PDOException $ex) {
    echo "exception: " . $ex->getMessage();
}