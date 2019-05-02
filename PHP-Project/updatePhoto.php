<?php
    include_once 'bootstrap.php';

    redirectIfLoggedOut();

    $errorMessage = '';

    $oldPhoto = new Photo();
    $oldPhoto->setId($_GET['id']);
    $oldPhoto->setData();

    if ($_SESSION['userid'] != $oldPhoto->getUploader()) {
        header('Location: index.php');
    }

    if (!empty($_POST)) {
        $newPhoto = new Photo();
        $newPhoto->setName($_POST['name']);
        if (!empty($_POST['description'])) {
            $newPhoto->setDescription($_POST['description']);
        } else {
            echo 'description is empty';
        }
        //checks
        if (!$newPhoto->checkIfFilledIn($newPhoto->getName())) {
            $errorMessage = 'Please enter a name for your picture.';
        }
        if (!$newPhoto->checkIfFilledIn($newPhoto->getDescription())) {
            $errorMessage = 'Please enter a description for your picture.';
        }

        //in orde -> let's update
        if ($errorMessage == '') {
            $conn = Db::getInstance();
            $statement = $conn->prepare('UPDATE `photos` SET `name`=:name,`description`=:description WHERE id = :photoId');
            $statement->bindValue(':name', $newPhoto->getName());
            $statement->bindValue(':description', $newPhoto->getDescription());
            $statement->bindValue(':photoId', $oldPhoto->getId());
            $statement->execute();
        }

        header('Location:photo.php?id='.$oldPhoto->getId());
    } ?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>PROJECT - UPDATE</title>
</head>

<body>
    <?php include_once 'includes/nav.inc.php'; ?>

    <h2>Update Photo</h2>
    <?php
        if ($errorMessage != '') {
            echo "<span class='errorBox'>$errorMessage</span>";
        }

    ?>
    <form id="updateForm" method="post" action="" enctype="multipart/form-data">
        <div>
            <label for="name">Name: </label>
            <input type="name" id="name" name="name" value="<?php echo $oldPhoto->getName(); ?>" >
        </div>
        <img id="photoPreview" src="<?php echo $oldPhoto->getPhotoPath(); ?>">
        <div>
            <label for="description">Description: </label>
        </div>
        <textarea name="description" form="updateForm" cols="83" rows="5" style="resize: none"><?php echo $oldPhoto->getDescription(); ?></textarea>
        <div>        
            <input type="submit" value="Update">
        </div>
    </form>
        
    <script>
        //PREVIEW FOTO
        var photoPreview = document.getElementById("photoPreview");
        var photoInput = document.getElementById("photoInput");

        photoInput.addEventListener("change", function(e) {           
            var reader = new FileReader();
            reader.onload = function() {
                photoPreview.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0])
        });
    </script>
</body>
</html>