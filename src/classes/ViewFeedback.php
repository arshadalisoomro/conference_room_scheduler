<?php

class ViewFeedback {
	function build($db) {
        $query = "SELECT feedback, name, room_number, first_name, last_name
                  FROM feedback f JOIN reservation res ON f.reservation_id = res._id
                        JOIN room r ON res.room_id = r._id
                        JOIN location l ON r.location_id = l._id
                        JOIN user u ON res.user_id = u._id";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<table class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">' . "\r\n";
            echo '  <thead>' . "\r\n";
            echo '      <tr>' . "\r\n";
            echo '          <th class="mdl-data-table__cell--non-numeric">Location</th>' . "\r\n";
            echo '          <th>User</th>' . "\r\n";
            echo '          <th>Feedback</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

            $last_room = "";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";
                if ($last_room != ($row['name'] . ' #' . $row['room_number'])) {
                    $last_description = $row['name'] . ' #' . $row['room_number']
                    echo '<td>' . $row['name'] . ' #' . $row['room_number'] . 's</td>' . "\r\n";
                } else {
                    echo '<td></td>';
                }
                echo '         <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['feedback'] . '</td>' . "\r\n";                
     	        echo '      </tr>' . "\r\n";
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}

