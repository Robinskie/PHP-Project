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
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <p class="gebruiker"><?php echo $uploadUser->getFullName(); ?></p>
                    <p class="photoDate"><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p><br>
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="250px" height="250px"> 
                </a>
            </div>
        
    <?php endforeach; ?>

    
 
</div>


</body>
</html>