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
    <style>.error {color: #FF0000;}</style>
    <style>.success {color: #00FF00;}</style>
    <meta charset="utf-8">
    <title>Conference Room</title>
    <meta name="description" content="Conference room management system for Database Systems">
    <meta name="author" content="Team 6">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" type="text/css">
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
      <a href="src/home.php" class="brand">Conference Room Scheduler</a>
      <div class="nav-collapse collapse">
        <ul class="nav pull-right">
          <li><a href="src/register.php">Register Administrator</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h1>Login</h1> <br />
    <form action="index.php" method="post">
        Email:<br/>
        <input type="text" name="email" value="<?php echo $email?>" /><br/>
        Password:<br/>
        <input type="password" name="password" value="" /><br/>
        <span class="error"><?php echo $message;?></span>
        <br/> 
        <input type="submit" class="btn btn-info" value="Login" />
    </form> 
    <a href="src/forgot_password.php">Forgot Password?</a>
</div>

</body>
</html>
