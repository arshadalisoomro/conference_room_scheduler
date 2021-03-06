<?php 
    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');
    
    require("config.php");
    
    if(empty($_SESSION['user'])) {
        header("Location: ../index.php");
        die("Redirecting to index.php"); 
    }

    $query = "SELECT feedback
                FROM feedback
                WHERE reservation_id = :id";

    $query_params = array(
        ':id' => $_GET['reservation_id']
    );

    try {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    } catch(PDOException $ex) {
        die("Failed to run query: " . $ex->getMessage());
    }

    $feedback = $stmt->fetch();
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
                <span class="mdl-layout-title">Provide Feedback</span>
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
                    <form action="insert_feedback.php" method="post">
                        <input type="hidden" value="<?php echo $_GET['reservation_id']; ?>" name="reservation_id" />
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield">
                            <input class="mdl-textfield__input" value="<?php echo $feedback['feedback']; ?>" type="text" id="feedback" name="feedback" required/>
                            <label class="mdl-textfield__label" for="feedback">Feedback</label>
                        </div><br/>
                        <br/> 
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Submit Feedback
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
