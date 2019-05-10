<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();

    if (!empty($_GET)) {
        $photo = new Photo();
        $photo->setId($_GET['id']);
        $photo->setData();

        $uploaderUser = new User();
        $uploaderUser->setId($photo->getUploader());
        $uploaderUser->setData();

        $currentUser = new User();
        $currentUser->setId($_SESSION['userid']);
        $currentUser->setData();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <title>PROJECT - PHOTO</title>
</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?>

    <h1><?php echo $photo->getName(); ?></h1>

    <a href="<?php echo $photo->getPhotoPath(); ?>" target="_blank">
        <img src="<?php echo $photo->getCroppedPhotoPath(); ?>" class="searchresult <?php echo $photo->getPhotoFilter(); ?>" width="250px" height="250px"> 
    </a>

    <?php if ($photo->getUploader() == $_SESSION['userid']) :?>
        <a href="updatePhoto.php?id=<?php echo $photo->getId(); ?>" >Bewerken</a>
    <?php endif; ?>
    
    <p><strong>Uploaded by: </strong><a href="profile.php?id=<?php echo $uploaderUser->getId(); ?>"><?php echo $uploaderUser->getFirstName().' '.$uploaderUser->getLastName(); ?></a></p>
    <p><strong>Upload date: </strong><?php echo howLongAgo(strtotime($photo->getUploadDate())); ?></p>
    <p><strong>Upload location: </strong><span id="locationCity"></span></p>
    <div id="mapDiv" class="mapDiv"></div>
    <p id="description"><?php echo $photo->getDescription(); ?></p>
    
    <p><strong>Colors: </strong></p>
        <?php $colorArray = $photo->getColors();
            foreach ($colorArray as $color):?>
                <a href="search.php?color=<?php echo substr($color['color'], strpos($color['color'], '#') + 1); ?>"><div class="colorBall" style="background-color: <?php echo $color['color']; ?>"></div></a>
    <?php endforeach; ?>
    
     <form name="commentForm">
        <div>
            <textarea id="commentText" name="commentText" form="commentText" cols="83" rows="5" style="resize: none"></textarea>
        </div>
        <input id="commentSubmit" data-photoid="<?php echo $photo->getId(); ?>" data-userid="<?php echo $_SESSION['userid']; ?>" type="submit" value="Post comment">
    </form>

     <p><strong>Comments: </strong></p>
    <div id="comments" class="comments">
    <?php
        $commentArray = $photo->getComments();

        foreach ($commentArray as $commentRow):
            $comment = new Comment();
            $comment->setId($commentRow['id']);
            $comment->setData();

            $commentUser = $comment->getCommenterObject();
        ?>
        
            <div class="commentBox">
                <div class="commentUserBox">
                    <img width="50px" src="<?php echo $commentUser->getAvatar(); ?>">
                    <strong><?php echo $commentUser->getFullName(); ?></strong>
                </div>
                <p><?php echo $comment->getText(); ?></p>
                <p class="commentDate"><?php echo $comment->getDate(); ?></p>
            </div>
        
    <?php endforeach; ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://openlayers.org/api/OpenLayers.js"></script>
    <script>
        // find hashtags
        var description = document.getElementById("description").innerText;
        findHashtags(description);
        function findHashtags(searchText) {
            var regexp = /\B\#\w\w+\b/g;
            hashtags = searchText.match(regexp);

            hashtags.forEach(el=>{
                var tag = el.substr(1);
                description = description.replace(el, '<a href="search.php?tag='+tag+'" target="_blank">'+el+'</a>');
                $("#description").html(description);
            })
            
        }

        //location stuff
        var locationCity = document.getElementById("locationCity");
        var lat = <?php echo $photo->getLatitude(); ?>;
        var lon = <?php echo $photo->getLongitude(); ?>;
        $.ajax({
            method: "GET",
            url: "https://nominatim.openstreetmap.org/reverse", 
            data: { 
                format: "json",
                lat: lat,
                lon: lon
            },
                dataType: "JSON" 
            }).done(function(res) {
                locationCity.innerHTML = res['address']['town'];

                map = new OpenLayers.Map("mapDiv");
                map.addLayer(new OpenLayers.Layer.OSM());

                var lonLat = new OpenLayers.LonLat(lon, lat)//position.coords.latitude, position.coords.longitude)
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

        //adding a comment stuff
        $("#commentSubmit").on("click", function(e) {
            var photoId = $(this).data("photoid");
            var userId = $(this).data("userid");
            var commentText = $("#commentText").val();

            e.preventDefault();

            $.ajax({
                method: "POST",
                url: "ajax/postComment.php", 
                data: { 
                    photoId: photoId,
                    userId: userId,
                    commentText: commentText
                },
                    dataType: "JSON" 
                }).done(function(res) {
                    console.log(res);
                    if(res.status == 'success') {
                        var newComment = "<div class='commentBox'>" +
                            "<div class='commentUserBox'>" +
                                "<img width='50px' src='<?php echo $currentUser->getAvatar(); ?>'>" +
                                " <strong><?php echo $currentUser->getFullName(); ?></strong>" +
                            "</div>" +
                            "<p>" + $("#commentText").val() + "</p>" +
                            "<p class='commentDate'><?php echo date('Y-m-d', time()); ?></p>" +
                        "</div>";

                        $("#comments").append(newComment);

                        $("#commentText").val("");
                    }
                });
            });
    </script>
</body>
</html>