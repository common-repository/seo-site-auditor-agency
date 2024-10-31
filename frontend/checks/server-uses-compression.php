<?php

function sdt_getEncoding(&$headers) {
	$arr = explode("\r\n", trim($headers));
	array_shift($arr);
	foreach ($arr as $header) {
		list($k, $v) = explode(':', $header);
		if ('content-encoding' == strtolower($k)) {
			return trim($v);
		}
	}
	return false;
}

if (!function_exists('sdt_server_uses_compression')) {

	function sdt_server_uses_compression($args) {
		?>
		<section class="container-details-page">
			<h2  class="title-section">
				<?php _e('MOBILE ANALYSIS', "SDT"); ?>
			</h2>
			<article>
				<div class="tabla">
					<table>
						<tbody>
							<?php
							extract($args, EXTR_OVERWRITE);
							$ch = curl_init($url);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate'));
							curl_setopt($ch, CURLOPT_HEADER, 1);
							curl_setopt($ch, CURLOPT_REFERER, home_url());
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$buffer = curl_exec($ch);
							$curl_info = curl_getinfo($ch);
							curl_close($ch);
							$header_size = $curl_info["header_size"];
							$headers = substr($buffer, 0, $header_size);
							$body = substr($buffer, $header_size);

							$encoding = sdt_getEncoding($headers);

							if ($encoding) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e("  Server uses compression: $encoding ", "SDT"); ?>
									</td>
								</tr>
								<?php
							} else {
								global $errorMessages;
								$errorMessages[] = __(" Enable Gzip compression on your server, for better load speed.", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e("Server does not use GZIP compression", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}
					add_action('sdt_mobile_analysis_checks', 'sdt_server_uses_compression', 10);
					