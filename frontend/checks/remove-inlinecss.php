<?php
if (!function_exists('sdt_remove_inlinecss')) {

	function sdt_remove_inlinecss($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);

		foreach ($html->find('body') as $element) {
			$textplain = $element->plaintext;
		}

		if (sdt_contains_keyword($textplain, 'style="') !== false) {
			global $errorMessages;
			$errorMessages[] = __(" Move inline css to a stylesheet.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" inline found", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" No inline css found. ", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_analysis_checks', 'sdt_remove_inlinecss');