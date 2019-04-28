<?php
    require_once("bootstrap.php");
    redirectIfLoggedOut();

    // als zoekveld niet leeg is
    if(!empty($_POST)){
            
    // databank connectie
    $conn = Db::getInstance();
                                                            
    // input gelijk aan wat in tekstveld ingegeven is
    $input = $_POST['searchbutton'];
                                                           
    // query voor posts te zoeken
    $statement = $conn->prepare("SELECT * FROM photos WHERE 'description' LIKE '%$input%'");
    $result = $statement->execute();
    $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
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
    
    <h1>Homepage</h1>
    <div class="content">

    <!-- zoekformulier maken -->
    <form  method="post" action="search.php" id="searchform"> 
	    <input  type="text" name="searchbutton"> 
        <input  type="submit" name="searchbutton" value="Search"> 
    </form>
    <!-- einde formulier -->


    <h2><a href="uploadPhoto.php">Upload a picture</a></h2>
    </div>

    <div class="homeFeed">
    <?php
        //FEED
        if(empty($_GET['postLimit'])) {
            $postLimit = 5;
        } else {
            $postLimit = $_GET['postLimit'];
        }

        $conn = Db::getInstance();
        $statement = $conn->prepare(
            "SELECT *, photos.id AS pId, users.id AS uId FROM photos 
            LEFT JOIN users ON photos.uploader = users.id 
            RIGHT JOIN followers ON followers.followedUser = photos.uploader
            WHERE followers.followingUser = :currentUser
            ORDER BY uploadDate DESC
            LIMIT " . $postLimit);
        $statement->bindParam(":currentUser", $_SESSION['userid']);
        $result = $statement->execute();
        $photoArray = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($photoArray as $photoRow):
            $photo = new Photo();
            $photo->setId($photoRow['pId']);
            $photo->setName($photoRow['name']);
            $photo->setUploadDate($photoRow['uploadDate']);
            $photoId = $photo->getId();
            $userId = $_SESSION['userid'];

            $uploadUser = new User();
            $uploadUser->setFirstName($photoRow['firstName']);
            $uploadUser->setLastName($photoRow['lastName']);

            $likeCount = Db::simpleFetch("SELECT count(*) AS count FROM likes WHERE photo_id=" . $photo->getId())['count'];
            $isLiked = Db::simpleFetch("SELECT count(*) AS count FROM likes WHERE photo_id=" . $photo->getId() . " AND user_id=" . $_SESSION['userid'])['count'];
    ?>
        
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <h3><?php echo $photo->getName(); ?></h3>
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="300px"> 
                    <p><i><?php echo $uploadUser->getFirstName() . " " . $uploadUser->getLastName(); ?></i></p>
                    <p class="photoDate"><?php echo howLongAgo($photo->getUploadDate()); ?></p>
                    <p><span class="likeCount"><?php echo $likeCount;?></span> people like this</p>
                    <?php if($isLiked) { ?>
                        <a href="#" id="likeButton" class="likeButton" data-id="<?php echo $photo->getId();?>" data-liked=1>Unlike</a>
                    <?php } else { ?>
                        <a href="#" id="likeButton" class="likeButton" data-id="<?php echo $photo->getId();?>" data-liked=0>Like</a>
                    <?php } ?>
                </a>
            </div>
        
    <?php endforeach ?>
    </div class="homeFeed">

    <a class="loadMoreButton" id="loadMoreButton" href="index.php?postLimit=<?php echo $postLimit + 5;?>">Load more...</a>

    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

  
<script src="js/like.js"></script>
    
</body>
</html>