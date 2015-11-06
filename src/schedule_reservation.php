<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

$insertStatement;
$insertParams;
if (empty($_GET['recurrence'])) {
    $insertStatement = "INSERT INTO reservation 
                    (`user_id`, `conference_room_id`, `time_slot_id`, `recurrence_id`, `date`) 
                    VALUES (:user_id,:conference_room_id,:time_slot_id,:recurrence_id,:date_val)";

    $insertParams = array(
                ':user_id' => $_GET['user_id'],
                ':conference_room_id' => $_GET['room_id'],
                ':time_slot_id' => $_GET['time_slot'],
                ':recurrence_id' => '0',
                ':date_val' => $_GET['date']
            );
} else {
    $createRecurrence = "INSERT INTO recurrence (`recurrence_type_id`) VALUES (:_id)";
    $createParams = array(':_id' => $_GET['recurrence']);
    $stmt = $db->prepare($createRecurrence);
    $result = $stmt->execute($createParams);
    echo "id: " . $db->lastInsertId();
}


try {
    //$stmt = $db->prepare($insertStatement);
    //$result = $stmt->execute($insertParams);

	//header("Location: home.php");
	//die("Redirecting to home.php");
} catch(PDOException $ex) {
	echo "query: " . $insertStatement . "</br>";
	print_r($insertParams);
    echo "<br/>exception: " . $ex->getMessage();
}