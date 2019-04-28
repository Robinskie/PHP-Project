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
					//er is nog niet geliked
					if (isLiked == "false") {
						if( res.status == "success" ) {
							likes++;
							elLikes.html(likes);
							likeButton.html("Unlike");
							likeButton.data('liked', "true");
						}
					} 
					//er is al geliked
					else {
						if( res.status == "success" ) {
							likes--;
							elLikes.html(likes);
							likeButton.html("Like");
							likeButton.data('liked', "false");
						}
					}
			});
		})
