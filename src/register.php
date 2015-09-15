

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
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <li><a href="../index.php">Login</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h1>Register</h1> <br/><br/>
    <form action="register.php" method="post">
        User type:<br/>
        <select name="user_type_id">
            
        </select><br/>
        Access Code (not applicable for patients):<br/>
        <input type="text" name="access_code"  />
        <span class="error"></span><br/>
        Email:<br/>
        <input type="text" name="email"  />
        <span class="error"> * </span><br/>
        Password:<br/>
        <input type="password" name="password" value="" />
        <span class="error"> * </span><br/>
        Confirm Password:<br/>
        <input type="password" name="confirmPassword" value="" />
        <span class="error"> * </span><br/>
        <span class="error"></span><br/>
        <span class="success"></span>
        <span class="error"></span>
        <input type="submit" class="btn btn-info" value="Register" /><br/><br/>
        <p>Password must have at least one number and letter, and must be 20 characters long or fewer.</p> 
    </form>
</div>

</body>
</html>
