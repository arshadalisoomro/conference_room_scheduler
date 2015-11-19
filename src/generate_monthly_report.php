<?php

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");
require("MailFiles/PHPMailerAutoload.php");

if(empty($_SESSION['user'])) {
    header("Location: ../index.php");
    die("Redirecting to index.php");
} 

$query = "SELECT _id, email
          FROM user
          WHERE user_type_id = 2";

$error = false;
try {
    $stmt = $db->prepare($query);
    $result = $stmt->execute();

    $mailer = new SendEmail();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mailer->SendEmail($row['email'],
            "Conference Room Scheduler - Monthly Report",
            "Hello Manager!<br/>
            Below is a link to the monthly report for your created users reservations. If anything looks out of place, feel free to contact the system administrator for help.<br/><br/>" .

            '<a href="http://dbsystems-engproject.rhcloud.com/src/monthly_report.php?user_id=' . $row['_id'] . '">Monthly Usage Report</a>',
            false);
    }
} catch(PDOException $ex) {
    $error = true;
    echo $e->getMessage();
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

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Profile</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <?php AccountDropdownBuilder::buildDropdown($db, $_SESSION) ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/>
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">
                    <?php
                        if ($error) {
                            echo "Error generating monthly report.";
                        } else {
                            echo "Monthly report sent successfully!";
                        }
                    ?>
                </div>
              </div>
            </section>
            <br/>
        </main>
    </div>
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>

</html>
