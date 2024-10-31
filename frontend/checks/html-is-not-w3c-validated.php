<?php
if (!function_exists('sdt_html_is_not_w3c_validated')) {

	function sdt_html_is_not_w3c_validated($args) {
		extract($args, EXTR_OVERWRITE);
		$flag = '';
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://validator.w3.org/nu/?out=json",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => -1,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $url,
			CURLOPT_HTTPHEADER => array(
				"User-Agent: Any User Agent",
				"Cache-Control: no-cache",
				"Content-type: text/html",
				"charset: utf-8"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			//handle error here
			die('sorry etc...');
		}
		$resJson = json_decode($response, true);
		$countMessages = (int) count($resJson['messages']);

		if ($countMessages > 0) {
			$flag = 1;
		}
		if (!empty($flag)) {
			global $errorMessages;
			$errorMessages[] = __("Validate your html at validator.w3.org.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" HTML is not W3C validated", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 

			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" HTML is W3C validated", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_analysis_checks', 'sdt_html_is_not_w3c_validated');