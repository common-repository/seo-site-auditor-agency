<?php
if (!function_exists('std_this_domain_is_years_old')) {

	function std_this_domain_is_years_old($args) {
		extract($args, EXTR_OVERWRITE);
		if ($domainData['Domain created']) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php printf(__(" This domain is %s old", "SDT"), sanitize_text_field($domainData['Domain created'])); ?>
				</td>
			</tr>
			<?php
		} else {
			global $errorMessages;
			$errorMessages[] = __("This domain is only missing old", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" This domain is only missing old", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_domain_analysis_checks', 'std_this_domain_is_years_old');
