<?php
    include_once("bootstrap.php");

    if(!empty($_GET)) {
        $photo = new Photo();
        $photo->setId($_GET['id']);
        
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM photos WHERE id = :id");
        $statement->bindValue(":id", $photo->getId());
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $photo->setName($result['name']);
        $photo->setUploader($result['uploader']);
        $photo->setUploadDate($result['uploadDate']);
        $photo->setDescription($result['description']);
        $photo->setUploadDate($result['uploadDate']);
        $photo->setTags($result['tags']);

        $uploaderUser = new User();
        $userRow = Db::simpleFetch("SELECT * FROM users WHERE id = " . $photo->getUploader());
        $uploaderUser->setFirstName($userRow['firstName']);
        $uploaderUser->setLastName($userRow['lastName']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PROJECT - PHOTO</title>
</head>
<body>
    <h2><?php echo $photo->getName();?></h2>
    <a href="<?php echo $photo->getPhotoPath();?>"><img src="<?php echo $photo->getCroppedPhotoPath();?>"></a>
    <p><strong>Uploaded by: </strong><?php echo $uploaderUser->getFirstName() . " " . $uploaderUser->getLastName();?></p>
    <p><strong> Upload date: </strong><?php echo $photo->getUploadDate();?></p>
    <p><?php echo $photo->getDescription();?></p>
    <p><strong>Tags: </strong><?php echo $photo->getTags();?></p>
</body>
</html>