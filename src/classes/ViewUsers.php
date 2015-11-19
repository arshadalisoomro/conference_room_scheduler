<?php

class ViewUsersManager {
	function buildTable($db, $post, $userId) {
        $tableType = $post['type'];
        $query;

        echo "<h3>";
        if ($tableType == 'users') { // manager functionality
            echo "Created Users";
            $query = "SELECT u._id AS _id, first_name, last_name, description, email FROM user u JOIN user_type ut ON u.user_type_id = ut._id WHERE created_by_id = " . $userId;
        }
        echo "</h3>" . "\r\n";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<table class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">' . "\r\n";
            echo '  <thead>' . "\r\n";
            echo '      <tr>' . "\r\n";
            echo '          <th class="mdl-data-table__cell--non-numeric">Type</th>' . "\r\n";
            echo '          <th>Name</th>' . "\r\n";
            echo '          <th>Email</th>' . "\r\n";
            echo '          <th>Delete?</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

    	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";
                echo '         <td>' . $row['description'] . '</td>' . "\r\n";
                echo '         <td>' . "<a class='home_page_link' href='user_page.php?id=" . $row['_id']  . "'>" . $row['first_name'] . ' ' . $row['last_name'] . "</a>" . '</td>' . "\r\n";
    	        echo '         <td>' . $row['email'] . '</td>' . "\r\n";                
                echo '         <td>' . "<a class='home_page_link' onclick='return confirm(\"Are you sure?\")'href='user_page.php?to_delete_id=" . $row['_id']  . "'>Delete</a>" . '</td>' . "\r\n";
     	        echo '      </tr>' . "\r\n";
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

class ViewUsersAdmin {
    function buildTable($db, $post, $userId) {
        $tableType = $post['type'];
        $query;

        echo "<h3>All Users</h3>";
        $query = "SELECT u._id AS _id, u.first_name AS first_name, u.last_name AS last_name, description, u.email AS email, created.first_name AS created_first_name, created.last_name AS created_last_name , u.created_by_id AS created_by_id FROM user u JOIN user_type ut ON u.user_type_id = ut._id JOIN user created ON u.created_by_id = created._id WHERE u._id <> " . $userId . " ORDER BY ut._id";

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute();

            echo '<table class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">' . "\r\n";
            echo '  <thead>' . "\r\n";
            echo '      <tr>' . "\r\n";
            echo '          <th class="mdl-data-table__cell--non-numeric">Type</th>' . "\r\n";
            echo '          <th>Name</th>' . "\r\n";
            echo '          <th>Managed By</th>' . "\r\n";
            echo '          <th>Email</th>' . "\r\n";
            echo '          <th>Delete?</th>' . "\r\n";
            echo '      </tr>' . "\r\n";
            echo '  </thead>' . "\r\n";
            echo '  <tbody>' . "\r\n";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '      <tr>' . "\r\n";
                echo '         <td>' . $row['description'] . '</td>' . "\r\n";
                echo '         <td>' . "<a class='home_page_link' href='user_page.php?id=" . $row['_id']  . "'>" . $row['first_name'] . ' ' . $row['last_name'] . "</a>" . '</td>' . "\r\n";
                if ($row['description'] == "Manager") {
                    echo '<td></td>' . "\r\n";
                } else {
                    echo '<td>' . "<a class='home_page_link' href='user_page.php?id=" . $row['created_by_id']  . "'>" . $row['created_first_name'] . ' ' . $row['created_last_name'] . "</a>" . '</td>' . "\r\n";
                }
                echo '         <td>' . $row['email'] . '</td>' . "\r\n";                
                echo '         <td>' . "<a class='home_page_link' onclick='return confirm(\"Are you sure?\")'href='user_page.php?to_delete_id=" . $row['_id']  . "'>Delete</a>" . '</td>' . "\r\n";
                echo '      </tr>' . "\r\n";
            }

            echo '  </tbody>' . "\r\n";
            echo '</table>' . "\r\n";

        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
}