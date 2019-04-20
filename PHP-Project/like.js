
	// include ook jquery voor dat je dit include

	var likeUnlike = 0;

		//als er op de like btn word geklikt
		$("a.like").on("click", function(e){// e staat voor event
			//op welke post is er geklikt?
			var postId = $(this).data('id');
			//console.log(postId);
			var elLikes = $(this).parent().find(".likes");
			var likes = elLikes.html();
			

				$.ajax({
					method: "POST", //post (=set), ge creeert data bij
					url: "ajax/like.php", //pagina om da te verwerken
					data: { postId: postId }, //postId meegeven, ma ge kunt eender welke data meegeven
					dataType: "Json" // de server ga json terugggeven
				})
				.done(function(res) {
					//er is nog niet geliked
					if (likeUnlike === 0) {
						if( res.status == "succes" ) {
						likes++;
						elLikes.html(likes);
						likeUnlike = 1;
						e.preventDefault();//ge ga ni meer naar boven in de pagina springen 
						}
					} 
					//er is al geliked
					else {
						if( res.status == "succes" ) {
						likes--;
						elLikes.html(likes);
						likeUnlike = 0;
						e.preventDefault();//ge ga ni meer naar boven in de pagina springen 
						}
					}
				});
		})
