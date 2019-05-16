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
        $newPhoto->setDescription($_POST['description']);

        if (!$newPhoto->checkIfFilledIn($newPhoto->getName())) {
            $errorMessage = 'Please enter a name for your picture.';
        }
        if (!$newPhoto->checkIfFilledIn($newPhoto->getDescription())) {
            $errorMessage = 'Please enter a description for your picture.';
        }

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script>
        var deleteBtn = document.getElementById("deleteBtn");

        deleteBtn.addEventListener("click", function(e) {
            $.ajax({
                method: "POST",
                url: "ajax/deletePhoto.php",
                data: {format:"json"},
                dataType: "Json" // de server ga json terugggeven
            }).done(function(res) {
                if(res === "succes") {
                    header('Location: index.php');
                } else {
                    console.log('something went wrong');
                }
            ?>
            e.preventDefault();
        });
    </script>
</body>
</html>