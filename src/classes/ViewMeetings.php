<?php

class ViewMeetings {
	function buildTable($db, $userId) {
        // you will have to join reservation with location, room, user, and time slot
        $query = "SELECT * FROM reservation WHERE user_id = " . $userId;

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
            echo '              <th>Time</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // a table row looks like this: (this is with the dummy data on google's website though, change it to use our data)
                /*
                <tr>
                    <td class="mdl-data-table__cell--non-numeric">Acrylic (Transparent)</td>
                    <td>25</td>
                    <td>$2.90</td>
                </tr>
                */
                echo '<h3>user_id: ' . $row['user_id'] . ', conference room: ' . $row['conference_room_id'] . '</h3><br/>';
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}