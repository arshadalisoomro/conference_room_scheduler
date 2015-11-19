<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

$insertStatement = "INSERT INTO feedback (`reservation_id`, `feedback`) 
					VALUES (:reservation_id,:feedback)";

$insertParams = array(
            ':reservation_id' => $_POST['reservation_id'],
            ':feedback' => $_POST['feedback']
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