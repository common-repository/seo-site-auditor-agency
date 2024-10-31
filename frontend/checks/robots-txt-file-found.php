<?php
if (!function_exists('std_robots_txt_file_found')) {

	function std_robots_txt_file_found($args) {
		extract($args);
		$protocol = array('http://', 'https://', 'ftp://', 'www.');
		$urlSite = explode('/', $url);
		$domainSite = $urlSite[0] . '//' . $urlSite[2];

		$found = '';
		$robots_response = wp_remote_get($domainSite . './robots.txt');
		$found = !is_wp_error($robots_response);
		if (empty($found)) {
			global $errorMessages;
			$errorMessages[] = __(" Add robots.txt file to your root directory.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" robots.txt file not found", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e("  robots.txt file found", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_domain_analysis_checks', 'std_robots_txt_file_found');