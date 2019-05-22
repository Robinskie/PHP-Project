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
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <title>Update Photo - Zoogram</title>
</head>

<body>
    <?php include_once 'includes/nav.inc.php'; ?>
    <div class="content">
    <h1>Update Photo</h1>
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
            <input id="updateBtn" type="submit" value="Update">
            <a href="#" id="deleteBtn" >Verwijderen</a>
        </div>
    </form>

    

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script>
        var deleteBtn = document.getElementById("deleteBtn");

        deleteBtn.addEventListener("click", function(e) {
            console.log('er is geklikt');
            var user = <?php echo $_SESSION['userid']; ?>;
            var photoId = <?php echo $oldPhoto->getId(); ?>;

            $.ajax({
                method: "POST",
                url: "ajax/deletePhoto.php", 
                data: { 
                    user: user,
                    photoId: photoId
                },
                    dataType: "JSON" 
            }).done(function(res) {
                console.log(res);
                if(res['status'] === "success") {
                    console.log('success');
                    window.location.href = "index.php";
                } else {
                    console.log('something went wrong');
                }
                e.preventDefault();
            });
        });
    </script>
</body>
</html>