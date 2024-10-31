<?php
if (!function_exists('sdt_bold_italicize_underline_exact_keyword')) {

	function sdt_bold_italicize_underline_exact_keyword($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$countBold = 0;
		$countIta = 0;
		$countUnd = 0;

		foreach ($html->find('strong') as $element) {
			$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
			if ($positionWord || $positionWord === 0) {
				$countBold++;
			}
		}
		foreach ($html->find('b') as $element) {
			$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
			if ($positionWord || $positionWord === 0) {
				$countItalicite++;
			}
		}
		foreach ($html->find('i') as $element) {
			$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
			if ($positionWord || $positionWord === 0) {
				$countUnderline++;
			}
		}
		if (
				$countBold != 0 &&
				$countItalicite != 0 &&
				$countUnderline != 0
		) {
			$total = $countBold + $countIta + $countUnd;
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 

			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Exact keyword is bolded, italicized, or underlined $total", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $errorMessages;
			$errorMessages[] = __(" Add your keyword and similar keywords as bolded, italicized, or underlined text.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Exact keywords were not found bolded, italicized, or underlined", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_copyanalysis_checks', 'sdt_bold_italicize_underline_exact_keyword', 12);