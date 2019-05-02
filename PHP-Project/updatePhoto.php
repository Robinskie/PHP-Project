<?php
    include_once 'bootstrap.php';

    redirectIfLoggedOut();

    //VAR errormessage om errors op te vangen
    $errorMessage = '';

    //info van huidige foto dat gaat bewerkt worden ophalen
    $oldPhoto = new Photo();
    $oldPhoto->setId($_GET['id']);
    $oldPhoto->setData();

    //user van pagina smijten als zij niet de foto hebben geüpload (je kan geen eigen foto's bewerken/deleten)
    if ($_SESSION['userid'] != $oldPhoto->getUploader()) {
        header('Location: index.php');
    }

    if (!empty($_POST)) {

        //geupdate versie foto aanmaken
        $newPhoto = new Photo();
        $newPhoto->setName($_POST['name']);
        $newPhoto->setDescription($_POST['description']);
      
        //checks
        if (!$newPhoto->checkIfFilledIn($newPhoto->getName())) {
            $errorMessage = 'Please enter a name for your picture.';
        }
        if (!$newPhoto->checkIfFilledIn($newPhoto->getDescription())) {
            $errorMessage = 'Please enter a description for your picture.';
        }

        //in orde -> let's update
        if ($errorMessage == '') {
            $oldPhoto->updatePhoto($newPhoto->getName(), $newPhoto->getDescription());
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

    <a href="#" id="deleteBtn" >Verwijderen</a>
        
    <script>
        var deleteBtn = document.getElementById("deleteBtn");

        deleteBtn.addEventListener("click", function(e) {
            <?php $photo = new Photo();
                $photo->setId($_GET['id']);
                $photo->setData();
                $photo->deletePicture();
            ?>
            e.preventDefault();
        });
    </script>
</body>
</html>