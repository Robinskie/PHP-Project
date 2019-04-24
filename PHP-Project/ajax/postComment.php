<?php
    include_once("../bootstrap.php");

    // POST?
    if( !empty($_POST)){
        $photoId = $_POST['photoId'];
        $userId = $_POST['userId'];
        $commentText = $_POST['commentText'];
        $date =date('Y-m-d H:i:s');

        try {
            include_once("../bootstrap.php");
            $comment = new Comment();
            $comment->setPhotoId($photoId);
            $comment->setUserId($userId);
            $comment->setText($commentText);
            $comment->setDate($date);
            $comment->save();

            //JSON object
            $result = [
                "status" => "success",
                "message" => "Comment has been saved! :)"
            ];
        
        } catch (Throwable $t) {
            $result = [
                "status" => "error",
                "message" => "Something went wrong."
            ];
        }

        echo json_encode($result);
    }