<?php
    include_once("bootstrap.php");

    redirectIfLoggedOut();

    $errorMessage = "";

    $oldPhoto = new Photo();
    $oldPhoto->setId(40);
    $oldPhoto->setData();

    if(!empty($_POST)) {
        $file = $_FILES['file'];

        $newPhoto = new Photo();
        $newPhoto->setName($_POST['name']);
        $newPhoto->setDescription($_POST['description']);
        
        //checks
        if(!$photo->checkIfFilledIn($photo->getName())) {
            $errorMessage = "Please enter a name for your picture.";
        }
        if(!$photo->checkIfFilledIn($photo->getDescription())) {
            $errorMessage = "Please enter a description for your picture.";
        }
        if(!$photo->checkIfFileTypeIsImage($file)) {
            $errorMessage = "This is not an image file.";
        }

        //in orde -> let's update
        if($errorMessage == "") {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE `photos` SET `name`=:name,`description`=:description WHERE photoId");
            $statement->bindValue(":name", $photo->getName());
            $statement->bindValue(":description", $photo->getDescription());
            $statement->execute();

            //image maken
            $originalImage = imagecreatefromstring(file_get_contents($file['tmp_name']));
            //cropped image maken
            $croppedImage = $photo->cropImage($file, 600, 600);

            //get/set ID of image
            $photo->setId(Db::simpleFetch("SELECT MAX(id) FROM photos")['MAX(id)']);

            //nu de image kopiëren
            imagepng($originalImage, $photo->getPhotoPath());
            imagepng($croppedImage, $photo->getCroppedPhotoPath());
        
            //verplaatsen naar photo.php?id=(id)
            header('Location:photo.php?id=' . $photo->getId());
        }

        
    }


?>

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
    <?php include_once("includes/nav.inc.php");?>

    <h2>Update Photo</h2>
    <?php
        if($errorMessage != "") {
            echo "<span class='errorBox'>$errorMessage</span>";
        }
        
    ?>
    <form id="uploadForm" method="post" action="" enctype="multipart/form-data">
        <div>
            <label for="name">Name: </label>
            <?php echo $photo->getName();?>
            <input type="name" id="name" name="name" value="<?php $photo->getName(); ?>" >
        </div>
        <img id="photoPreview" src="<?php $photo->getPhotoPath()?>">
        <div>
            <label for="File">File: </label>
            <input id="photoInput" type="file" id="file" name="file">
        </div>
        <div>
            <label for="description">Description: </label>
        </div>
        <textarea name="description" form="uploadForm" cols="83" rows="5" style="resize: none"></textarea>
        <div>        
            <input type="submit" value="Upload">
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