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
            $photoId = $photo->getId();
            $userId = $_SESSION['userid'];

            $uploadUser = new User();
            $uploadUser->setFirstName($photoRow['firstName']);
            $uploadUser->setLastName($photoRow['lastName']);

            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT count(*) AS count FROM likes WHERE photo_id = :photoid AND user_id = :userid ");
            $statement->bindParam(":photoid",$photoId);
            $statement->bindParam(":userid",$userId);
            $result = $statement->execute();

            $count = $statement->fetch(PDO::FETCH_ASSOC);

            if($count > 0) {// al geliked
                //echo "dees moet geunliked worde";
            } else { // nog ni geliked
                //echo "dees moet geliked worden";
            }
    ?>
        
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="300px"> 
                    <p><?php echo $photo->getName(); ?></p>
                    <p><i><?php echo $uploadUser->getFirstName() . " " . $uploadUser->getLastName(); ?></i></p>
                </a>
                <div><a href="#" data-id="<?php echo $photo->getId() ?>" data-isLiked="<?php echo $photo->getId() ?>" class="like">Like</a> <span class='likes'><?php echo $photo->getLikes(); ?></span> people like this </div>
            </div>
        
    <?php endforeach ?>
    </div class="homeFeed">

    <a class="loadMoreButton" id="loadMoreButton" href="index.php?postLimit=<?php echo $postLimit + 5;?>">Load more...</a>

    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

  
<script src="like.js"></script>
    
</body>
</html>