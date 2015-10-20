<?php

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    $user = $_SESSION['user'];
    $scheduler = new Scheduler();

    if(empty($_SESSION['user'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script type="text/javascript">
    $(function() {
        $("#datepicker").datepicker({
                minDate: "+1D", 
                maxDate: "+6M"
            });
    });

    function dateUpdated() {
        document.getElementById("time_form").submit();
    }
    </script>
</head>


<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Schedule a Reservation</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <?php AccountDropdownBuilder::buildDropdown($db, $_SESSION); ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/><section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">
                <form id="time_form" action="pick_time.php" method="get">
                    <input type="hidden" name="room_id" value="<?php echo $_GET['room_id'] ?>" />
                    Date: <input type="text" id="datepicker" name="date" readonly="readonly" value="<?php echo $_GET['date'] ?>" onchange="dateUpdated()"/>

                    <?php 

                    if (!empty($_GET['date'])) {
                        // we want to show the time selector and a submit button
                        $scheduler->buildAvailableTimes($db, $_GET);
                        echo '<br/><br/><input type="submit" value="Schedule Reservation" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"/>' . "\r\n";
                    }

                    ?>
                </div>
              </div>
            </section>
        </main>
    </div>
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>
</html>