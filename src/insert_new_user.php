<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");
require("MailFiles/PHPMailerAutoload.php");

$realPassword = PasswordUtils::generateNewPassword();
$passwordSalt = PasswordUtils::generatePasswordSalt();
$hashedPassword = PasswordUtils::hashPassword($realPassword, $passwordSalt);

$email = $_POST['email'];

$insertStatement = "INSERT INTO user
					(`user_type_id`, `password`, `password_salt`, `first_name`, `last_name`, `email`, `picture_url`) 
					VALUES (:user_type_id,:password,:password_salt,:first_name,:last_name,:email,:picture_url)";

$insertParams = array(
            ':user_type_id' => $_POST['user_type_id'],
            ':password' => $hashedPassword,
            ':password_salt' => $passwordSalt,
            ':first_name' => $_POST['first'],
            ':last_name' => $_POST['last'],
            ':email' => $email,
            ':picture_url' => 'http://walphotobucket.s3.amazonaws.com/default.jpg'
        );

try {
    $stmt = $db->prepare($insertStatement);
    $result = $stmt->execute($insertParams);

    $link = "http://dbsystems-engproject.rhcloud.com/";
    $message = 'Hello!<br/><br/>'
            . 'An account has been created for you on our conference room scheduler!'
            . ' Please click <a href='.$link.'>here</a> log in.<br/><br/>'
            . 'Password: ' . $realPassword
            . '<br/><br/>Thank you,<br/>Team 6';
    $mailer = new SendEmail();
    $mailer->SendEmail($email,"Conference Room Scheduler",$message,false);

	header("Location: home.php");
	die("Redirecting to home.php");
} catch(PDOException $ex) {
	echo "query: " . $insertStatement . "</br>";
	print_r($insertParams);
    echo "<br/>exception: " . $ex->getMessage();
}