<?php

    require_once 'bootstrap.php';
    redirectIfLoggedOut();

    // we roepen de functie searchPhotos aan in klasse Search
    if (!empty($_GET)) {
        $foundPhotos = Search::searchPhotos($_GET['search']);
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
    <!-- navigatie toevoegen -->
    <?php include_once 'includes/nav.inc.php'; ?>
    
    <h1>Search results</h1>
    <div class="content">
    <!-- zoekresultaten op basis van tags/zoekwoorden -->

<!-- de resultaten toon je in een feed --> 
<!-- klik je op een resultaat, dan krijg je de detailpagina te zien met commentaren -->
<?php foreach ($foundPhotos as $foundPhoto):

            // object aanmaken
            $photo = new Photo();
            $photo->setId($foundPhoto['id']);
            $photo->setData();

            $uploadUser = $photo->getUploaderObject();
            // we halen de getter uit de klasse photo
            $likeCount = $photo->getLikeCount();
            ?>
        
            <!-- we printen een foto --> 
            <div class="photoBox">
            <!-- we halen de getter uit klasse foto --> 
            <a href="photo.php?id=<?php echo $photo->getId(); ?>">
            <!-- we halen getter uit klasse foto --> 
            <h3><?php echo $photo->getName(); ?></h3>
            <!-- we halen getter uit klasse foto --> 
            <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="300px"> 
            <!-- we halen de naam uit de klasse user --> 
            <p><i><?php echo $uploadUser->getFullName(); ?></i></p>
            <!-- we halen de upload data uit de klasse foto --> 
            <p class="photoDate"><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p>
            <p><span class="likeCount"><?php echo $likeCount; ?></span> people like this</p>
                    
            </a>
            </div>
        
    <?php endforeach; ?>
 
</div>


</body>
</html>