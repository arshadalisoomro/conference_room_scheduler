<?php

class RoomBuilder {
    function buildCards($db) {
        $query = "SELECT * FROM room";

        // execute the statement
        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            // loop through, adding the all the rooms in a seperate card
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">' . "\r\n";
                echo '  <div class="mdl-card mdl-cell mdl-cell--12-col">' . "\r\n";
                echo '      <div class="mdl-card__supporting-text">' . "\r\n";
                echo '          <h3>Room Number: ' . $row['room_number'] . '</h3>' . "\r\n";
                echo '          <p>Resources: ' . $this->getResourcesString($db, $row['_id']) . '</p>' . "\r\n";
                echo '          <p>Capacity: ' . $row['capacity'] . '</p>';
                echo '      </div>' . "\r\n";
                echo '  </div>' . "\r\n";
                echo '</section>' . "\r\n";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }

        echo '</br>' . "\r\n";
    }

    function getResourcesString($db, $roomId) {
        $query = "SELECT description, quality_description 
                  FROM resource r LEFT JOIN resource_type rt ON r.resource_type_id = rt._id 
                  WHERE r.room_id = " . $roomId;

        $string = "";
        $inserted = false;

        // execute the statement
        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            $i = 0;

            // loop through, adding the all the rooms in a seperate card
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == 0) {
                    $string = $row['description'];
                } else {
                    $string = $string . ", " . $row['description'];
                }

                $inserted = true;
                $i = $i + 1;
            }
        } catch(Exception $e) {
            $string = $e->getMessage();
        }

        if (!$inserted) {
            $string = "No resources available at this location.";
        }

        return $string;
    }
}
