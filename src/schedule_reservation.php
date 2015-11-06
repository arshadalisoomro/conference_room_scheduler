<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

$insertStatement = "INSERT INTO reservation 
                    (`user_id`, `conference_room_id`, `time_slot_id`, `recurrence_id`, `date`) 
                    VALUES (:user_id,:conference_room_id,:time_slot_id,:recurrence_id,:date_val)";
$insertParams;

if (empty($_GET['recurrence'])) {
    $insertParams = array(
                ':user_id' => $_GET['user_id'],
                ':conference_room_id' => $_GET['room_id'],
                ':time_slot_id' => $_GET['time_slot'],
                ':recurrence_id' => '0',
                ':date_val' => $_GET['date']
            );

    try {
        $stmt = $db->prepare($insertStatement);
        $result = $stmt->execute($insertParams);

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

    $currentDate = strtotime($_GET['date']);
    $recurrenceEndDate = strtotime($_GET['rec_end']);

    echo "current date: " . $currentDate . ", end date: " . $recurrenceEndDate . ", difference: " . $recurrenceEndDate - $currentDate;
    while ($currentDate <= $recurrenceEndDate) {
        $insertParams = array(
                ':user_id' => $_GET['user_id'],
                ':conference_room_id' => $_GET['room_id'],
                ':time_slot_id' => $_GET['time_slot'],
                ':recurrence_id' => $recurrenceId,
                ':date_val' => date("yy-mm-dd", $currentDate)
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

        $currentDate = $currentDate + 86400000;
    }

    if (!$error) {
        header("Location: home.php");
        die("Redirecting to home.php");
    }
}