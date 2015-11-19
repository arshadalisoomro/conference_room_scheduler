<?php 
    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');
    
    require("config.php");
    
    if(empty($_SESSION['user']) || empty($_GET['type'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Conference Room</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Create a New User</span>
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
                <div id="content" class="mdl-card__supporting-text">
                    <form action="insert_new_user.php" method="post">
                        <input type="hidden" value="3" name="user_type_id" />
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield">
                            <input class="mdl-textfield__input" type="text" id="first" name="first" required/>
                            <label class="mdl-textfield__label" for="first">First Name</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield">
                            <input class="mdl-textfield__input" type="text" id="last" name="last" required/>
                            <label class="mdl-textfield__label" for="last">Last Name</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield">
                            <input class="mdl-textfield__input" type="text" id="email" name="email" required/>
                            <label class="mdl-textfield__label" for="email">Email</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield">
                            <input class="mdl-textfield__input" type="number" id="manager" name="manager" required/>
                            <label class="mdl-textfield__label" for="manager">Manager ID</label>
                        </div><br/>
                        <br/> 
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Create User
                        </button>
                    </form><br/><br/>
                    This will generate a random password for the user and send them an email informing them of how to log in.
                </div>
              </div>
            </section>
            <br/>
        </main>
    </div>
    
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.min.js"></script>
</body>
</html>
