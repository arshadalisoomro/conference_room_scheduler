<?php 

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");
require("MailFiles/PHPMailerAutoload.php");

$editStatement;
$editParams;

if (!empty($_GET["submitted"])) {
    $editStatement = "UPDATE reservation
		      SET * = :submitted
		      WHERE _id = :_id";

    $editParams = array(
            ':submitted' => $_GET['submitted']
        );

    $query = "SELECT _id
              FROM reservation
              WHERE _id = :id";******
    $query_params = array(
        ':id' => $_GET['conference_room_id']*******
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $query = "SELECT email, conference_room_id, w.user_id AS user_id, date
                      FROM waitlist w 
                            JOIN user u ON w.user_id = u._id 
                            JOIN reservation res ON w.blocking_reservation_id = res._id
                      WHERE blocking_reservation_id = :id";
            $query_params = array(
                ':id' => $row['_id']
            );

            try {
                $stmt2 = $db->prepare($query);
                $result2 = $stmt2->execute($query_params);

                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    $mailer = new SendEmail();
                    $mailer->SendEmail($row['email'],
                        "Conference Room Scheduler",
                        'One of your waitlisted rooms is now available. To claim it, visit <a href="http://dbsystems-engproject.rhcloud.com/src/pick_time.php?submitted=false&date=' . $row['date'] . '&room_id=' . $row['conference_room_id'] . '&user_id=' . $row['user_id'] . '">here</a>',
                        false);
                }
            } catch(PDOException $ex) {
                die("Failed to run query: " . $ex->getMessage());
            }
        }
    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
} else {
    $deleteStatement = "DELETE FROM reservation ***
                    WHERE _id = :reservation_id";***

    $deleteParams = array(
            ':reservation_id' => $_GET['reservation_id']****
    );

    $query = "SELECT email, conference_room_id, w.user_id AS user_id, date
              FROM waitlist w 
                    JOIN user u ON w.user_id = u._id 
                    JOIN reservation res ON w.blocking_reservation_id = res._id
              WHERE blocking_reservation_id = :id";
    $query_params = array(
        ':id' => $_GET['reservation_id']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mailer = new SendEmail();
            $mailer->SendEmail($row['email'],
                "Conference Room Scheduler",
                'One of your waitlisted rooms is now available. To claim it, visit <a href="http://dbsystems-engproject.rhcloud.com/src/pick_time.php?submitted=false&date=' . $row['date'] . '&room_id=' . $row['conference_room_id'] . '&user_id=' . $row['user_id'] . '">here</a>',
                false);
        }
    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
}

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
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reservation Changes</title>
    <meta name="description" content="Edit reservation management system for Database Systems">
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
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <?php AccountDropdownBuilder::buildDropdown($db, $_SESSION); ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/><section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">
                <?php $scheduler->buildRoomTitle($db, $_GET['submitted']) ?>
                <form id="time_form" action="pick_time.php" method="get">
                    <input id="submitted" type="hidden" name="submitted" value="false" />
                    <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?>" />
                    <input type="hidden" name="room_id" value="<?php echo $_GET['room_id'] ?>" />
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