<?php

class Scheduler {
	function buildRoomTitle($db, $roomId) {
		$query = "SELECT name, room_number FROM room r LEFT OUTER JOIN location l ON r.location_id = l._id " .
					"WHERE r._id = " . $roomId;

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();
            $row = $stmt->fetch();
            echo "<h3>" . $row['name'] . ", Room " . $row['room_number'] . "</h3>";
        } catch(Exception $e) {
            echo $e->getMessage();
        }
	}

    function buildAvailableTimes($db, $post) {
    	$query = "SELECT * FROM time_slot";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<br/><b>Available Time Slots:</b> <select onchange="timeChange()" name="time_slot">';
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == 0) {
                    echo '<option selected="selected" value="' . $row['_id'] . '">' . $this->buildTimeDisplay($row) . '</option>';
                } else {
                    echo '<option value="' . $row['_id'] . '">' . $this->buildTimeDisplay($row) . '</option>';
                }
                $i = $i + 1;
            }

            echo '</select>';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function buildTimeDisplay($timeSlot) {
    	return $timeSlot['start_time'] . ' - ' . $timeSlot['end_time'];
    }
}