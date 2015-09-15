<?php

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");
    require("MailFiles/PHPMailerAutoload.php");

    // Initialize error messages to blank.
    $r = new Register();

    if(!empty($_POST)) {
        // Ensure that the user fills out fields.
        if ($r->checkNoFormErrors($_POST, $db)) {
            $hash = md5(rand(0,2147483647));
            // check if the email exists
            $r->checkEmailExists($_POST['email'], $db);

            // If the email is not registered yet, send them a confirmation email
            // and add it to the database.
            if (empty($r->registeredEmail)) {
                $link = "http://wal-engproject.rhcloud.com/src/verify.php?email=" . $_POST['email'] . "&hash=" . $hash;
                if(!$r->sendRegistrationEmail($_POST['email'], $link)) {
                    $r->registrationFailure = "Verification email could not be sent.";
                } else {
                    $r->registrationSuccess = "A confirmation email has been sent to the email address that you provided";
                    $r->saveRegistration($_POST, $hash, $db);
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
    <script src="assets/bootstrap.min.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" type="text/css">
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
      <a href="src/home.php" class="brand">Conference Room Scheduler</a>
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <li><a href="../index.php">Login</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h1>Register</h1> <br/><br/>
</div>

</body>
</html>
