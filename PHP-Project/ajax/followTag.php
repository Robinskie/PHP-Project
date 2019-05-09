<?php
    require_once '../bootstrap.php';

    if (!empty($_POST)) {
        $thisTag = $_POST['thisTag'];
        $userId = $_SESSION['userid'];
        $isTagFollowed = $_POST['isTagFollowed'];

        if ($isTagFollowed == 0) {
            $tag = new Tag();
            $tag->setTagName($thisTag);
            $tag->setUserId($userId);
            $tag->save();
        } elseif ($isTagFollowed == 1) {
            $tag = new Tag();
            $tag->setTagName($thisTag);
            $tag->setUserId($userId);
            $tag->unSave();
        }

        $result = [
            'status' => 'success',
            'message' => 'Follow has been updated',
        ];

        echo json_encode($result);
    }
