
	// include ook jquery voor dat je dit include

	 	//als er op de like btn is geklikt
		$("a.like").on("click", function(e){// e staat voor event
			//op welke post is er geklikt?
			var postId = $(this).data('id');
			//console.log(postId);
			var elLikes = $(this).parent().find(".likes");
			var likes = elLikes.html();
			
			//is er al eens geklikt?
				// @to do: unlike
			//like saven
			$.ajax({
  				method: "POST", //post (=set), ge creeert data bij
  				url: "ajax/like.php", //pagina om da te verwerken
  				data: { postId: postId }, //postId meegeven, ma ge kunt eender welke data meegeven
				dataType: "Json" // de server ga json terugggeven
			})
  				.done(function( res ) {
    			if( res.status == "succes" ) {
					likes++;
					elLikes.html(likes);
				}
  			});
			e.preventDefault();//ge ga ni meer naar boven in de pagina springen 
		})
		
