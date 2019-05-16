<?php
require_once '../bootstrap.php';

if (!empty($_POST)) {
    $photo = new Photo();

    $photo->setId($_POST['photoId']);
    $photo->setData();
    $photo->deletePicture();

    $result = [
        'status' => 'success',
        'message' => 'Photo is deleted'
    ];

    echo json_encode($result);

}