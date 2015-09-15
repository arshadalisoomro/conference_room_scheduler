<?php

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    if(empty($_SESSION['user'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
    } else {
        switch($_SESSION['user']['user_type_id']) {
                            case 3: // nurse
                                $userType = "nurse";
                                break;
                            case 2: // doctor
                                $userType = "doctor";
                                break;
                            case 4: // admin
                                $userType = "administrator";
                                break;
                            default:
                                $userType = "patient";
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

          <?php
          $searchText = "";
          $showAdvanced = false;
          if ($userType == "patient") {
              $searchText = "Search All Doctors";
              $showAdvanced = true;
          } else {
              $searchText = "Search All Users";
          }
          ?>

          <form class="navbar-search pull-left" action="search.php" method="GET" >
              <input type="text" class="search-query" name="search" placeholder="<?php echo $searchText ?>" >
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
        if ($userType == "patient") {
            echo "<a href=\"advanced_doctor_search.php\">Advanced Doctor Search</a><br/>";
            echo "<a href=\"schedule_appointment.php\">Schedule an Appointment</a><br/>";
            echo "<a href=\"view_bills.php\">View Bills</a><br/>";   
            echo "<a href=\"view_appointments.php\">View Current Appointments Scheduled</a>";
        } else if ($userType == "doctor") {
            echo "<a href=\"advanced_user_search.php\">Advanced User Search</a><br/>";
            echo "<a href=\"view_checks.php\">View checks</a><br/>";          
            echo "<a href=\"view_appointments.php\">View Current Appointments Scheduled</a>";
        } else if ($userType == "administrator") {
            echo "<a href=\"release_bills.php\">Release Bills</a><br/>";
            echo "<a href=\"release_checks.php\">Release Checks</a><br/>";
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
                                echo $row['type_name'];
                            }
                    ?>

</div>

</body>
</html>
