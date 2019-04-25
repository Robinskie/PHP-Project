console.log('eehm');
	// include ook jquery voor dat je dit include
<<<<<<< HEAD

	console.log("jeeej");

=======
>>>>>>> 497de8bf5f18bed6d56e71a360c30120024d686c
	var likeUnlike = 0;

		//als er op de like btn word geklikt
		$("a.like").on("click", function(e){// e staat voor event
			console.log('click');
			//op welke post is er geklikt?
			var photoId = $(this).data('id');
			//console.log(postId);
			var elLikes = $(this).parent().find(".likes");
			var likes = elLikes.html();
			console.log('var');

				$.ajax({
					method: "POST", //post (=set), ge creeert data bij
					url: "ajax/like.php", //pagina om da te verwerken
					data: { photoId: photoId }, //postId meegeven, ma ge kunt eender welke data meegeven
					dataType: "Json" // de server ga json terugggeven
				})
				.done(function(res) {
					console.log('ajax_begin');
					//er is nog niet geliked
					if (likeUnlike === 0) {
						if( res.status == "succes" ) {
						console.log('++');
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
