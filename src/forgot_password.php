<?php

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");
    require("MailFiles/PHPMailerAutoload.php");
    
    $fp = new ForgotPassword();
  
    if(!empty($_POST)) {
        // Check if the email is recognized.
        $fp->checkEmail($_POST['email'], $db);
        // If the email was recognized, generate a new password and send an email.
        if(empty($fp->noEmail) && !empty($_POST['challenge_question_answer'])) {
            if($fp->checkAnswer(htmlspecialchars($_POST['challenge_question_answer']))) {
                $newPassword = PasswordUtils::generateNewPassword();
                if($fp->sendNewPassword($newPassword)) {
                    $fp->success = "An email has been sent to the address that you provided. "
                        . "Use the password included in the email to log in.";
                    // Hash the new password and update the tables.
                    $newSalt = PasswordUtils::generatePasswordSalt();
                    $newPassword = PasswordUtils::hashPassword($newPassword, $newSalt);
                    $fp->updateTables($newPassword, $newSalt, $db);
                } else {
                    $fp->registrationFailure = "Verification email could not be sent. Please try again later.";
                }
            }
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <style>.error {color: #FF0000;}</style>
    <style>.success {color: #00FF00;}</style>
    <meta charset="utf-8">
    <title>Conference Room</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="../assets/bootstrap.min.js"></script>
    <link href="../assets/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../assets/styles.css" rel="stylesheet" type="text/css">
</head>


<body>

<div class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a href="home.php" class="brand">Conference Room Scheduler</a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
            <li><a href="../index.php">Login</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h1>Password Retrieval</h1> <br />
    
</div>

</body>
</html>
