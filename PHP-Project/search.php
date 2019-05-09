<?php

    require_once 'bootstrap.php';
    redirectIfLoggedOut();

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
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <title>PROJECT</title> 

</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?> 
    <h1>Search results</h1>
    <div class="content">

<?php foreach ($foundPhotos as $foundPhoto):

            $photo = new Photo();
            $photo->setId($foundPhoto['id']);
            $photo->setData();

            $uploadUser = $photo->getUploaderObject();
            $likeCount = $photo->getLikeCount();
            ?>
        
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <p class="gebruiker"><?php echo $uploadUser->getFullName(); ?></p>
                    <p class="photoDate"><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p><br>
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" class="<?php echo $photo->getPhotoFilter(); ?>"width="250px" height="250px"> 
                </a>
            </div>
        
    <?php endforeach; ?>


</div>


</body>
</html>