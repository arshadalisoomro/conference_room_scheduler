<?php

class AccountDropdownBuilder {

    public static function buildDropdown($db, $session) {
        $query = "
                SELECT picture_url
                FROM user
                WHERE
                    _id = :id
                ";
        $query_params = array(
            ':id' => $session['user']['_id']
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        } catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();
        $pictureUrl = $row['picture_url'];

        $profileLink = "http://dbsystems-engproject.rhcloud.com/src/user_page.php?id=" . $session['user']['id'];

        echo "<li class=\"dropdown\">";
        echo "<a class=\"dropdown-toggle\" href=\"#\" data-toggle=\"dropdown\">Account  <strong class=\"caret\"></strong></a>";
        echo "<div class=\"dropdown-menu\" style=\"padding: 15px; padding-bottom: 0px;\">";
        echo "<a href=\"" . $profileLink . "\">";
        echo "<div class=\"center_image_dropdown\"><img src='" . $pictureUrl . "' /></div>";
        echo "</a>";
        echo "<b>" . $session['user']['first_name'] . " " . $session['user']['last_name'] . "</b><br/><br/>";
        echo "<a href=\"change_password.php\">Change Password</a><br/>";
        echo "<a href=\"delete_account.php\">Delete Account</a><br/><br/>";
        echo "<a href=\"upload_photo.php\">Upload Photo</a><br/><br/>";
        echo "</div>";
        echo "</li>";
    }

}
