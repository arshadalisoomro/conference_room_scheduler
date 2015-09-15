

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
        <ul class="nav pull-right">
            <li><a href="../index.php">Login</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container hero-unit">
    <h1>Password Retrieval</h1> <br />
    <form action="forgot_password.php" method="post" id="mainForm">
        <label>Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'])?>" onblur="update()" /><br/>
        <span class="error"><?php echo $fp->noEmail;?></span><br/>
        <?php
            if(!empty($_POST['email'])) {
                $entry = $fp->checkEmail($_POST['email'], $db);
                if ($entry != NULL && $entry['challenge_question_answer'] != NULL) {
                    $query = "
                        SELECT *
                        FROM challenge_question
                        WHERE
                            id = " . $entry['challenge_question_id'];
                        try {
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                        } catch(PDOException $ex) {
                            die("Failed to run query: " . $ex->getMessage());
                        }
                        $row = $stmt->fetch();
                        echo "Challenge question:<br/><br/>";
                        echo "<label>" . $row['question'] . "</label>";
                        echo '<input type="password" name="challenge_question_answer" "value="' . htmlspecialchars($_POST['challenge_question_answer']) . '"/><br/><br/>';
                        echo '<span class="error">' . $fp->wrongAnswer . '</span><br/>';
                }
                echo '<input type="submit" class="btn btn-info" value="Retrieve Password" /><br/><br/>';
            }
        ?>
        <span class = "success"><?php echo $fp->success;?></span>
        <span class = "error"><?php echo $fp->regisrationFailure;?></span>
        <script>
        function update() {
            document.getElementById("mainForm").submit();  
        }
        </script>
    </form>
</div>

</body>
</html>
