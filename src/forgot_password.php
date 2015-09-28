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
        if(empty($fp->noEmail)) {
            $newPassword = PasswordUtils::generateNewPassword();
            if($fp->sendNewPassword($newPassword)) {
                $fp->success = "An email has been sent to the address that you provided. "
                    . "Use the password included in the email to log in.";
                // Hash the new password and update the tables.
                $newSalt = PasswordUtils::generatePasswordSalt();
                $newPassword = PasswordUtils::hashPassword($newPassword, $newSalt);
                $fp->updateTables($newPassword, $newSalt, $db);
            } else {
                $fp->registrationFailure = "Password reset failed. Please try again later.";
            }
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Conference Room</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Reset Password</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="../index.php">Login</a>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/>
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div id="content" class="mdl-card__supporting-text">
                    <form action="forgot_password.php" method="post">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="email" name="email" />
                            <label class="mdl-textfield__label" for="email">Email...</label>
                        </div><br/>
                        <span class="error"><?php echo $fp->noEmail;?></span><br/>
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Reset Password
                        </button>
                        <span class = "success"><?php echo $fp->success;?></span>
                        <span class = "error"><?php echo $fp->regisrationFailure;?></span>
                    </form>
                </div>
              </div>
            </section>
            <br/>
        </main>
    </div>
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>

</html>
