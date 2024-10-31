<?php
if (!function_exists('sdt_no_flash_found')) {

	function sdt_no_flash_found($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$swf = '.swf';
		$count = '';

		foreach ($html->find('embed') as $element) {
			$positionWord = sdt_contains_keyword($element->src, $swf);
			if ($positionWord) {
				$count = true;
			}
		}

		if (empty($count)) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Does not contain flash elements", "SDT"); ?>
				</td>
			</tr>
			<?php
		}

		if ($count) {
			global $errorMessages;
			$errorMessages[] = __("Remove Flash code from page.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Flash found", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_analysis_checks', 'sdt_no_flash_found', 11);