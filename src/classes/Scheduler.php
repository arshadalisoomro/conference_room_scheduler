<?php

class Scheduler {
    function buildAvailableTimes($db, $post) {
    	$query = "SELECT * FROM time_slot";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<br/><b>Time Slot:</b> <select name="time_slot">';
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == 0) {
                    echo '<option selected="selected" value="' . $row['_id'] . '">' . $this->buildTimeDisplay($row) . '</option>';
                } else {
                    echo '<option value="' . $row['_id'] . '">' . $this->buildTimeDisplay($row) . '</option>';
                }
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