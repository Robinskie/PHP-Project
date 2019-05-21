<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <title>Map - Zoogram</title>
</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?>
    <div id="mapDiv" class="bigMapDiv"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://openlayers.org/api/OpenLayers.js"></script>
    <script>

            $.ajax({
                method: "POST",
                url: "getAllLocations.php",
                data: {format:"json"},
                dataType: "Json" // de server gaat json terugggeven
            }).done(function(res) {
                var response = JSON.stringify(res);
                var jsonData = JSON.parse(response);
                console.log(jsonData);

                map = new OpenLayers.Map("mapDiv");
                map.addLayer(new OpenLayers.Layer.OSM());

                jsonData.forEach(function(key) {
                    if (key.latitude != '' && key.longitude != '') {
                    var lat = key.latitude;
                    var lon = key.longitude;
                    console.log(lon);
                    console.log(lat);
                    

                    var lonLat = new OpenLayers.LonLat(lon, lat)//position.coords.latitude, position.coords.longitude)
                        .transform(
                            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                            map.getProjectionObject() // to Spherical Mercator Projection
                        );
                            
                        var zoom = 10;

                        var markers = new OpenLayers.Layer.Markers( "Markers" );
                        map.addLayer(markers);
                        
                        markers.addMarker(new OpenLayers.Marker(lonLat));

                        map.setCenter (lonLat, zoom);
                    }   
                        });
                    
                });
    
    </script>
    
</body>
</html>