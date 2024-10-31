<?php
if (!function_exists('sdt_exact_keyword_in_h1h2')) {

	function sdt_exact_keyword_in_h1h2($args) {
		?>
		<section class="container-details-page">
			<h2  class="title-section">
				<?php _e('HEADING TAGS', "SDT"); ?>
			</h2>
			<article>
				<div class="tabla">
					<table>
						<tbody>
							<?php
							global $html;
							extract($args, EXTR_OVERWRITE);
							$countH1 = 0;
							$countH2 = 0;
							//size of keyword
							$size = strlen($keyword);
							/* get h1 */
							foreach ($html->find('h1') as $element) {
								$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
								if ($positionWord !== false) {
									$countH1++;
								}
							}
							/* get h2 */
							foreach ($html->find('h2') as $element) {
								$positionWord = sdt_contains_keyword($element->plaintext, $keyword);
								if ($positionWord !== false) {
									$countH2++;
								}
							}
							if ($countH1 > 0) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php printf(__(" Exact Keyword in H1: %d", "SDT"), (int) $countH1); ?>
									</td>
								</tr>
								<?php
							} else {
								global $errorMessages;
								$errorMessages[] = __(" Include keyword in the h1 tags, but do not duplicate.", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(" Keyword was not found on the h1 tags.", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
							if ($countH2 > 0) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php printf(__(" Exact Keyword in H2: %d", "SDT"), (int) $countH2); ?>
									</td>
								</tr>
								<?php
							} else {
								global $errorMessages;
								$errorMessages[] = __(" Include keyword in the h2 tags, but do not duplicate.", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(" Keyword was not found on the h2 tags.", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}
					add_action('sdt_code_headings_checks', 'sdt_exact_keyword_in_h1h2');
					