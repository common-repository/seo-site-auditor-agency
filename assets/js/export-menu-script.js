jQuery(document).ready(function ($) {

	//console.log("Probando");

	$("#export-button").click(function () {

		$.ajax({
			method: "POST",
			url: sdt.ajax_url,
			dataType: "json",
			data: {
				action: sdt.export_method,
				nonce: sdt.export_menu_nonce
			},
			success: function (result, status, xhr) {
				console.log(result);
				console.log(result.data.csv_rute);
				$("#download_csv").attr("href", result.data.csv_rute);
				//$("#download_csv").trigger("click");
				$("#download_csv")[0].click();

			},
			error: function (xhr, status, error) {
				console.log(xhr);
				console.log(status);
				console.log(error);
			}
		});

	});

});


