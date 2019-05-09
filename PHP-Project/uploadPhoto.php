<?php
    include_once 'bootstrap.php';

    redirectIfLoggedOut();

    $errorMessage = '';

    if (!empty($_POST)) {
        $file = $_FILES['file'];

        $photo = new Photo();
        $photo->setName($_POST['name']);
        $photo->setUploadDate(date('Y-m-d H:i:s'));
        $photo->setUploader($_SESSION['userid']);
        $photo->setDescription($_POST['description']);
        $photo->setLocation($_POST['locationLat'], $_POST['locationLon']);

        if (!$photo->checkIfFilledIn($photo->getName())) {
            $errorMessage = 'Please enter a name for your picture.';
        }
        if (!$photo->checkIfFilledIn($photo->getDescription())) {
            $errorMessage = 'Please enter a description for your picture.';
        }
        if (!$photo->checkIfFileTypeIsImage($file)) {
            $errorMessage = 'This is not an image file.';
        }

        if ($errorMessage == '') {
            $conn = Db::getInstance();
            $statement = $conn->prepare('INSERT INTO photos (name, uploader, uploadDate, description, location) values (:name, :uploader, :uploadDate, :description, :location)');
            $statement->bindValue(':name', $photo->getName());
            $statement->bindValue(':uploader', $photo->getUploader());
            $statement->bindValue(':uploadDate', $photo->getUploadDate());
            $statement->bindValue(':description', $photo->getDescription());
            $statement->bindValue(':location', $photo->getLocation()->latitude.','.$photo->getLocation()->longitude);
            $statement->execute();

            $originalImage = imagecreatefromstring(file_get_contents($file['tmp_name']));
            $croppedImage = $photo->cropImage($file, 600, 600);
            $photo->setId(Db::simpleFetch('SELECT MAX(id) FROM photos')['MAX(id)']);

            imagepng($originalImage, $photo->getPhotoPath());
            imagepng($croppedImage, $photo->getCroppedPhotoPath());

            $photo->saveColors();
            header('Location:photo.php?id='.$photo->getId());
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
    <title>PROJECT - UPLOAD</title>
</head>

<body>
    <?php include_once 'includes/nav.inc.php'; ?>

    <h2>Upload Photo</h2>
    <?php
        if ($errorMessage != '') {
            echo "<span class='errorBox'>$errorMessage</span>";
        }
    ?>
    <form id="uploadForm" method="post" action="" enctype="multipart/form-data">
        <div>
            <label for="name">Name: </label>
            <input type="name" id="name" name="name">
        </div>
        <img id="photoPreview" src="./images/placeholder.png" width="500px">
        <div>
            <label for="File">File: </label>
            <input id="photoInput" type="file" id="file" name="file">
        </div>
        <div>
            <label for="description">Description: </label>
        </div>
        <textarea name="description" form="uploadForm" cols="83" rows="5" style="resize: none"></textarea>
        <div>
            <p>Location: <span id="locationCity"></span></p>
            <input id="locationLat" type="hidden" name="locationLat">
            <input id="locationLon" type="hidden" name="locationLon">
        </div>
        <div id="mapDiv" class="mapDiv"></div>
        <div>        
            <input type="submit" value="Upload">
        </div>
    </form>
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
    <script>

        var photoPreview = document.getElementById("photoPreview");
        var photoInput = document.getElementById("photoInput");

        photoInput.addEventListener("change", function(e) {           
            var reader = new FileReader();
            reader.onload = function() {
                photoPreview.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0])
        });

        var locationInput = document.getElementById("locationCity");
        var locationLatInput = document.getElementById("locationLat");
        var locationLonInput = document.getElementById("locationLon");
        navigator.geolocation.getCurrentPosition(function(position) {
            $.ajax({
            method: "GET",
            url: "https://nominatim.openstreetmap.org/reverse", 
            data: { 
                format: "json",
                lat: position.coords.latitude,
                lon: position.coords.longitude
            },
                dataType: "JSON" 
            }).done(function(res) {
                locationInput.innerHTML = res['address']['town'];
                locationLatInput.value = position.coords.latitude
                locationLonInput.value = position.coords.longitude;

                map = new OpenLayers.Map("mapDiv");
                map.addLayer(new OpenLayers.Layer.OSM());

                var lonLat = new OpenLayers.LonLat(position.coords.longitude, position.coords.latitude)//position.coords.latitude, position.coords.longitude)
                    .transform(
                        new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                        map.getProjectionObject() // to Spherical Mercator Projection
                    );
                    
                var zoom=16;

                var markers = new OpenLayers.Layer.Markers( "Markers" );
                map.addLayer(markers);
                
                markers.addMarker(new OpenLayers.Marker(lonLat));
                
                map.setCenter (lonLat, zoom);
            });
        });

    </script>
</body>
</html>