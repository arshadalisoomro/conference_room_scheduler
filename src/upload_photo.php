<?php
    include_once('../AutoLoader.php');
    AutoLoader::registerDirectory('../src/classes');

    require("config.php");

    if(empty($_SESSION['user'])) {
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

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.2/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="../main.css" rel="stylesheet" type="text/css">
</head>

<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Upload Picture</span>
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
                                UPDATE user
                                SET
                                    picture_url = :url
                                WHERE
                                    _id = :id
                            ";

                                $query_params = array(
                                    ':url' => $image_url,
                                    ':id' => $_SESSION['user']['_id']
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
                    <p>Please select a file by clicking the 'Choose File' button and press 'Upload' to start uploading your file.</p>
                    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
                      <input name="theFile" type="file" />
                      <input name="Submit" type="submit" value="Upload">
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
