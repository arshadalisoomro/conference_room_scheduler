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
                echo '          <p>Resources: ' . $this->getResourcesString($row['_id']) . '</p>' . "\r\n";
                echo '      </div>' . "\r\n";
                echo '  </div>' . "\r\n";
                echo '</section>' . "\r\n";
                echo '</br>' . "\r\n";
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function getResourcesString($roomId) {
        return "test, one, two, three";
    }
}
