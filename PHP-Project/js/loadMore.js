function getPhotoHTML(photo) {
    var temp = '<div class="photoBox">' +
    '<a href="photo.php?id=' + photo['id'] + '">' +
    '<p class="gebruiker">' + photo['uploader'] + '</p>' +
    '<p class="photoDate">' + photo['uploadDate'] + '</p>' + 
    '<img class="' + photo['filter'] + '" src="' + photo['croppedPhoto'] + '"width="250px" height="250px">' +
    '<div class="telaantal">' +
    '<p><span class="likeCount">' + photo['likeCount'] + '</span> likes ' +
    '<span class="reportCount">' + photo['reportCount'] + '</span> reports</p>' +
    '</div>';

    if(photo['likeState'] == "1") {
        temp += '<a href="#" id="likeButton" class="likeButton" data-id="' + photo['id'] + '" data-liked=1>Unlike</a>';
    } else {
        temp += '<a href="#" id="likeButton" class="likeButton" data-id="' + photo['id'] + '" data-liked=0>Like</a>';
    }

    if(photo['reportState'] == "1") {
        temp += '<a href="#" id="reportButton" class="reportButton" data-id="' + photo['id'] + '" data-reported=1>Take back</a>';
    } else {
        temp += '<a href="#" id="reportButton" class="reportButton" data-id="' + photo['id'] + '" data-reported=0>Report</a>';
    }

    temp += "</a></div>";

    return temp;
}