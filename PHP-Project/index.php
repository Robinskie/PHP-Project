<?php
    require_once("bootstrap.php");

    redirectIfLoggedOut();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PROJECT</title>
</head>
<body>
    <?php include_once("includes/nav.inc.php");?>
    
    <h1>Homepage</h1>
    <div class="content">
        <h2><a href="uploadPhoto.php">Upload a picture</h2>
    </div>
</body>
</html>