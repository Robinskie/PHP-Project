<?php

    require_once 'bootstrap.php';

    redirectIfLoggedOut();

    if (!empty($_GET)) {
        if (isset($_GET['search'])) {
            $foundPhotos = Search::searchPhotos($_GET['search']);
            $foundProfiles = Search::searchProfiles($_GET['search']);
        }

        if (isset($_GET['color'])) {
            $foundPhotos = Search::searchPhotosOnColor('#'.$_GET['color']);
        }

        if (isset($_GET['tag'])) {
            $foundPhotos = Search::searchPhotosByTags('#'.$_GET['tag']);
            $userId = $_SESSION['userid'];

            $thisTag = $_GET['tag'];

            $tag = new Tag();
            $tag->setTagName($thisTag);
            $isTagFollowed = $tag->getFollowState($userId);
        }

        if (isset($_GET['lat'])) {
            $lat = $_GET['lat'];
            $lon = $_GET['lon'];
            $foundPhotos = Search::searchPhotosByLocation($lat, $lon, 10);
        }
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
    <h1>Search results</h1>

    <?php if (isset($_GET['tag'])) {
    if ($isTagFollowed) {
        ?>
            <a href="#" id="followButton" class="followButton" data-id="<?php echo $tag->getTagName(); ?>" data-followed=1>Unfollow</a>
        <?php
    } else {
        ?>
            <a href="#" id="followButton" class="followButton" data-id="<?php echo $tag->getTagName(); ?>" data-followed=0>Follow</a>
        <?php
    }
} ?>

    <div class="content">

    <?php if (!empty($foundProfiles)) {
    foreach ($foundProfiles as $profile) {
        $user = new User();
        $user->setId($profile['id']);
        $user->setData(); ?>
        <div class="foundProfile">
            <a href="profile.php?id=<?php echo $profile['id']; ?>">
                <img src="<?php echo $user->getAvatar(); ?>" alt="Profile picture">
                <div class="foundProfileInfo">
                    <h2><?php echo $profile['firstName'].' '.$profile['lastName']; ?></h2>
                    <p>Followers: <?php echo $user->getFollowersCount($profile['id']); ?></p>
                    <p>Posts: <?php echo $user->getUserPostsCount($profile['id']); ?></p>
                </div>
            </a>
         </div>
        <?php
    } ?>


    <?php
} ?>

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

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

<script src="js/followTag.js"></script>
</body>
</html>