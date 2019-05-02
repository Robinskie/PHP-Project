<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();

    if (!empty($_GET)) {
        $foundPhotos = Db::searchPhotosOnColor('#'.$_GET['color']);
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
    <?php include_once 'includes/nav.inc.php'; ?>
    
    <h1>Search color results</h1>
    <div class="content">
    <!-- zoekresultaten op basis van tags/zoekwoorden -->

<!-- de resultaten toon je in een feed --> 
<!-- klik je op een resultaat, dan krijg je de detailpagina te zien met commentaren -->
<?php foreach ($foundPhotos as $foundPhoto):

            $photo = new Photo();
            $photo->setId($foundPhoto['photoId']);
            $photo->setData();

            $uploadUser = $photo->getUploaderObject();
            $likeCount = $photo->getLikeCount();
            ?>
        
            <div class="photoBox">
            <a href="photo.php?id=<?php echo $photo->getId(); ?>">
            <h3><?php echo $photo->getName(); ?></h3>
            <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="300px"> 
            <p><i><?php echo $uploadUser->getFullName(); ?></i></p>
            <p class="photoDate"><?php echo howLongAgo($photo->getUploadDate()); ?></p>
            <p><span class="likeCount"><?php echo $likeCount; ?></span> people like this</p>
                    
            </a>
            </div>
        
    <?php endforeach; ?>
 
</div>


</body>
</html>