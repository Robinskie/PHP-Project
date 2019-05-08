<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();

    if (!empty($_GET)) {
        $photo = new Photo();
        $photo->setId($_GET['id']);
        $photo->setData();

        $uploaderUser = new User();
        $uploaderUser->setId($photo->getUploader());
        $uploaderUser->setData();

        $currentUser = new User();
        $currentUser->setId($_SESSION['userid']);
        $currentUser->setData();
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
    <?php include_once 'includes/nav.inc.php'; ?>

    <h1><?php echo $photo->getName(); ?></h1>

    <img src="images/photos/<?php echo $photo->getId(); ?>_cropped.png" class="searchresult" width="250px" height="250px"> 

    <?php if ($photo->getUploader() == $_SESSION['userid']) :?>
        <a href="updatePhoto.php?id=<?php echo $photo->getId(); ?>" >Bewerken</a>
    <?php endif; ?>
    
    <p><strong>Uploaded by: </strong><a href="profile.php?id=<?php echo $uploaderUser->getId(); ?>"><?php echo $uploaderUser->getFirstName().' '.$uploaderUser->getLastName(); ?></a></p>
    <p><strong> Upload date: </strong><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p>
    <p><?php echo $photo->getDescription(); ?></p>
    
    <p><strong>Colors: </strong></p>
        <?php $colorArray = $photo->getColors();
            foreach ($colorArray as $color):?>
                <a href="searchColor.php?color=<?php echo substr($color['color'], strpos($color['color'], '#') + 1); ?>"><div class="colorBall" style="background-color: <?php echo $color['color']; ?>"></div></a>
    <?php endforeach; ?>
    

     <form name="commentForm">
        <div>
            <textarea id="commentText" name="commentText" form="commentText" cols="83" rows="5" style="resize: none"></textarea>
        </div>
        <input id="commentSubmit" data-photoid="<?php echo $photo->getId(); ?>" data-userid="<?php echo $_SESSION['userid']; ?>" type="submit" value="Post comment">
    </form>

     <p><strong>Comments: </strong></p>
    <div id="comments" class="comments">
    <?php
        $commentArray = $photo->getComments();

        foreach ($commentArray as $commentRow):
            $comment = new Comment();
            $comment->setId($commentRow['id']);
            $comment->setData();

            $userRow = Db::simpleFetch('SELECT * FROM users WHERE id = '.$comment->getUserId());
            $commentUser = new User();
            $commentUser->setId($comment->getUserId());
            $commentUser->setData();
        ?>
        
            <div class="commentBox">
                <div class="commentUserBox">
                    <img width="50px" src="<?php echo $commentUser->getAvatar(); ?>">
                    <strong><?php echo $commentUser->getFullName(); ?></strong>
                </div>
                <p><?php echo $comment->getText(); ?></p>
                <p class="commentDate"><?php echo $comment->getDate(); ?></p>
            </div>
        
    <?php endforeach; ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script>

        $("#commentSubmit").on("click", function(e) {
            console.log("hey");
        var photoId = $(this).data("photoid");
        var userId = $(this).data("userid");
        var commentText = $("#commentText").val();

        e.preventDefault();

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
                            "<img width='50px' src='<?php echo $currentUserRow['avatar']; ?>'>" +
                            "<strong><?php echo $currentUser->getFirstName().' '.$currentUser->getLastName(); ?></strong>" +
                        "</div>" +
                        "<p>" + $("#commentText").val() + "</p>" +
                        "<p class='commentDate'><?php echo $comment->getDate(); ?></p>" +
                    "</div>";

                    $("#comments").append(newComment);

                    $("#commentText").val("");
                }
            });
        });
    </script>
</body>
</html>