<?php

include_once('../AutoLoader.php');
AutoLoader::registerDirectory('../src/classes');

require("config.php");

if(empty($_SESSION['user'])) {
    header("Location: ../index.php");
    die("Redirecting to index.php");
} else if (isset($_GET['to_delete_id'])) {
    $query = "
        DELETE
        FROM user
        WHERE
          id = :id
    ";
    $query_params = array(
        ':id' => $_GET['to_delete_id']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }

    header("Location: home.php");
    die("Redirecting to home.php");
} else {

    $query = "
        SELECT *
        FROM user
        WHERE
          _id = :id
    ";
    $query_params = array(
        ':id' => $_GET['id']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);

        $row = $stmt->fetch();
        if ($row) {
            $userProfile = $row;
        }

    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <style>.error {color: #FF0000;}</style>
    <style>.success {color: #00FF00;}</style>
    <meta charset="utf-8">
    <title>Conference Room</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Profile</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <?php AccountDropdownBuilder::buildDropdown($db, $_SESSION) ?>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/>
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div class="mdl-card__supporting-text">
                    <h1><?php echo $userProfile['first_name'] . " " . $userProfile['last_name'] ?></h1> <br/>
                    <?php echo "<b>Email:</b> " . $userProfile['email'] . "<br/>"; ?>
                    <br><b>User Type: </b>
                    <?php
                        $query = "
                                SELECT *
                                FROM user_type
                                WHERE
                                    _id = :id
                                ";
                        $query_params = array(
                            ':id' => $_SESSION['user']['user_type_id']
                        );

                        try {
                            $stmt = $db->prepare($query);
                            $result = $stmt->execute($query_params);
                        } catch(PDOException $ex) {
                            die("Failed to run query: " . $ex->getMessage());
                        }

                        $row = $stmt->fetch();
                        if ($row) {
                            echo $row['description'];
                        }
                    ?>
                </div>
              </div>
            </section>
            <br/>
        </main>
    </div>
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>

</html>
