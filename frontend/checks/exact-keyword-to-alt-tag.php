<?php
if (!function_exists('sdt_exact_keyword_to_alt_tag')) {

	function sdt_exact_keyword_to_alt_tag($args) {
		extract($args, EXTR_OVERWRITE);
		global $html;
		$count = 0;
		foreach ($html->find('img') as $img) {
			$positionWord = sdt_contains_keyword($img->alt, $keyword);
			if ($positionWord || $positionWord === 0) {
				$count++;
			}
		}
		if (!$count) {
			global $errorMessages;
			$errorMessages[] = __(" Include keyword in the alt tag of an image.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Exact keyword was not found on an alt tag", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e("  Exact keyword found in an alt tag", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}

add_action('sdt_code_image_checks', 'sdt_exact_keyword_to_alt_tag', 11);