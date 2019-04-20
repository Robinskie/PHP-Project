<?php
    // POST?
    if( !empty($_POST)){
        //VAR_dump($_POST); ga in u console onder network kijken
        $postId = $_POST['postId'];
        $userId = 1; //$_SESSION["userId"]; //hier kunt ge u user vanuit u databank meegeven

        include_once("../bootstrap.php");
        $like = new Like();
        $like->setPostId($postId);
        $like->setUserId($userId);
        $like->save();//staat in classes/like.php

        //JSON object
        $result = [
            "status" => "succes",
            "message" => "Like has been saved"
        ];

        echo json_encode($result);
    }