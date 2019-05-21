<?php
    require_once '../bootstrap.php';

    if (!empty($_POST)) {
        $photoId = $_POST['photoId'];
        $userId = $_SESSION['userid'];
        $isReported = $_POST['isReported'];

        // uitleggen
        if ($isReported == 0) {
            $report = new Report();
            $report->setPhotoId($photoId);
            $report->setUserId($userId);
            $report->reportPicture();

        // uitleggen
        } elseif ($isReported == 1) {
            $report = new Report();
            $report->setPhotoId($photoId);
            $report->setUserId($userId);
            $report->takeBack();
        }

        $result = [
            'status' => 'success',
            'message' => 'Report has been updated',
        ];

        echo json_encode($result);
    }
