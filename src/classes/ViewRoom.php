<?php 
error_reporting(E_ALL);
//parse_str($_SERVER['QUERY_STRING']);

class ViewRoom {
	function buildRoom($db, $post, $userId) {
		$tableType = $post['type']; // will either be me, users, or all
		
		$room_list_json=array();
		
		

        // you will have to join reservation with location, room, user, and time slot
        $query="SELECT room.room_number,room.geometry,room.capacity,location.name,resource.quality_description,resource_type.description FROM room LEFT JOIN location ON room.location_id=location._id LEFT JOIN resource ON room._id=resource.room_id LEFT JOIN resource_type ON resource.resource_type_id=resource_type._id ";
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

            echo '<table align="center" class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp" style="height:400px;width:600px">' . "\r\n";
            echo '  <thead >' . "\r\n";
            echo '      <tr >' . "\r\n";
            echo '          <th class="mdl-data-table__cell--non-numeric">Building &nbsp;</th>' . "\r\n";
            echo '              <th>Room</th>' . "\r\n";
            echo '              <th>Capacity</th>' . "\r\n";
            echo '              <th>Geometry</th>' . "\r\n";
			echo '              <th>Equipments</th>' . "\r\n";
			echo '              <th>Resource Types</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead style="display:block;">' . "\r\n";
            echo '  <tbody style="position:absolute; overflow-y: auto; overflow-x: hidden; height:85%; width:100%;">' . "\r\n";

    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								
                echo '      <tr>' . "\r\n";
    	        echo '         <td>' . $row['name'] . '</td>' . "\r\n";
    	        echo '         <td>' . $row['room_number'] . '</td>' . "\r\n";
                echo '         <td>' . $row['capacity'] . '</td>' . "\r\n";
				echo '         <td>' . $row['geometry'] . '</td>' . "\r\n";
				echo '         <td>' . $row['quality_description'] . '</td>' . "\r\n";
				echo '         <td>' . $row['description'] . '</td>' . "\r\n";			
                //echo '         <td>' . "<a class='home_page_link' onclick='return confirm(\"Are you sure?\")'href='cancel_reservation.php?reservation_id=" . $row['_id']  . "'>Delete</a>" . '</td>' . "\r\n";
     	        echo '      </tr>' . "\r\n";
            
			$each_record=array(			
			"name" => $row['name'],
			"room_number" => $row['room_number'],
			"capacity" => $row['capacity'],
			"geometry" => $row['geometry'],
			"quality_description" => $row['quality_description'],
			"description" => $row['description'],
			);
            array_push($room_list_json, $each_record);
			
			}

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";
			//echo json_encode($room_list_json);
			//echo '<script type="text/javascript">console.log('+json_encode($room_list_json)+');</script>';
	
			echo '<script type="text/javascript">var room_json='+json_encode($room_list_json)+'; console.log(room_json);</script>';
			//echo 'function load_table_json(){ window["room_detail"]=json_encode('+$room_list_json+');';
			//echo 'console.log("aaa"); alert("test");}';
			//echo 'load_table_json();';
			//echo '</script>';
			

        } catch(Exception $e) {
            echo $e->getMessage();
        }

	}
	function getRoom($db, $post, $userId) {
		$tableType = $post['type']; // will either be me, users, or all
		
		$room_list_json=array();
        $query="SELECT room.room_number,room.geometry,room.capacity,location.name,resource.quality_description,resource_type.description 
                FROM resource JOIN resource_type ON resource.resource_type_id = resource_type._id 
                	JOIN room ON room._id = resource.room_id 
                	JOIN location ON room.location_id = location._id 
                ORDER BY location.name, room.room_number";


        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();


    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								
   
            
			$each_record=array(			
				"name" => $row['name'],
				"room_number" => $row['room_number'],
				"capacity" => $row['capacity'],
				"geometry" => $row['geometry'],
				"quality_description" => $row['quality_description'],
				"description" => $row['description'],
			);
            array_push($room_list_json, $each_record);
			
			}

			//echo json_encode($room_list_json);
			//echo '<script type="text/javascript">console.log('+json_encode($room_list_json)+');</script>';
	
			echo json_encode($room_list_json);

			

        } catch(Exception $e) {
            echo $e->getMessage();
        }

	}
}