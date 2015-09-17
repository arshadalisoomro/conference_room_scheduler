<?php

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    if(empty($_SESSION['user'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
    } else {
        switch($_SESSION['user']['user_type_id']) {
                            case 3: // user
                                $userType = "user";
                                break;
                            case 2: // manager
                                $userType = "manager";
                                break;
                            case 1: // admin
                                $userType = "admin";
                                break;
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

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="../assets/bootstrap.min.js"></script>
    <link href="../assets/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../assets/styles.css" rel="stylesheet" type="text/css">
</head>


<body>

<div class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a href="home.php" class="brand">Conference Room Scheduler</a>
      <div class="nav-collapse">
          <form class="navbar-search pull-left" action="search.php" method="GET" >
              <input type="text" class="search-query" name="search" placeholder="Search" >
          </form>
        <ul class="nav pull-right">
            <?php AccountDropdownBuilder::buildDropdown($db, $_SESSION) ?>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h2>Welcome!</h2>
    <?php
        if ($userType == "user") {
            //echo "<a href=\"advanced_doctor_search.php\">Advanced Doctor Search</a><br/>";
            
        } else if ($userType == "manager") {

        } else if ($userType == "admin") {

        }

    ?>
    <br>User Type:      <?php
                            $query = "
                                    SELECT *
                                    FROM user_types
                                    WHERE
                                        id = :id
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

</body>
</html>
