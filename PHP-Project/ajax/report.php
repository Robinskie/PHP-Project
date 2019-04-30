<?php
    require_once("../bootstrap.php");
    
    if( !empty($_POST)){
         
        $photoId = $_POST['photoId'];
        $userId = $_SESSION['userid']; 
        $isReported = $_POST['isReported'];

        if($isReported == 0) {
            $report = new Report();
            $report->setPhotoId($photoId);
            $report->setUserId($userId);
            $report->report(); 
        } else if($isReported == 1) {
            $report = new Report();
            $report->setPhotoId($photoId);
            $report->setUserId($userId);
            $report->takeBack();
        }
        //JSON OBJECT
        $result = [
            "status" => "success",
            "message" => "Report has been updated"
        ];

        echo json_encode($result);
    }