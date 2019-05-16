<?php
require_once '../bootstrap.php';

if (!empty($_POST)) {
    $photo = new Photo();

    $photo->setId($_GET['id']);
    $photo->setData();
    $photo->deletePicture();

    $result = [
        'status' => 'success',
        'message' => 'Photo is deleted'
    ];

    echo json_encode($result);

}