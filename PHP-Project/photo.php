<?php
    include_once("bootstrap.php");

    //check URL voor welke foto het is
    if(!empty($_GET)) {
        //photo-object maken om u gegevens in te proppen
        $photo = new Photo();
        $photo->setId($_GET['id']);
        
        //gegevens ophalen
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM photos WHERE id = :id");
        $statement->bindValue(":id", $photo->getId());
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $photo->setName($result['name']);
        $photo->setUploader($result['uploader']);
        $photo->setUploadDate($result['uploadDate']);
        $photo->setDescription($result['description']);
        $photo->setUploadDate($result['uploadDate']);
        $photo->setTags($result['tags']);

        //zelfde voor uploader
        $uploaderUser = new User();
        $uploaderUserRow = Db::simpleFetch("SELECT * FROM users WHERE id = " . $photo->getUploader());
        $uploaderUser->setFirstName($uploaderUserRow['firstName']);
        $uploaderUser->setLastName($uploaderUserRow['lastName']);

        $currentUser = new User();
        $currentUserRow = Db::simpleFetch("SELECT * FROM users WHERE id = " . $_SESSION['userid']);
        $currentUser->setFirstName($currentUserRow['firstName']);
        $currentUser->setLastName($currentUserRow['lastName']);
        $currentUser->setAvatar($currentUserRow['avatar']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>PROJECT - PHOTO</title>
</head>
<body>
    <!--photo weergeven met nodige info-->
    <h2><?php echo $photo->getName();?></h2>

    <!--foto is een link naar de vergrootte weergave-->
    <a href="<?php echo $photo->getPhotoPath();?>"><img src="<?php echo $photo->getCroppedPhotoPath();?>"></a>
    
    <p><strong>Uploaded by: </strong><?php echo $uploaderUser->getFirstName() . " " . $uploaderUser->getLastName();?></p>
    <p><strong> Upload date: </strong><?php echo $photo->getUploadDate();?></p>
    <p><?php echo $photo->getDescription();?></p>
    <p><strong>Tags: </strong><?php echo $photo->getTags();?></p>

    <!--comment form-->
    <form name="commentForm">
        <div>
            <textarea id="commentText" name="commentText" form="commentText" cols="83" rows="5" style="resize: none"></textarea>
        </div>
        <input id="commentSubmit" data-photoid="<?php echo $photo->getId();?>" data-userid="<?php echo $_SESSION['userid'];?>" type="submit" value="Post comment">
    </form>

    <!--alle comments printen-->
    <p><strong>Comments: </strong></p>
    <div id="comments" class="comments">
    <?php
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM comments WHERE photoId = :photoId ORDER BY date"); 
        $photoId = $photo->getId();
        $statement->bindParam(":photoId", $photoId); 
        $result = $statement->execute();
        $commentArray = $statement->fetchAll(PDO::FETCH_ASSOC);

        

        foreach($commentArray as $commentRow):
            //comment object maken
            $comment = new Comment();
            $comment->setPhotoId($commentRow['photoId']);
            $comment->setUserId($commentRow['userId']);
            $comment->setText($commentRow['text']);
            $comment->setDate($commentRow['date']);

            //user die de comment geplaatst heeft ophalen
            $userRow = Db::simpleFetch("SELECT * FROM users WHERE id = " . $comment->getUserId());
            $user = new User();
            $user->setFirstName($userRow['firstName']);
            $user->setLastName($userRow['lastName']);
    ?>
        
            <div class="commentBox">
                <div class="commentUserBox">
                    <img width="50px" src="<?php echo $userRow['avatar'];?>">
                    <strong><?php echo $user->getFirstName() . " " . $user->getLastName();?></strong>
                </div>
                <p><?php echo $comment->getText();?></p>
                <p class="commentDate"><?php echo $comment->getDate();?></p>
            </div>
        
    <?php endforeach ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script>
        $("#commentSubmit").on("click", function(e) {
        var photoId = $(this).data("photoid");
        var userId = $(this).data("userid");
        var commentText = $("#commentText").val();

        $.ajax({
            method: "POST",
            url: "ajax/postComment.php", 
            data: { 
                photoId: photoId,
                userId: userId,
                commentText: commentText
            },
                dataType: "JSON" 
            }).done(function(res) {
                if(res.status == 'success') {
                    var newComment = "<div class='commentBox'>" +
                        "<div class='commentUserBox'>" +
                            "<img width='50px' src='<?php echo $currentUserRow['avatar'];?>'>" +
                            "<strong><?php echo $currentUser->getFirstName() . " " . $currentUser->getLastName();?></strong>" +
                        "</div>" +
                        "<p>" + $("#commentText").val() + "</p>" +
                        "<p class='commentDate'><?php echo $comment->getDate();?></p>" +
                    "</div>";

                    $("#comments").append(newComment);

                    $("#commentText").val("");
                }
            });

            e.preventDefault();
        });
    </script>
</body>
</html>