	// include ook jquery voor dat je dit include


		//als er op de like btn word geklikt
		$("a.likeButton").on("click", function(e){// e staat voor event
			//ge ga ni meer naar boven in de pagina springen 
			e.preventDefault();
			//op welke post is er geklikt?
			var photoId = $(this).data('id');
			//ist om te liken of te unliken?
			var isLiked = $(this).data('liked');
			//aantal likes krijge
			var elLikes = $(this).parent().find(".likeCount");
			var likes = $(elLikes).html();
			var likeButton = $(this).parent().find(".likeButton");

			$.ajax({
				method: "POST",
				url: "ajax/like.php", 
				data: { 
					photoId: photoId,
					isLiked: isLiked
				},
					dataType: "JSON" 
				}).done(function(res) {
					if( res.status == "success" ) {
						//er is nog niet geliked
						console.log(isLiked);
						console.log(res.status);
						if(isLiked == 0) {
							likes++;
							elLikes.html(likes);
							likeButton.html("Unlike");
							likeButton.data('liked', 1);
							
						} else if(isLiked == 1) {
							likes--;
							elLikes.html(likes);
							likeButton.html("Like");
							likeButton.data('liked', 0);
						}
				}
			});
		})
