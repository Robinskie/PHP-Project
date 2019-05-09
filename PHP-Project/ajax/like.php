<?php
    require_once '../bootstrap.php';

    if (!empty($_POST)) {
        $photoId = $_POST['photoId'];
        $userId = $_SESSION['userid'];
        $isLiked = $_POST['isLiked'];

        if ($isLiked == 0) {
            $like = new Like();
            $like->setPhotoId($photoId);
            $like->setUserId($userId);
            $like->save();
        } elseif ($isLiked == 1) {
            $like = new Like();
            $like->setPhotoId($photoId);
            $like->setUserId($userId);
            $like->unSave();
        }

        $result = [
            'status' => 'success',
            'message' => 'Like has been updated',
        ];

        echo json_encode($result);
    }
