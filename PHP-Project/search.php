<?php
    require_once("bootstrap.php");
    redirectIfLoggedOut();

    if(!empty($_GET)) {
        $foundPhotos = Db::searchPhotos($_GET['search']);
    }
    
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>PROJECT</title>

</head>
<body>
    <?php include_once("includes/nav.inc.php");?>
    
    <h1>Zoekresultaten </h1>
    <div class="content">
    <!-- zoekresultaten op basis van tags/zoekwoorden -->

<?php
    foreach ($foundPhotos as $foundPhoto) {
        $photo = new Photo();
        $photo->setId($foundPhoto['id']);
        $photo->setData();
        echo $photo->getName() . "<br>";
    }
?>

</div>


</body>
</html>