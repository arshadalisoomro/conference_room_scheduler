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
                <form class="navbar-search pull-left" action="search.php" method="GET" >
                    <input type="text" class="search-query" name="search" placeholder="<?php echo $_GET['search'] ?>" >
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
 <?php
    //include the S3 class              
    if (!class_exists('S3'))require_once('S3.php');
 
    //AWS access info
    if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJQX5I545NDU35UBA');
    if (!defined('awsSecretKey')) define('awsSecretKey', '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');
 
    //instantiate the class
    $s3 = new S3(awsAccessKey, awsSecretKey);
    //check whether a form was submitted
    if(isset($_POST['Submit'])){
        //retreive post variables
        $fileName = $_FILES['theFile']['name'];
        $fileTempName = $_FILES['theFile']['tmp_name'];
        //create a new bucket
        $result = $s3->putBucket("walphotobucket", S3::ACL_PUBLIC_READ);
        //move the file

        if ($s3->putObjectFile($fileTempName, "walphotobucket", $fileName, S3::ACL_PUBLIC_READ)) {
            echo "We successfully uploaded your file.";

            $image_url = "http://walphotobucket.s3.amazonaws.com/" . $fileName;

            $query = "
            UPDATE users
            SET
                picture_url = :url
            WHERE
                id = :id
        ";

            $query_params = array(
                ':url' => $image_url,
                ':id' => $_SESSION['user']['id']
            );

            try {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            } catch(PDOException $ex) {
                die("Failed to run query: " . $ex->getMessage());
            }
        }else{
            echo "Something went wrong while uploading your file... sorry.";
        }
    }
?>   
<h1>Upload a file</h1>
<p>Please select a file by clicking the 'Browse' button and press 'Upload' to start uploading your file.</p>
    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <input name="theFile" type="file" />
      <input name="Submit" type="submit" value="Upload">
    </form>

</div>

</body>
</html>
