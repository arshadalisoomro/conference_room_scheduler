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
                <span class="mdl-layout-title">Register as Admin</span>
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
                    <form action="register.php" method="post">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="access_code" name="access_code" />
                            <label class="mdl-textfield__label" for="access_code">Access Code...</label>
                        </div> <span class="error"><?php echo $r->noAccessCode; ?></span><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="first_name" name="first_name" />
                            <label class="mdl-textfield__label" for="first_name">First Name...</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="last_name" name="last_name" />
                            <label class="mdl-textfield__label" for="last_name">Last Name...</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="email" name="email" />
                            <label class="mdl-textfield__label" for="email">Email...</label>
                        </div> <span class="error"> * <?php echo $r->noEmail; echo $r->incorrectEmail; echo $r->registeredEmail;?></span><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="password" name="password" />
                            <label class="mdl-textfield__label" for="password">Password...</label>
                        </div> <span class="error"> * <?php echo $r->noPassword; echo $r->badPassword; ?></span><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="confirmPassword" name="confirmPassword" />
                            <label class="mdl-textfield__label" for="confirmPassword">Confirm Password...</label>
                        </div> <span class="error"> * <?php echo $r->noConfirmPassword;?></span><br/>
                        <span class="error"><?php echo $r->noPasswordMatch;?></span><br/>
                        <span class="success"><?php echo $r->registrationSuccess;?></span>
                        <span class="error"><?php echo $r->registrationFailure;?></span>
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Register
                        </button><br/><br/>
                        <p>Password must have at least one number and letter, and must be less than 20 characters.</p> 
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
