<?php
    include_once("bootstrap.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PROJECT - UPLOAD</title>
</head>

<body>
    <h2>Upload Photo</h2>
    <form id="uploadForm" method="post" action="">
        <div>
            <label for="Name">Name</label>
            <input type="name" id="Name" name="name">
        </div>
        <img src="./images/placeholder.png">
        <div>
            <label for="File">File</label>
            <input type="file" id="file" name="File">
        </div>
        <div>
            <label for="Description">Description</label>
        </div>
        <textarea name="Description" form="uploadForm" cols="83" rows="5" style="resize: none"></textarea>
        <div>        
            <input type="submit" value="Upload">
        </div>
    </form>
</body>
</html>