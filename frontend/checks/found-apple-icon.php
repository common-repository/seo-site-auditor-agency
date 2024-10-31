<?php
if (!function_exists('sdt_found_apple_icon')) {

	function sdt_found_apple_icon($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);

		foreach ($html->find('html') as $element) {
			$plain_html = $element;
		}

		$favicon = 'apple-touch-icon';
		$found = sdt_contains_keyword($plain_html, $favicon) !== false;

		if (!$found) {
			global $errorMessages;
			$errorMessages[] = __(" Add an apple icon to header of page.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" No apple icon found", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e("  Apple icon found ", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_mobile_analysis_checks', 'sdt_found_apple_icon', 11);