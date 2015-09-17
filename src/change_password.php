<?php

    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    $changer = new ChangePassword();
    $user = $_SESSION['user'];

    if(empty($_SESSION['user'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php");
    } else if (!empty($_POST) && $changer->checkFieldsCorrect($_POST)) {
        $query = "
                    SELECT *
                    FROM user
                    WHERE
                        email = :email
                ";
        $query_params = array(
            ':email' => $user['email']
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        } catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();
        if ($row) {
            $check_password = PasswordUtils::hashPassword($_POST['current_password'], $row['salt']);
            
            if (PasswordUtils::checkMatchingPasswords($check_password, $row['password'])) {
                $changer->errorMessage = PasswordUtils::testPassword($_POST['new_password']);
                if(empty($changer->errorMessage)) {
                    $changer->makePasswordChange($db, $_POST['new_password'], $row['salt'], $row['_id']);
                    $changer->success = "Password changed successfully.";
                }
            } else {
                $changer->errorMessage = "Incorrect password.";
            }
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <style>.error {color: #FF0000;}</style>
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
                <span class="mdl-layout-title">Change Password</span>
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
                    <form action="change_password.php" method="post">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="current_password" name="current_password" />
                            <label class="mdl-textfield__label" for="current_password">Current Password...</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="new_password" name="new_password" />
                            <label class="mdl-textfield__label" for="new_password">New Password...</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="confirm_password" name="confirm_password" />
                            <label class="mdl-textfield__label" for="confirm_password">Confirm New Password...</label>
                        </div><br/>
                        <span class="error"><?php echo $changer->errorMessage;?></span>
                        <span class="success"><?php echo $changer->success;?></span><br/>
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Change Password
                        </button>
                    </form>
                </div>
              </div>
            </section>
            <br/>
        </main>
    </div>
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>
</html>
