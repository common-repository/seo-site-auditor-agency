<?php
if (!function_exists('sdt_found_schemaorg_markup')) {

	function sdt_found_schemaorg_markup($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$schema = 'schema.org';
		$count = 0;

		foreach ($html->find('html') as $element) {
			$plain_html = $element;
		}

		$found = sdt_contains_keyword($plain_html, $schema) !== false;


		if ($found) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Found schema.org markup.", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $errorMessages;
			$errorMessages[] = __(" Add structured data (schema.org) to page.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Schema.org markup not found.", "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}
add_action('sdt_code_analysis_checks', 'sdt_found_schemaorg_markup');