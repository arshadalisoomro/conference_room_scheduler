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

        $profileLink = "http://dbsystems-engproject.rhcloud.com/src/user_page.php?id=" . $session['user']['_id'];

        echo "<a class=\"padding_left_class\" href=\"" . $profileLink . "\">";
        echo "<div class=\"center_image_dropdown\"><img src='" . $pictureUrl . "' /></div>";
        echo "</a>";
        echo "<b class=\"padding_left_class\">" . $session['user']['first_name'] . " " . $session['user']['last_name'] . "</b>";
        echo "<a class=\"mdl-navigation__link\" href=\"home.php\">Home</a>";
        echo "<a class=\"mdl-navigation__link\" href=\"change_password.php\">Change Password</a>";
        echo "<a class=\"mdl-navigation__link\" href=\"upload_photo.php\">Upload Photo</a>";
        echo "<a class=\"mdl-navigation__link\" href=\"logout.php\">Log Out</a>";
    }

}
