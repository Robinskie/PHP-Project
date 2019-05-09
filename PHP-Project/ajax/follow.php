<?php
    require_once '../bootstrap.php';

    if (!empty($_POST)) {
        $thisUserId = $_POST['thisUserId'];
        $userId = $_SESSION['userid'];
        $isFollowed = $_POST['isFollowed'];

        if ($isFollowed == 0) {
            $follow = new Follow();
            $follow->setThisUserId($thisUserId);
            $follow->setUserId($userId);
            $follow->save();
        } elseif ($isFollowed == 1) {
            $follow = new Follow();
            $follow->setThisUserId($thisUserId);
            $follow->setUserId($userId);
            $follow->unSave();
        }

        $result = [
            'status' => 'success',
            'message' => 'Follow has been updated',
        ];

        echo json_encode($result);
    }
