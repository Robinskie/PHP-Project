<?php
    // POST?
    if( !empty($_POST)){
        //VAR_dump($_POST); ga in u console onder network kijken
        $photoId = $_POST['photoId'];
        $userId = $_SESSION["userid"]; //hier kunt ge u user vanuit u databank meegeven
        $isLiked = $_POST['isLiked'];

        include_once("../bootstrap.php");
        if($isLiked = false) {
            $like = new Like();
            $like->setPhotoId($photoId);
            $like->setUserId($userId);
            $like->save();//staat in classes/like.php
        } else {
            $like = new Like();
            $like->unSave();
        }
        //JSON object
        $result = [
            "status" => "succes",
            "message" => "Like has been saved"
        ];

        echo json_encode($result);
    }