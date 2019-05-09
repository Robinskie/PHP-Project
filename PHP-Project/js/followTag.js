$("a.followButton").on("click", function(e){
    e.preventDefault();
    var thisTag = $(this).data('id');
    console.log(thisTag);
    var isTagFollowed = $(this).data('followed');
    var followButton = $(this).parent().find(".followButton");

    $.ajax({
        method: "POST",
        url: "ajax/followTag.php", 
        data: { 
            thisTag: thisTag,
            isTagFollowed: isTagFollowed
        },
            dataType: "JSON" 
        }).done(function(res) {
            if( res.status == "success" ) {
                console.log(isTagFollowed);
                console.log(res.status);
                if(isTagFollowed == 0) {
                    followButton.html("Unfollow");
                    followButton.data('followed', 1);
                    
                } else if(isTagFollowed == 1) {
                    followButton.html("Follow");
                    followButton.data('followed', 0);
                }
        }
    });
})
