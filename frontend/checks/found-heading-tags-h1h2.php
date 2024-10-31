<?php
if (!function_exists('sdt_found_heading_tags_h1h2')) {

	function sdt_found_heading_tags_h1h2($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$countH1 = 0;
		$countH2 = 0;

		/* get h1 */
		foreach ($html->find('h1') as $element) {
			$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
			$countH1++;
		}
		/* get h2 */
		foreach ($html->find('h2') as $element) {
			$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
			$countH2++;
		}
		if (!$countH1 && !$countH2) {
			global $errorMessages;
			$errorMessages[] = __(" Add heading tags to page, using keyword: H1 & H2.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Heading tags not found: H1 & H2", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			$total = $countH1 + $countH2;
			?> 

			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php printf(__(" Found %d heading tags: H1 & H2", "SDT"), (int) $total); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_headings_checks', 'sdt_found_heading_tags_h1h2');
