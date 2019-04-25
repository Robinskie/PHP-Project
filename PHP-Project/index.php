<?php
    require_once("bootstrap.php");

    redirectIfLoggedOut();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>PROJECT</title>
}

</head>
<body>
    <?php include_once("includes/nav.inc.php");?>
    
    <h1>Homepage</h1>
    <div class="content">

    <!-- zoekformulier maken -->
    <form  method="post" action="search.php?go"  id="searchform"> 
	    <input  type="text" name="name"> 
        <input  type="submit" name="submit" value="Search"> 
    </form>
    <!-- einde formulier -->


    <h2><a href="uploadPhoto.php">Upload a picture</a></h2>
    </div>

  

    <?php
        //FEED
        $photoArray = Db::simpleFetchAll("SELECT * FROM photos ORDER BY uploadDate");
        foreach($photoArray as $photoRow):
            $photo = new Photo();
            $photo->setId($photoRow['id']);
            $photo->setName($photoRow['name']);
            $photoId = $photo->getId();
            $userId = $_SESSION['userid'];

            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT count(*) AS count FROM likes WHERE photo_id = :photoid AND user_id = :userid ");
            $statement->bindParam(":photoid",$photoId);
            $statement->bindParam(":userid",$userId);
            $result = $statement->execute();

            $count = $statement->fetch(PDO::FETCH_ASSOC);

            if($count > 0) {// al geliked
                echo "dees moet geunliked worde";
            } else { // nog ni geliked
                echo "dees moet geliked worden";
            }
    ?>
        
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $photo->getId(); ?>">
                    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" width="300px"> 
                    <p><?php echo $photo->getName(); ?></p>
                </a>
                <div><a href="#" data-id="<?php echo $photo->getId() ?>" data-isLiked="<?php echo $photo->getId() ?>" class="like">Like</a> <span class='likes'><?php echo $photo->getLikes(); ?></span> people like this </div>
            </div>
        
    <?php endforeach ?>

    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

  
<script src="like.js"></script>
    
</body>
</html>