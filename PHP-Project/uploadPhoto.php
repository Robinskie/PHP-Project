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
        $photo->setPhotoFilter($_POST['photoFilters']);
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
            $statement = $conn->prepare('INSERT INTO photos (name, uploader, uploadDate, description, location, photoFilter) values (:name, :uploader, :uploadDate, :description, :location, :photoFilter)');
            $statement->bindValue(':name', $photo->getName());
            $statement->bindValue(':uploader', $photo->getUploader());
            $statement->bindValue(':uploadDate', $photo->getUploadDate());
            $statement->bindValue(':description', $photo->getDescription());
            $statement->bindValue(':photoFilter', $photo->getPhotoFilter());
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
    <link rel="stylesheet" href="./css/styleUpload.css">
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <title>PROJECT - UPLOAD</title>
</head>

<body>
    <?php include_once 'includes/nav.inc.php'; ?>

    <h1>Upload photo</h1>
    <?php
        if ($errorMessage != '') {
            echo "<span class='errorBox'>$errorMessage</span>";
        }
    ?>
    <form id="uploadForm" method="post" action="" enctype="multipart/form-data">

        <img id="photoPreview" src="./images/avatars/placeholder.png">
        <div>
            <label for="name"></label>
            <input type="name" id="name" name="name" placeholder="Fill in your name">
        </div>
        <div> 
            <label for="File"></label>
            <input id="photoInput" type="file" id="filebtn" name="file">
        </div>
        <div id="photoFilters" class="hidden">
            <label>
                <input type="radio" name="photoFilters" value="brannan">
                <img id="photoFilterOne" src="" class="brannan" alt="filter one" width="100px">
            </label>
            <label>
                <input type="radio" name="photoFilters" value="moon">
                <img id="photoFilterTwo" src="" class="moon" alt="filter two" width="100px">
            </label>
            <label>
                <input type="radio" name="photoFilters" value="_1977">
                <img id="photoFilterThree" src="" class="_1977" alt="filter three" width="100px">
            </label>
        </div>
        <div>
            <label for="description"></label>
        </div>
        <textarea name="description" form="uploadForm" id="description" placeholder="What is this photo about?" style="resize: none"></textarea>
        <div>
            <p class="location">Your location: <span id="locationCity"></span></p>
            <input id="locationLat" type="hidden" name="locationLat">
            <input id="locationLon" type="hidden" name="locationLon">
        </div>
        <div id="mapDiv" class="mapDiv"></div>
        <div>        
            <input type="submit" id="uploaden" value="Upload">
        </div>
    </form>
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://www.openlayers.org/api/OpenLayers.js"></script>
    <script>

        var photoPreview = document.getElementById("photoPreview");
        var photoInput = document.getElementById("photoInput");

        photoInput.addEventListener("change", function(e) {           
            var reader = new FileReader();
            reader.onload = function() {
                photoPreview.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });

        photoInput.addEventListener("change", function(e) {    
            document.getElementById("photoFilters").classList.remove('hidden');       
            document.getElementById("photoFilters").classList.add('visible');
        });

        var filterOne = document.getElementById('photoFilterOne');

        photoInput.addEventListener("change", function(e) {           
            var reader = new FileReader();
            reader.onload = function() {
                filterOne.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0])
        });

        filterOne.addEventListener('click', function(e){
            photoPreview.className= '';
            photoPreview.classList.add('brannan');
        });

        var filterTwo = document.getElementById('photoFilterTwo');

        photoInput.addEventListener("change", function(e) {           
            var reader = new FileReader();
            reader.onload = function() {
                filterTwo.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0])
        });

        filterTwo.addEventListener('click', function(e){
            photoPreview.className= '';
            photoPreview.classList.add('moon');
        });

        var filterThree = document.getElementById('photoFilterThree');

        photoInput.addEventListener("change", function(e) {           
            var reader = new FileReader();
            reader.onload = function() {
                filterThree.src = reader.result;
            }
            reader.readAsDataURL(e.target.files[0])
        });

        filterThree.addEventListener('click', function(e){
            photoPreview.className= '';
            photoPreview.classList.add('_1977');
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