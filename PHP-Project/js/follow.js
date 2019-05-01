$("a.followButton").on("click", function(e){
    e.preventDefault();
    var thisUserId = $(this).data('id');
    var isFollowed = $(this).data('followed');
    var followButton = $(this).parent().find(".followButton");

    var elFollowers = $(this).parent().find(".followersCount");
	var followers = $(elFollowers).html();

    $.ajax({
        method: "POST",
        url: "ajax/follow.php", 
        data: { 
            thisUserId: thisUserId,
            isFollowed: isFollowed
        },
            dataType: "JSON" 
        }).done(function(res) {
            if( res.status == "success" ) {
                console.log(isFollowed);
                console.log(res.status);
                if(isFollowed == 0) {
                    followers++;
					elFollowers.html(followers);
                    followButton.html("Unfollow");
                    followButton.data('followed', 1);
                    
                } else if(isFollowed == 1) {
                    followers--;
					elFollowers.html(followers);
                    followButton.html("Follow");
                    followButton.data('followed', 0);
                }
        }
    });
})
