<?php

class ViewMeetings {
	function buildTable($db, $post, $userId) {
        $tableType = $post['type']; // will either be me, users, or all

        // you will have to join reservation with location, room, user, and time slot
        $query;

        echo "<h3>";
        if ($tableType == 'me') {
            echo "Future Reservations";
            $query = "SELECT res._id AS _id, first_name, last_name, name, room_number, start_time, end_time, date, rect.description as description FROM reservation res JOIN room r ON res.conference_room_id = r._id JOIN location l ON r.location_id = l._id JOIN user u ON res.user_id = u._id JOIN time_slot t ON res.time_slot_id = t._id JOIN recurrence rec on res.recurrence_id = rec._id JOIN recurrence_type rect on rec.recurrence_type_id = rect._id WHERE date >= CURDATE() AND user_id = " . $userId . " ORDER BY date";
        } else if ($tableType == 'users') {
            echo "Created User's Reservations";
            $query = "SELECT res._id AS _id, first_name, last_name, name, room_number, start_time, end_time, date, rect.description as description FROM reservation res JOIN room r ON res.conference_room_id = r._id JOIN location l ON r.location_id = l._id JOIN user u ON res.user_id = u._id JOIN time_slot t ON res.time_slot_id = t._id JOIN recurrence rec on res.recurrence_id = rec._id JOIN recurrence_type rect on rec.recurrence_type_id = rect._id WHERE date >= CURDATE() AND created_by_id = " . $userId . " ORDER BY date";
        } else { // all users
            echo "All User's Reservations";
            $query = "SELECT res._id AS _id, first_name, last_name, name, room_number, start_time, end_time, date, rect.description as description FROM reservation res JOIN room r ON res.conference_room_id = r._id JOIN location l ON r.location_id = l._id JOIN user u ON res.user_id = u._id JOIN time_slot t ON res.time_slot_id = t._id JOIN recurrence rec on res.recurrence_id = rec._id JOIN recurrence_type rect on rec.recurrence_type_id = rect._id WHERE date >= CURDATE()" . " ORDER BY date";
        }
        echo "</h3>" . "\r\n";

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
            echo '              <th>Room</th>' . "\r\n";
            echo '              <th>Date</th>' . "\r\n";
            echo '              <th>Start Time</th>' . "\r\n";
            echo '              <th>End Time</th>' . "\r\n";
            echo '              <th>Recurring</th>' . "\r\n";
            echo '              <th>Delete?</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";
<<<<<<< HEAD

    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";
                echo '         <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['room_number'] . '</td>' . "\r\n";
                echo '         <td>' . $row['date'] . '</td>' . "\r\n";
     	        echo '         <td>' . $row['start_time'] . '</td>' . "\r\n";
                echo '         <td>' . $row['end_time'] . '</td>' . "\r\n";
                echo '         <td>' . $row['description'] . '</td>' . "\r\n";
                echo '         <td>' . "<a class='home_page_link' onclick='return confirm(\"Are you sure?\")'href='cancel_reservation.php?reservation_id=" . $row['_id']  . "'>Delete</a>" . '</td>' . "\r\n";
     	        echo '      </tr>' . "\r\n";
            }
=======
            echo '      <tr>' . "\r\n";
            echo '         <td class="mdl-data-table__cell--non-numeric"> $row['last_name'])</td>' . "\r\n";
	    echo '         <td> $row['name']</td>' . "\r\n";
	    echo '         <td> $row['room_number']</td>' . "\r\n";
 	    echo '         <td> $row['start_time']</td>' . "\r\n";
 	    echo '      <tr>' . "\r\n";
        }
>>>>>>> add veiw room structure

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}