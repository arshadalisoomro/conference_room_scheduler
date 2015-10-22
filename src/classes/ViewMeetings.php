<?php

class ViewMeetings {
	function buildTable($db, $userId) {
        $query = "SELECT * FROM reservation WHERE user_id = " . $userId;

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "user_id: " . $row['user_id'] . ", conference room: " . $row['conference_room_id'] . "<br/>";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}