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

    function buildRecurrenceOptions($db, $post) {
        $query = "SELECT * FROM recurrence_type where _id <> 1";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<br/><b>Recurrence:</b> <select name="recurrence">';
            $i = 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == $post['recurrence'] || (empty($post['recurrence']) && $i == 1)) {
                    echo '<option selected="selected" value="' . $row['_id'] . '">' . $row['description'] . '</option>';
                } else {
                    echo '<option value="' . $row['_id'] . '">' . $row['description'] . '</option>';
                }
                $i = $i + 1;
            }

            echo '</select>';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function buildAvailableTimes($db, $post) {
        $whereClause = $this->buildWhereClause($db, $post);
    	$query;
        if ($whereClause != "") {
            $query = "SELECT * FROM time_slot WHERE " . $whereClause;
        } else {
            $query = "SELECT * FROM time_slot";
        }

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<br/><b>Available Time Slots:</b> <select onchange="timeChange()" name="time_slot">';
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['_id'] == $post['time_slot'] || (empty($post['time_slot']) && i == 0)) {
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

    function buildUnavailableTimes($db, $post) {
        $whereClause = $this->buildUnavailableWhereClause($db, $post);
        $query;
        if ($whereClause != "") {
            $query = "SELECT * FROM time_slot WHERE " . $whereClause;
        } else {
            $query = "SELECT * FROM time_slot";
        }

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<br/><b>Timeslots available for waitlisting:</b> <select onchange="timeChange()" name="time_slot">';
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['_id'] == $post['time_slot'] || (empty($post['time_slot']) && i == 0)) {
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

    function buildWhereClause($db, $post) {
        $whereClause = "";
        $query = "SELECT time_slot_id FROM reservation WHERE date = '" . $post['date'] . "' and conference_room_id = " . $post['room_id'];

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == 0) {
                    $whereClause = "_id <> " . $row['time_slot_id'];
                } else {
                    $whereClause = $whereClause . " and _id <> " . $row['time_slot_id'];
                }

                $i++;
            }
        } catch(Exception $e) { }

        return $whereClause;
    }

    function buildUnavailableWhereClause($db, $post) {
        $whereClause = "";
        $query = "SELECT time_slot_id 
                  FROM reservation 
                  WHERE date = '" . $post['date'] . "' and conference_room_id = " . $post['room_id'];

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == 0) {
                    $whereClause = "_id = " . $row['time_slot_id'];
                } else {
                    $whereClause = $whereClause . " or _id = " . $row['time_slot_id'];
                }

                $i++;
            }
        } catch(Exception $e) { }

        return $whereClause;
    }
}