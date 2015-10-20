<?php

class RoomBuilder {
    function buildFilters($db, $post) {
        echo '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">' . "\r\n";
        echo '  <div class="mdl-card mdl-cell mdl-cell--12-col">' . "\r\n";
        echo '      <div class="mdl-card__supporting-text">' . "\r\n";
        echo '          <h3>Filter Rooms:</h3>' . "\r\n";
        echo '          <form action="search_rooms.php" method="get">' . "\r\n";

        $this->makeResourceCheckboxes($db, $post);
        $this->makeCapacityInput($db, $post);
        $this->makeLocationSpinner($db, $post);

        echo '          <br/><br/><input type="submit" value="Filter"/>' . "\r\n";
        echo '          </form>' . "\r\n";
        echo '      </div>' . "\r\n";
        echo '  </div>' . "\r\n";
        echo '</section>' . "\r\n";
    }

    function buildCards($db, $post) {
        $query = "SELECT * FROM resource r LEFT OUTER JOIN room rm ON r.room_id = rm._id " . 
                    " LEFT OUTER JOIN location l on rm.location_id = l._id " . 
                    $this->buildWhereClause($post) . 
                    " GROUP BY rm._id";

        //print_r($post);
        //echo "<br/>query: " . $query;

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            // loop through, adding the all the rooms in a seperate card
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">' . "\r\n";
                echo '  <div class="mdl-card mdl-cell mdl-cell--12-col">' . "\r\n";
                echo '      <div class="mdl-card__supporting-text">' . "\r\n";
                echo '          <h3>' . $row['name'] . ', Room ' . $row['room_number'] . '</h3>';
                echo '          Resources: ' . $this->getResourcesString($db, $row['room_id']) . '<br/>';
                echo '          Capacity: ' . $row['capacity'] . '<br/>';
                echo '          <br/><button onclick="location.href=\'http://google.com\';" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">Select Room</button>';
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

    function makeResourceCheckboxes($db, $post) {
        $query = "SELECT * FROM resource_type";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<b>Resources (check all that are prefered):</b> <br/>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($this->isChecked($post, "resources", $row['_id'])) {
                    echo '<input type="checkbox" name="resources[]" value="' . $row['_id'] . '" checked/>' . $row['description'] . '   ';
                } else {
                    echo '<input type="checkbox" name="resources[]" value="' . $row['_id'] . '"/>' . $row['description'] . '   ';
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function makeLocationSpinner($db, $post) {
        $query = "SELECT * FROM location";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<br/><b>Room Location:</b> <select name="location_id">';
            if (!empty($post['location_id'])) {
                echo '<option value="">Any Location</option>';
            } else {
                echo '<option value="" selected="selected">Any Location</option>';
            }

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($post['location_id'] == $row['_id']) {
                    echo '<option selected="selected" value="' . $row['_id'] . '">' . $row['name'] . '</option>';
                } else {
                    echo '<option value="' . $row['_id'] . '">' . $row['name'] . '</option>';
                }
            }

            echo '</select>';
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    function makeCapacityInput($db, $post) {
        echo '<br/><br/><b>Miniumum Room Capacity:</b> 
            <input type="number" name="capacity" min="1" max="75" value="' . $post['capacity'] . '"> people';
    }

    function buildWhereClause($post) {
        $where = "";

        if (!empty($post['location_id'])) {
            $where = $where . "WHERE location_id = " . $post['location_id'];
        }

        // if they have check a resource to filter by and they have filtered by location
        if (isset($post['resources']) && !empty($where)) {
            $where = $where . " AND (";
            $or = "";
            foreach($post['resources'] as $val) {
                if (!empty($or)) {
                    $or = $or . " OR resource_type_id = " . $val;
                } else {
                    $or = "resource_type_id = " . $val;
                }
            }
            $where = $where . $or . ")";
        } else if (isset($post['resources'])) { 
            // if they have filtered by resource, but not by location
            $where = "WHERE ";
            $or = "";
            foreach($post['resources'] as $val) {
                if (!empty($or)) {
                    $or = $or . " OR resource_type_id = " . $val;
                } else {
                    $or = "(resource_type_id = " . $val;
                }
            }
            $where = $where . $or . ")";
        }

        if (!empty($post['capacity']) && !empty($where)) {
            $where = $where . " AND capacity > " . $post['capacity'];
        } else if (!empty($post['capacity'])) {
            $where = "WHERE capacity > " . $post['capacity'];
        }

        return $where;
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
