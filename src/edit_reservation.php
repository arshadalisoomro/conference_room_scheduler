<?php
    error_reporting(E_ALL);

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    $user = $_SESSION['user'];
    $scheduler = new Scheduler();

    if(empty($_SESSION['user']) && empty($_GET['user_id'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
    } else if (empty($_GET['room_id'])) {
        header("Location: search_rooms.php");
        die("Redirecting to search_rooms.php"); 
    } else if (isset($_GET['submitted']) && $_GET['submitted'] == "true") {
        $getParams;

        if (empty($_GET['user_id'])) {
            if ($_GET['recurring'] == 'on') {
                $getParams = "room_id=" . $_GET['room_id'] . "&date=" . $_GET['date'] . "&time_slot=" . $_GET['time_slot'] . "&user_id=" . $_SESSION['user']['_id'] . "&recurrence=" . $_GET['recurrence'] . "&rec_end=" . $_GET['recurrence_end'];
            } else {
                $getParams = "room_id=" . $_GET['room_id'] . "&date=" . $_GET['date'] . "&time_slot=" . $_GET['time_slot'] . "&user_id=" . $_SESSION['user']['_id'];
            }
        } else {
            if ($_GET['recurring'] == 'on') {
                $getParams = "room_id=" . $_GET['room_id'] . "&date=" . $_GET['date'] . "&time_slot=" . $_GET['time_slot'] . "&user_id=" . $_GET['user_id'] . "&recurrence=" . $_GET['recurrence'] . "&rec_end=" . $_GET['recurrence_end'];
            } else {
                $getParams = "room_id=" . $_GET['room_id'] . "&date=" . $_GET['date'] . "&time_slot=" . $_GET['time_slot'] . "&user_id=" . $_GET['user_id'];
            }
        }
        

		

        header("Location: schedule_reservation.php?" . $getParams);
        die("Redirecting to schedule_reservation.php"); 
    }
	if (empty($_GET['reservation_id'])==False) {
		echo "trydeleting";
			$deleteParams = array(
            ':reservation_id' => $_GET['reservation_id']
        );		
		$deleteOldStatement = "DELETE FROM reservation WHERE _id =:reservation_id";
		$stmt = $db->prepare($deleteOldStatement);
        $result = $stmt->execute($deleteParams);
	}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Reservation</title>
    <meta name="description" content="Reservation editing management system for Database Systems">
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
                maxDate: "+6M",
                dateFormat: "yy-mm-dd"
            });
        $("#datepicker2").datepicker({
                minDate: "+1D", 
                maxDate: "+6M",
                dateFormat: "yy-mm-dd"
            });
    });

    function dateUpdated() {
        document.getElementById("time_form").submit();
    }

    function submitButton() {
        document.getElementById("submitted").value = "true";
    }
    </script>
</head>


<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Edit a Reservation</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Editor</span>
            <nav class="mdl-navigation">
                <?php AccountDropdownBuilder::buildDropdown($db, $_SESSION); ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/><section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">
                <?php $scheduler->buildRoomTitle($db, $_GET['room_id']) ?>
                <form id="time_form" action="pick_time.php" method="get">
                    <input id="submitted" type="hidden" name="submitted" value="false" />
                    <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?>" />
                    <input type="hidden" name="room_id" value="<?php echo $_GET['room_id'] ?>" />
					<input type="hidden" name="reservation_id" value="<?php echo $_GET['reservation_id'] ?>" />
                    <b>Reservation Date:</b> <input type="text" id="datepicker" name="date" readonly="readonly" value="<?php echo $_GET['date'] ?>" onchange="dateUpdated()"/>

                    <?php 

                    if (!empty($_GET['date'])) {
                        // we want to show the time selector and a submit button
                        $scheduler->buildAvailableTimes($db, $_GET);
                        if (!empty($_GET['recurring']) && $_GET['recurring'] == 'on') {
                            echo '</br><br/><input checked type="checkbox" name="recurring" onchange="dateUpdated()"> Recurring Reservation</br>';
                            $scheduler->buildRecurrenceOptions($db);
                            if (empty($_GET['recurrence_end'])) {
                                echo '</br><b>Recurrence End Date:</b> <input type="text" id="datepicker2" name="recurrence_end" readonly="readonly" value="' . $_GET['date'] . '"/>';
                            } else {
                                echo '</br><b>Recurrence End Date:</b> <input type="text" id="datepicker2" name="recurrence_end" readonly="readonly" value="' . $_GET['recurrence_end'] . '"/>';
                            }
                        } else {
                            echo '</br><br/><input type="checkbox" name="recurring" onchange="dateUpdated()"> Recurring Reservation</br>';
                        }

                        echo '<br/><br/><input onclick="submitButton();" type="submit" value="Schedule Reservation" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"/>' . "\r\n";
                        echo '<br/><br/>Can\'t find the time you want? Looks like it is taken...<br/>Click <a class="home_screen_link" href="add_to_waitlist.php?room_id=' . $_GET['room_id'] . '">here</a> to sign up for the waitlist.';
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