<?php

class ProvideFeedback {
    function build($db) {
        $thisUserId = $_SESSION['user']['_id'];

        $query = "SELECT user_id AS _id 
                  FROM reservation r JOIN user u ON r.user_id = u._id 
                  WHERE date <= CURDATE()
                  AND created_by_id = " . $thisUserId . " 
                  GROUP BY user_id 
                  ORDER BY date";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->buildTable($db, $row['_id']);
                echo "<br/><br/>";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

	function buildTable($db, $userId) {
        $query = "SELECT res._id AS _id, first_name, last_name, name, room_number, start_time, end_time, date, recurrence_id, rect.description as description 
                FROM reservation res JOIN room r ON res.conference_room_id = r._id JOIN location l ON r.location_id = l._id JOIN user u ON res.user_id = u._id JOIN time_slot t ON res.time_slot_id = t._id JOIN recurrence rec on res.recurrence_id = rec._id JOIN recurrence_type rect on rec.recurrence_type_id = rect._id 
                WHERE date BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE() AND user_id = " . $userId . " ORDER BY date";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<table class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">' . "\r\n";
            echo '  <thead>' . "\r\n";
            echo '      <tr>' . "\r\n";
            echo '          <th class="mdl-data-table__cell--non-numeric">Name</th>' . "\r\n";
            echo '              <th>Place</th>' . "\r\n";
            echo '              <th>Date</th>' . "\r\n";
            echo '              <th>Time</th>' . "\r\n";
            echo '              <th>Feedback</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

            $print_name = true;
    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";

                // only show the name for the first row for each user
                if ($print_name) {
                    $print_name = false;
                    echo '<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>' . "\r\n";
                } else {
                    echo '<td></td>';
                }
                
    	        echo '         <td>' . $row['name'] . ' #' . $row['room_number'] . '</td>' . "\r\n";
                echo '         <td>' . $row['date'] . '</td>' . "\r\n";
     	        echo '         <td>' . $row['start_time'] . ' - ' . $row['end_time'] . '</td>' . "\r\n";
                echo '         <td><a class="home_page_link" href="provide_feedback.php?reservation_id=' . $row['_id'] . '</td>' . "\r\n";
                
     	        echo '      </tr>' . "\r\n";
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}