<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

$insertStatement = "SELECT _id 
                    FROM feedback
                    WHERE reservation_id = :id";

$insertParams = array(
            ':id' => $_POST['reservation_id']
        );

$shouldUpdate = false;
try {
    $stmt = $db->prepare($insertStatement);
    $result = $stmt->execute($insertParams);

    $row = $stmt->fetch();
    if (!empty($row['_id'])) {
        $shouldUpdate = true;
    }
    
    header("Location: home.php");
    die("Redirecting to home.php");
} catch(PDOException $ex) {

}

if (!$shouldUpdate) {
    $insertStatement = "INSERT INTO feedback (`reservation_id`, `feedback`) 
                        VALUES (:reservation_id, :feedback)";
} else {
    $insertStatement = "UPDATE feedback 
                        SET `feedback` = :feedback
                        WHERE reservation_id = :reservation_id";
}

$insertParams = array(
            ':reservation_id' => $_POST['reservation_id'],
            ':feedback' => $_POST['feedback']
        );

try {
    $stmt = $db->prepare($insertStatement);
    $result = $stmt->execute($insertParams);

    echo "should update: " . $shouldUpdate;

	//header("Location: home.php");
	//die("Redirecting to home.php");
} catch(PDOException $ex) {
	echo "query: " . $insertStatement . "</br>";
	print_r($insertParams);
    echo "<br/>exception: " . $ex->getMessage();
}