<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();

    $userId = $_SESSION['userid'];

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
    <div class="content">

    <h1>Your feed </h1>
    
    <form action="search.php" method="GET">
    <input id="search" name="search" type="text" placeholder="Search">
    <input id="submit" type="submit" value="Search">
    </form> 
    </div>


    <a href="uploadPhoto.php" id="upload">Upload a picture</a>

    <div class="homeFeed">
    <?php
        if (empty($_GET['postLimit'])) {
            $postLimit = 5;
        } else {
            $postLimit = $_GET['postLimit'];
        }

        $photoArray = Db::getUserFeed($userId, $postLimit);

    if (!empty($photoArray)) {
        foreach ($photoArray as $photoRow):
        $photo = new Photo();
        $photo->setId($photoRow['pId']);
        $photo->setData();
        $photoId = $photo->getId();

        $uploadUser = $photo->getUploaderObject();

        $likeCount = $photo->getLikeCount();
        $isLiked = $photo->getLikeState($userId);

        $reportCount = $photo->getReportCount();
        $isReported = $photo->getReportState($userId); ?>
        
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <p class="gebruiker"><?php echo $uploadUser->getFullName(); ?></p>
                    <p class="photoDate"><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p><br>
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="250px" height="250px"> 
                    <p> <?php // echo $photo->getName();?></p>
                    
                    <div class="telaantal">
                    <p><span class="likeCount"><?php echo $likeCount; ?></span> likes
                    <span class="reportCount"><?php echo $reportCount; ?></span> reports</p>
                    </div>

                    <?php if ($isLiked) {
            ?>
                        <a href="#" id="likeButton" class="likeButton" data-id="<?php echo $photo->getId(); ?>" data-liked=1>Unlike</a>
                    <?php
        } else {
            ?>
                        <a href="#" id="likeButton" class="likeButton" data-id="<?php echo $photo->getId(); ?>" data-liked=0>Like</a>
                    <?php
        } ?>
           
                    <?php if ($isReported) {
            ?>
                        <a href="#" id="reportButton" class="reportButton" data-id="<?php echo $photo->getId(); ?>" data-reported=1>Take back</a>
                    <?php
        } else {
            ?>
                        <a href="#" id="reportButton" class="reportButton" data-id="<?php echo $photo->getId(); ?>" data-reported=0>Report</a>
                    <?php
        } ?>
                
                </a>
            </div>
        <?php endforeach;

    // IF USER IS NOT FOLLOWING ANY ACCOUNTS YET
    } else {
        $userId = $_SESSION['userid'];
        $conn = Db::getInstance();
        $randomUserStatement = $conn->prepare("SELECT * FROM users WHERE NOT id = $userId ORDER BY RAND() LIMIT 1");
        $randomUserStatement->execute();
        $randomUser = $randomUserStatement->fetch(PDO::FETCH_ASSOC); ?>

        <p>You're not following anyone yet.<br>
        Perhaps you'll like

        <a href="profile.php?id=<?php echo $randomUser['id']; ?>"><?php echo $randomUser['firstName'].' '.$randomUser['lastName']; ?></a><br></p>

        <?php
            $randomUserPostsStatement = $conn->prepare('SELECT * FROM photos WHERE uploader = :randomUserId LIMIT 3');
        $randomUserPostsStatement->bindParam(':randomUserId', $randomUser['id']);
        $randomUserPostsStatement->execute();

        $randomUserPosts = $randomUserPostsStatement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($randomUserPosts as $randomUserPost):
        ?>

        <a href="photo.php?id=<?php echo $randomUserPost['id']; ?>"><img src="images/photos/<?php echo $randomUserPost['id']; ?>_cropped.png" alt="">

        <?php
            endforeach;
    }
        ?>
        
    </div class="homeFeed">

    <a class="loadMoreButton" id="loadMoreButton" href="index.php?postLimit=<?php echo $postLimit + 5; ?>">Load more...</a>

    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  
<script src="js/like.js"></script>
<script src="js/report.js"></script>
    
</body>
</html>