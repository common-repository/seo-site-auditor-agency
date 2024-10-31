<?php
if (!function_exists('sdt_exact_keyword_found_the_first100words')) {

	function sdt_exact_keyword_found_the_first100words($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$count = 0;
		foreach ($html->find('html') as $element) {
			$content = strtolower($element->plaintext);
		}


		$words = strip_tags($content);
		//delete whitespaces
		$words = preg_replace('/\s+/', ' ', trim($words));
		//create array 
		$words = explode(" ", $words);
		$words = array_filter(array_map('trim', $words));

		$oneHundred = array_slice($words, 0, 100);
		foreach ($oneHundred as $one) {
			if ($one === strtolower($keyword)) {
				$count++;
			}
		}

		if ($count != 0) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 

			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php printf(__(" Exact keyword found in the first 100 words, %d times", "SDT"), (int) $count); ?>
				</td>
			</tr>
			<?php
		} else {
			global $errorMessages;
			$errorMessages[] = __("  Include keyword in the first 100 words of page.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e("  Exact keyword was not found in the first 100 words", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_copyanalysis_checks', 'sdt_exact_keyword_found_the_first100words', 14);
