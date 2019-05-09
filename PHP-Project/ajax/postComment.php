<?php
    require_once '../bootstrap.php';

    if (!empty($_POST)) {
        $photoId = $_POST['photoId'];
        $userId = $_POST['userId'];
        $commentText = $_POST['commentText'];
        $date = date('Y-m-d H:i:s');

        try {
            $comment = new Comment();
            $comment->setPhotoId($photoId);
            $comment->setUserId($userId);
            $comment->setText($commentText);
            $comment->setDate($date);
            $comment->save();

            $result = [
                'status' => 'success',
                'message' => 'Comment has been saved! :)',
            ];
        } catch (Throwable $t) {
            $result = [
                'status' => 'error',
                'message' => 'Something went wrong.',
            ];
        }

        echo json_encode($result);
    }
