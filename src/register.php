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
            // check if the email exists
            $r->checkEmailExists($_POST['email'], $db);

            // If the email is not registered yet, send them a confirmation email
            // and add it to the database.
            if (empty($r->registeredEmail)) {
                $r->saveRegistration($_POST, $db);
                
                header("Location: ../index.php");
                die("Redirecting to: ../index.php");
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
    <h1>Register</h1> <br/><br/>
    <form action="register.php" method="post">
        </select><br/>
        Access Code:<br/>
        <input type="text" name="access_code" value="<?php echo htmlspecialchars($_POST['access_code'])?>" />  
        <span class="error"><?php echo $r->noAccessCode; ?></span><br/>
        First Name:<br/>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'])?>" /><br/>
        Last Name:<br/>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'])?>" /><br/>
        Email:<br/>
        <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'])?>" />
        <span class="error"> * <?php echo $r->noEmail; echo $r->incorrectEmail; echo $r->registeredEmail;?></span><br/>
        Password:<br/>
        <input type="password" name="password" value="" />
        <span class="error"> * <?php echo $r->noPassword; echo $r->badPassword; ?></span><br/>
        Confirm Password:<br/>
        <input type="password" name="confirmPassword" value="" />
        <span class="error"> * <?php echo $r->noConfirmPassword;?></span><br/>
        <span class="error"><?php echo $r->noPasswordMatch;?></span><br/>
        <span class="success"><?php echo $r->registrationSuccess;?></span>
        <span class="error"><?php echo $r->registrationFailure;?></span>
        <input type="submit" class="btn btn-info" value="Register" /><br/><br/>
        <p>Password must have at least one number and letter, and must be less than 20 characters.</p> 
    </form>
</div>

</body>
</html>
