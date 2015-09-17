<?php 
    $debug = false;
    $message = "";

    include_once('AutoLoader.php');
    AutoLoader::registerDirectory('src/classes');
    
    require("src/config.php");
    
    if(!empty($_POST)) {
      $email = htmlspecialchars($_POST['email']);

      $query = "
            SELECT *
            FROM user
            WHERE
                email = :email
        ";
        $query_params = array(
            ':email' => $email
        );

        try {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        } catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }

        $row = $stmt->fetch();
        if ($row) {
            $check_password = PasswordUtils::hashPassword($_POST['password'], $row['password_salt']);
            
            if($check_password == $row['password']) {
                unset($row['salt']);
                unset($row['password']);

                $_SESSION['user'] = $row;

                header("Location: src/home.php");
                die("Redirecting to: home.php");
            } else {
                $message = "Invalid Password.";
            }
        } else {
            $message = "The email address is not registered.";
        }  
    } 
?>

<!doctype html>
<html lang="en">
<head>
    <style>
        #content {
            text-align: center;
        }
        #forgot_password {
            margin-top: 10px;
            margin-bottom: 8px;
        }
    </style>
    <meta charset="utf-8">
    <title>Conference Room</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="main.css" rel="stylesheet" type="text/css">
</head>

<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Login</span>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Scheduler</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="src/register.php">Register as Administrator</a>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <br/>
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
              <div class="mdl-card mdl-cell mdl-cell--12-col">
                <div id="content" class="mdl-card__supporting-text">
                    <form action="index.php" method="post">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="email" name="email" />
                            <label class="mdl-textfield__label" for="email">Email...</label>
                        </div><br/>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="password" name="password" />
                            <label class="mdl-textfield__label" for="password">Password...</label>
                        </div><br/>
                        <span class="error"><?php echo $message;?></span>
                        <br/> 
                        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Login
                        </button>
                    </form> 
                    <form action="src/forgot_password.php" method="post">
                        <button id="forgot_password" type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                          Forgot Password
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
