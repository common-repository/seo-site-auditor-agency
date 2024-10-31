jQuery(document).ready(function ($) {

	$sdt_pdf = $("#sdt-pdf-iframe");


	sdt_checks_template_string = "";
	sdt_checks_sections_string = "";
	sdt_checks_in_progress = false;
	sdt_checks_already_done = false;
	$iframe = $('#sdt-pdf-iframe');
	//console.log(sdt);

	$("#audit-form").keypress(function (e) {

		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {

			//if($('#audit-form')[0].checkValidity()){

			$(this).submit();
			//}else{
			console.log("form no valido");
			//}           

		}

	});

	$("#audit-form").submit(function (e) {
		var $form = jQuery(this);
		e.preventDefault();

		if (sdt_checks_in_progress) {
			console.log("Chequeos en progreso");
			return false;
		}

		// Clear everything in case it's a repeated check
		sdt_checks_template_string = "";
		sdt_checks_sections_string = "";
		sdt_checks_in_progress = false;
		sdt_checks_already_done = false;
		$iframe.show();
		$iframe.first().contents().find('body').empty();

		$element = document.getElementById('sdt_pdf');
		var position = $iframe.offset().top;
		$tagImg = "<img class='loader' src='" + sdt.base_plugin_url + "assets/img/loader.gif' alt='loader' style='padding: 60px 42%;'>";
		$iframe.contents().find("body").append($tagImg);

		if (sdt.is_premium && !sdt.dont_show_report_after_submission) {
			$("html, body").animate({
				scrollTop: position
			}, 2000);
		} else {
			$form.find('.loader, .audit-results-emailed').remove();
			$form.append($tagImg);
		}



		//add header to iframe    
		$.ajax({
			method: "post",
			url: sdt.ajax_url,
			dataType: "text",
			data: {
				action: sdt.get_header_iframe,
				sdt_nonce: sdt.sdt_nonce,
			},
			success: function (result) {
				console.log(result);
				$iframe.ready(function () {
					$iframe.contents().find("head").append(result);
				});
			},
			error: function (xhr, status, error) {
				console.log(xhr);
				console.log(status);
				console.log(error);
			}
		});
		$.ajax({
			method: "post",
			url: sdt.ajax_url,
			dataType: "text",
			data: {
				action: sdt.get_pdf_ajax,
				sdt_nonce: sdt.sdt_nonce,
				sdt_website: $("#sdt_website").val(),
				sdt_keyword: $("#sdt_keyword").val(),
				sdt_name: $("#sdt_name").val(),
				sdt_email: $("#sdt_email").val()
			},
			success: function (result, status, xhr) {
				//console.log("Pdf get");
				//console.log(result);
				//console.log(status);
				//console.log(xhr);

				//var $iframe = $('#sdt-pdf-iframe');
				sdt_checks_template_string = result;
				var hooks = ["sdt_screenShot", "sdt_pagelinks_analysis_checks", "sdt_code_speed_checks",
					"sdt_code_url_checks", "sdt_code_title_checks", "sdt_code_image_checks", "sdt_top_5_wordused",
					"sdt_code_headings_checks", "sdt_code_copyanalysis_checks", "sdt_code_analysis_checks", "sdt_mobile_analysis_checks",
					"sdt_domain_analysis_checks", "sdt_tasklist_above"];

				hooks_number = hooks.length;
				do_checks(hooks, 0, hooks_number, [], []);

			},
			error: function (xhr, status, error) {
				console.log("Pdf get");
				console.log(xhr);
				console.log(status);
				console.log(error);
			}
		});

		$("#audit-form input").prop("disabled", true);
		sdt_checks_in_progress = true;

	});

	function save_pdf() {

		//$element = document.getElementById('sdt-pdf-iframe');
		iframe = document.getElementById('sdt-pdf-iframe');
		$element = iframe.contentWindow.document.getElementsByTagName("html")[0];

		heightHtml = jQuery('#sdt-pdf-iframe').contents().height();

		jQuery('#sdt-pdf-iframe').height(heightHtml);

		var reportHtml = jQuery('#sdt-pdf-iframe').contents().find('body')[0].innerHTML.replace(/(\r\n|\n|\r)/gm, "").replace(/ {2,}/g, ' ').replace(/\t{2,}/g, ' ');
		$.ajax({
			method: "post",
			url: sdt.ajax_url,
			dataType: "json",
			data: {
				action: sdt.save_pdf_ajax,
				sdt_nonce: sdt.sdt_nonce,
				sdt_website: $("#sdt_website").val(),
				sdt_keyword: $("#sdt_keyword").val(),
				sdt_name: $("#sdt_name").val(),
				sdt_email: $("#sdt_email").val(),
				sdt_pdf_image: reportHtml
			},
			success: function (result, status, xhr) {
				console.log("Guardado imagen success");
				console.log(result);
				console.log(status);
				console.log(xhr);
			},
			error: function (xhr, status, error) {
				console.log("Guardado imagen error");
				console.log(xhr);
				console.log(status);
				console.log(error);
			}
		}).always(function (response) {
			if (!sdt.is_premium || sdt.dont_show_report_after_submission) {
				$("#audit-form").find('.loader').remove();
				$("#audit-form").append('<p class="audit-results-emailed">' + sdt.results_sent_to_email_text + '</p>');
			}
		});

		// We no longer save the report as image
		/*html2canvas($element, {height: heightHtml + 50}).then(function (canvas) {
		 myImage = canvas.toDataURL("image/jpeg", 0.5);
		 console.log(myImage);
		 
		 var finalImage = myImage.split(',');
		 $.ajax({
		 method: "post",
		 url: sdt.ajax_url,
		 dataType: "json",
		 data: {
		 action: sdt.save_pdf_ajax,
		 sdt_nonce: sdt.sdt_nonce,
		 sdt_website: $("#sdt_website").val(),
		 sdt_keyword: $("#sdt_keyword").val(),
		 sdt_name: $("#sdt_name").val(),
		 sdt_email: $("#sdt_email").val(),
		 sdt_pdf_image: finalImage[ finalImage.length - 1]
		 },
		 success: function (result, status, xhr) {
		 console.log("Guardado imagen success");
		 console.log(result);
		 console.log(status);
		 console.log(xhr);
		 },
		 error: function (xhr, status, error) {
		 console.log("Guardado imagen error");
		 console.log(xhr);
		 console.log(status);
		 console.log(error);
		 }
		 }).always(function (response) {
		 if (!sdt.is_premium) {
		 $("#audit-form").find('.loader').remove();
		 $("#audit-form").append('<p class="audit-results-emailed">' + sdt.results_sent_to_email_text + '</p>');
		 }
		 });
		 
		 });*/

	}

	function do_checks(hooks, current_hook, hooks_number, count_success, error_messages) {

		if (current_hook >= hooks_number) {

			console.log("checks done");

			$iframe.ready(function () {

				res = sdt_checks_template_string.replace("sdt_checks_go_here", sdt_checks_sections_string);
				$iframe.contents().find("body").append(res);

				$iframe.contents().find('.info-details').append($iframe.contents().find('.capture-wrapper'));
				complete_checks();

				$iframe.contents().find(".loader").css("display", "none");
				//$iframe.attr("scrolling", "no");
				save_pdf();

			});

			$("#audit-form input").prop("disabled", false);
			sdt_checks_in_progress = false;
			sdt_checks_already_done = true;

			return 0;
		}

		$.ajax({
			method: "POST",
			url: sdt.ajax_url,
			dataType: "json",
			data: {
				action: sdt.get_check_section,
				hook: hooks[current_hook],
				sdt_nonce: sdt.sdt_nonce,
				count_success: count_success,
				error_messages: error_messages,
				sdt_website: $("#sdt_website").val(),
				sdt_keyword: $("#sdt_keyword").val(),
				sdt_name: $("#sdt_name").val(),
				sdt_email: $("#sdt_email").val()
			},
			success: function (result, status, xhr) {
				//jQuery('iframe').contents().find('body').append(result);
				console.log(result);
				console.log(result.data.check_html_result);
				console.log(result.data.error_messages);
				console.log(result.data.count_success);

				all_count_success = result.data.count_success;
				all_error_messages = result.data.error_messages;

				sdt_checks_sections_string += result.data.check_html_result;

				if (result.data.fields_are_valid) {
					current_hook++;
					do_checks(hooks, current_hook, hooks_number, all_count_success, all_error_messages);
				} else {
					console.log("Los datos del form no son validos");
				}

			},
			error: function (xhr, status, error) {

				console.log(xhr);
				console.log(status);
				console.log(error);

				current_hook++;
				do_checks(hooks, current_hook, hooks_number, count_success, error_messages);

			}
		});

	}

	function complete_checks() {

		$goodSignal = $iframe.contents().find("#goodSignal").val();
		$errors = $iframe.contents().find("#issuesFound").val();
		$milisec = $iframe.contents().find("#loadtime").text();
		var seconds;
		//validate if empty $ milisec
		if ($milisec == '') {
			seconds = 'Unavailable';
		} else {
			//convert miliseconds to seconds
			seconds = parseInt($milisec) / 1000.0;
			seconds = seconds.toFixed(2);
		}

		if ($goodSignal != "" && $errors != "") {
			//calculate page grade
			total = parseInt($goodSignal) + parseInt($errors);
			calculatePercent = (parseInt($goodSignal) / total) * 100;
			var percent = Math.round(calculatePercent);
			//percent = percent.toFixed(2);
			//Insert
			$iframe.contents().find("#issuesresutl").text($errors);
			$iframe.contents().find("#corrects").text($goodSignal);
			//addd text Page grade 
			$iframe.contents().find(".score").text(percent + '%');
			//add time seconds
			$iframe.contents().find("#loadtime").text(seconds);
		} else {
			//nothing
		}


	}

});



