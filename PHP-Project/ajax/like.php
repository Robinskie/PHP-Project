<?php
    require_once("../bootstrap.php");
    // POST?
    if( !empty($_POST)){
        //var_dump($_POST); //ga in u console onder network kijken
        $photoId = $_POST['photoId'];
        $userId = $_SESSION['userid']; //hier kunt ge u user vanuit u databank meegeven
        $isLiked = $_POST['isLiked'];

        if($isLiked == "false") {
            $like = new Like();
            $like->setPhotoId($photoId);
            $like->setUserId($userId);
            $like->save();//staat in classes/like.php
        } else {
            $like = new Like();
            $like->setPhotoId($photoId);
            $like->setUserId($userId);
            $like->unSave();
        }
        //JSON object
        $result = [
            "status" => "success",
            "message" => "Like has been updated"
        ];

        echo json_encode($result);
    }