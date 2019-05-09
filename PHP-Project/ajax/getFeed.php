<?php
    require_once '../bootstrap.php';

    if (!empty($_GET)) {
        $user = $_GET['user'];
        $from = $_GET['from'];
        $to = $_GET['to'];

        try {
            $result = [
                'status' => 'success',
                'message' => 'Photos have been returned! :)',
            ];
            $photoCount = 0;
            $feed = Db::getUserFeed($user, $from, $to);
            foreach ($feed as $row) {
                $photo = new Photo();
                $photo->setId($row['pId']);
                $photo->setData();
                $uploader = new User();
                $uploader->setId($photo->getUploader());
                $uploader->setData();

                $result['photos'][$photoCount]['id'] = $photo->getId();
                $result['photos'][$photoCount]['name'] = $photo->getName();
                $result['photos'][$photoCount]['croppedPhoto'] = $photo->getCroppedPhotoPath();
                $result['photos'][$photoCount]['filter'] = $photo->getPhotoFilter();
                $result['photos'][$photoCount]['uploadDate'] = $photo->getUploadDate();
                $result['photos'][$photoCount]['uploader'] = $uploader->getFullname();
                $result['photos'][$photoCount]['likeCount'] = $photo->getLikeCount();
                $result['photos'][$photoCount]['likeState'] = $photo->getLikeState($user);
                $result['photos'][$photoCount]['reportCount'] = $photo->getReportCount();
                $result['photos'][$photoCount]['reportState'] = $photo->getReportState($user);
                ++$photoCount;
            }
        } catch (Throwable $t) {
            $result = [
                'status' => 'error',
                'message' => 'Something went wrong.',
            ];
        }
    } else {
        $result = [
            'status' => 'error',
            'message' => 'No parameters given!',
        ];
    }

    echo json_encode($result);
