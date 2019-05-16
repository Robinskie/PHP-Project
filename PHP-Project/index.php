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
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <title>Home - Zoogram</title>

<script>
    function showHint(str) {
    if (str.length == 0) { 
    document.getElementById("txtHint").innerHTML = "";
    return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
                var hintArray = this.responseText.split(',');
                console.log(hintArray);  
            }
        };
        xmlhttp.open("GET", "ajax/gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>

</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?>
    <div class="content">

    <h1>Your feed </h1>
    
    <form action="search.php" method="GET">
        <p> Suggestions: <span id="txtHint"></span></p>
        <input id="search" name="search" type="text" placeholder="Search" onkeyup="showHint(this.value)">
        <input id="submit" type="submit" value="Search">
    </form>
    <form action="search.php" method="GET">
        <input id="searchLocation" name="location" type="text" placeholder="Your location">
        <input class="searchLocationButton" id="searchLocationButton" type="submit" value="Search by location">
    </form> 
    </div>

    <a href="uploadPhoto.php" id="upload">Upload a picture</a>

    <div class="homeFeed" id="homeFeed">
    <?php
        if (empty($_GET['postLimit'])) {
            $postLimit = 5;
        } else {
            $postLimit = $_GET['postLimit'];
        }

        $photoArray = Db::getUserFeed($userId, 0, $postLimit);

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
                    <img class="<?php echo $photo->getPhotoFilter(); ?>" src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="250px" height="250px"> 
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
        }

        if ($isReported) {
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
        $randomUser = Db::getRandomOtherUser($userId); ?>

        <p>You're not following anyone yet.<br>
        Perhaps you'll like

        <a href="profile.php?id=<?php echo $randomUser->getId(); ?>"><?php echo $randomUser->getFullName(); ?></a><br></p>

        <?php
        $randomUserPosts = $randomUser->getUserPosts($randomUser->getId());

        foreach ($randomUserPosts as $randomUserPost):
        ?>

        <a href="photo.php?id=<?php echo $randomUserPost['id']; ?>"><img src="images/photos/<?php echo $randomUserPost['id']; ?>_cropped.png" alt="">

        <?php
            endforeach;
    }
        ?>
        
    </div class="homeFeed">

    <a class="loadMoreButton" id="loadMoreButton" href="#" data-user="<?php echo $userId; ?>" data-loadedposts="5" >Load more...</a>

  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  
<script src="js/like.js"></script>
<script src="js/report.js"></script>
<script src="js/loadMore.js"></script>

<script>
    addLikeListeners();
    addReportListeners();

    //load more
    $("#loadMoreButton").on('click', function(e) {
        e.preventDefault();

        var btn = $(this);

        var user = btn.data("user");
        var loadedPosts = btn.data("loadedposts");

        var content = document.getElementById("homeFeed");

        $.ajax({
                method: "GET",
                url: "ajax/getFeed.php", 
                data: { 
                    from: loadedPosts,
                    to: loadedPosts + 5,
                    user: user
                },
                    dataType: "JSON" 
                }).done(function(res) {
                    console.log(res);
                    res['photos'].forEach(function(photo) {
                        content.innerHTML += getPhotoHTML(photo);
                    });
                    
                    addLikeListeners();
                    addReportListeners();
                    
                    btn.data("loadedposts", loadedPosts + 5);
                });
    });

    //location search
    $("#searchLocationButton").on('click', function(e) {
        e.preventDefault();

        var location = $("#searchLocation").val();

        if(location != "") {
            $.ajax({
                method: "GET",
                url: "https://nominatim.openstreetmap.org/search", 
                data: { 
                    q: location,
                    limit: 1,
                    format: 'json'
                },
                    dataType: "JSON" 
                }).done(function(res) {
                    var lat = res[0]['lat'];
                    var lon = res[0]['lon'];
                    window.location.href = "search.php?lat=" + lat + "&lon=" + lon;
                });
        } else {
            navigator.geolocation.getCurrentPosition(function(position) {
                window.location.href = "search.php?lat=" + position.coords.latitude + "&lon=" + position.coords.longitude;
            });
        }
    });
</script>
    
</body>
</html>