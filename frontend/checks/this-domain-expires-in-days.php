<?php
if (!function_exists('sdt_this_domain_expires_in_days')) {

	function sdt_this_domain_expires_in_days($args) {
		extract($args, EXTR_OVERWRITE);
		if ($domainData['expiration_timestamp'] < strtotime('+6 months')) {
			global $errorMessages;
			$errorMessages[] = __(" Domain expires in less than 6 months", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php printf(__(" This domain expires in less than 6 months: %s.", "SDT"), sanitize_text_field($domainData['Domain expires'])); ?>
				</td>
			</tr>
			<?php
		} elseif ($domainData['Domain expires']) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php printf(__(" This domain expires in %s.", "SDT"), sanitize_text_field($domainData['Domain expires'])); ?>
				</td>
			</tr>
			<?php
		} else {
			global $errorMessages;
			$errorMessages[] = __(" Domain expiration date not found", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Domain expiration date not found", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_domain_analysis_checks', 'sdt_this_domain_expires_in_days', 11);
