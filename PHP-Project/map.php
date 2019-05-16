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
    <title>Document</title>
</head>
<body>
    <div id="mapDiv" class="mapDiv"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://openlayers.org/api/OpenLayers.js"></script>
    <script>

            $.ajax({
                method: "POST",
                url: "getAllLocations.php",
                data: {format:"json"},
                dataType: "Json" // de server ga json terugggeven
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
                            
                        var zoom=16;

                        var markers = new OpenLayers.Layer.Markers( "Markers" );
                        map.addLayer(markers);
                        
                        markers.addMarker(new OpenLayers.Marker(lonLat));
                    }   
                        });
                    
                });
    
    </script>
    
</body>
</html>