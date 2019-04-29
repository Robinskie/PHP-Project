

		// als geklikt op de knop report
		$("a.reportButton").on("click", function(e){

			// niet meer naar boven springen
			e.preventDefault();

			// welke foto rapporteren
			var photoId = $(this).data('id');

			// rapporteren of take back
			var isReported = $(this).data('reported');

			// aantal reports
			var aantalReports = $(this).parent().find(".reportCount");
			var reports = $(aantalReports).html();
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
						// nog niet gerapporteerd
						console.log(isReported);
						console.log(res.status);

						if(isReported == 0) {
							reports++;
							aantalReports.html(reports);
							reportButton.html("Take back");
							reportButton.data('reported', 1);
							
						} else if(isReported == 1) {
							reports--;
							aantalReports.html(reports);
							reportButton.html("Report");
							reportButton.data('reported', 0);
						}
				}
			});
		})
