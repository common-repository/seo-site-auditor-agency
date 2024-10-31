<?php
if (!function_exists('sdt_title_tag_has_70')) {

	function sdt_title_tag_has_70($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$title = $html->find('title', 0);
		$titleSize = strlen($title->plaintext);
		$message = "";
		if ($titleSize > 70) {
			global $errorMessages;
			$errorMessages[] = __("Reduce the title tag to less than 70 characters", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" the title tag has $titleSize more than 70 characters", "SDT"); ?>
				</td>
			</tr>
			<?php
		} elseif ($titleSize <= 70) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e("the title tag has {$titleSize} less than  70 characters", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}

//add_action('sdt_code_title_checks', 'sdt_title_tag_has_70', 12);