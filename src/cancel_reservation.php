<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

$deleteStatement = "DELETE FROM reservation 
                    WHERE _id = :reservation_id";

$deleteParams = array(
            ':reservation_id' => $_GET['reservation_id']
        );

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