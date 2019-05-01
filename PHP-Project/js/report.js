	
		$("a.reportButton").on("click", function(e){ 
			e.preventDefault();

			var photoId = $(this).data('id');
			var isReported = $(this).data('reported');

			var elReports = $(this).parent().find(".reportCount");
			var reports = $(elReports).html();
			var reportButton = $(this).parent().find(".reportButton");

			$.ajax({
				method: "POST",
				url: "ajax/report.php", 
				data: { 
					photoId: photoId,
					isReported: isReported
				},
					dataType: "JSON" 
				}).done(function(res) {
					if( res.status == "success" ) {
			
						console.log(isReported);
						console.log(res.status);
						if(isReported == 0) {
							reports++;
							elReports.html(reports);
							reportButton.html("Take back");
							reportButton.data('reported', 1);
							
						} else if(isReported == 1) {
							reports--;
							elReports.html(reports);
							reportButton.html("Report");
							reportButton.data('reported', 0);
						}
				}
			});
		})
