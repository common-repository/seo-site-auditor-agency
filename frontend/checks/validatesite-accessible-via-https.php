<?php
if (!function_exists('sdt_validatesite_accessible_via_https')) {

	function sdt_validatesite_accessible_via_https($args) {
		extract($args, EXTR_OVERWRITE);
		$protocol = array('http://', 'https://', 'ftp://');
		$urlSite = explode('/', str_replace($protocol, '', $url));
		$domain = $urlSite[0];
		$ssl_check = @fsockopen('ssl://' . $domain, 443, $errno, $errstr, 30);
		$request = !!$ssl_check;
		if ($ssl_check) {
			fclose($ssl_check);
		}
		//return $res;
		if ($request) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Site is accessible via https (SSL).", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $errorMessages;
			$errorMessages[] = __("Highly recommend adding an SSL to your domain for added security and seo benefit (https).", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Site is not accessible via https (SSL).", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}

add_action('sdt_domain_analysis_checks', 'sdt_validatesite_accessible_via_https');