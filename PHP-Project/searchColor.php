<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();

    if (!empty($_GET)) {
        $foundPhotos = Search::searchPhotosOnColor('#'.$_GET['color']);
    }

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <title>Search - Zoogram</title>

</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?>
    
    <h1>Search color results</h1>
    <div class="content">

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
            <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" class="<?php echo $photo->getPhotoFilter(); ?>" width="300px"> 
            <p><i><?php echo $uploadUser->getFullName(); ?></i></p>
            <p class="photoDate"><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p>

            <p><span class="likeCount"><?php echo $likeCount; ?></span> people like this</p>
                    
            </a>
            </div>
        
    <?php endforeach; ?>
 
</div>


</body>
</html>