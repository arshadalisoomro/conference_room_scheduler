<?php

class ViewMeetings {
	function buildTable($db, $userId) {
        // you will have to join reservation with location, room, user, and time slot
        $query = "SELECT * FROM reservation res JOIN room r ON res.conference_room_id = r._id JOIN location l ON r.location_id = l._id JOIN user u ON res.user_id = u._id JOIN time_slot t ON res.time_slot_id = t._id WHERE user_id = " . $userId;

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            // todo: we want to create a table here from our room data
            // here is the page for material design table:
            // http://www.getmdl.io/components/index.html#tables-section

            // the .\r\n just creates

            echo '<table class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">' . "\r\n";
            echo '  <thead>' . "\r\n";
            echo '      <tr>' . "\r\n";
            echo '          <th class="mdl-data-table__cell--non-numeric">Name</th>' . "\r\n";
            echo '              <th>Building</th>' . "\r\n";
            echo '              <th>Room Number</th>' . "\r\n";
            echo '              <th>Start Time</th>' . "\r\n";
            echo '              <th>End Time</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";
                echo '         <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['room_number'] . '</td>' . "\r\n";
     	        echo '         <td>' . $row['start_time'] . '</td>' . "\r\n";
                echo '         <td>' . $row['end_time'] . '</td>' . "\r\n";
     	        echo '      </tr>' . "\r\n";
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}