<?php

class RoomBuilder {
    function buildFilters($db, $post) {
        echo '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">' . "\r\n";
        echo '  <div class="mdl-card mdl-cell mdl-cell--12-col">' . "\r\n";
        echo '      <div class="mdl-card__supporting-text">' . "\r\n";
        echo '          <h3>Filter Rooms:</h3>' . "\r\n";
        echo '          <form action="search_rooms.php" method="post">' . "\r\n";

        $this->makeResourceCheckboxes($db);
        $this->makeLocationSpinner($db);
        $this->makeCapacityInput($db);

        echo '          <br/><input type="submit" value="Filter"/>' . "\r\n";
        echo '          </form>' . "\r\n";
        echo '      </div>' . "\r\n";
        echo '  </div>' . "\r\n";
        echo '</section>' . "\r\n";
    }

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

    function makeResourceCheckboxes($db) {
        $query = "SELECT * FROM resource_type";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo 'Resources: <br/>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<input type="checkbox" name="resources[]" value="' . $row['_id'] . '"/>' . $row['description'] . '   ';
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function makeLocationSpinner($db) {
        $query = "SELECT * FROM location";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            $i = 0;

            echo '<br/><br/>Location: <select name="location_id">';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($i == 0) {
                    echo '<option value="' . $row['id'] . '" selected="selected">' . $row['name'] . '</option>';
                } else {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }

                $i = $i + 1;
            }

            echo '</select>';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function makeCapacityInput($db) {
        
    }

    function isChecked($post, $chkname, $value) {
        if(!empty($post[$chkname])) {
            foreach($post[$chkname] as $chkval) {
                if($chkval == $value) {
                    return true;
                }
            }
        }

        return false;
    }
}
