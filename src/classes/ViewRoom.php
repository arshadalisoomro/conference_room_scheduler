<?php 
error_reporting(E_ALL);
//parse_str($_SERVER['QUERY_STRING']);




class ViewRoom {
	function buildRoom($db, $post, $userId) {
 $tableType = $post['type']; // will either be me, users, or all

        // you will have to join reservation with location, room, user, and time slot
        $query="SELECT room.room_number,room.geometry,room.capacity,location.name,resource.quality_description FROM room INNER JOIN location ON room.location_id=location._id INNER JOIN resource ON room._id=resource.room_id INNER JOIN resource_type ON resource.resource_type_id=resource_type._id ";
        echo "<h3>";
		
		echo "Room Details";
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
            echo '          <th class="mdl-data-table__cell--non-numeric">Building</th>' . "\r\n";
            echo '              <th>Room</th>' . "\r\n";
            echo '              <th>Capacity</th>' . "\r\n";
            echo '              <th>Geometry</th>' . "\r\n";
			echo '              <th>Equipments</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";
    	        echo '         <td>' . $row['name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['room_number'] . '</td>' . "\r\n";
                echo '         <td>' . $row['capacity'] . '</td>' . "\r\n";
				echo '         <td>' . $row['geometry'] . '</td>' . "\r\n";
				echo '         <td>' . $row['quality_description'] . '</td>' . "\r\n";
                //echo '         <td>' . "<a class='home_page_link' onclick='return confirm(\"Are you sure?\")'href='cancel_reservation.php?reservation_id=" . $row['_id']  . "'>Delete</a>" . '</td>' . "\r\n";
     	        echo '      </tr>' . "\r\n";
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }

	}
}