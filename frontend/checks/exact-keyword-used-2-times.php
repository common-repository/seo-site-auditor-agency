<?php
if (!function_exists('sdt_exact_keyword_used_2_times')) {

	function sdt_exact_keyword_used_2_times($args) {
		?>
		<section class="container-details-page">
			<h2  class="title-section">
				<?php _e('COPY ANALYSIS', "SDT"); ?>
			</h2>
			<article>
				<div class="tabla">
					<table>
						<tbody>
							<?php
							global $html;
							extract($args, EXTR_OVERWRITE);
							foreach ($html->find('html') as $element) {
								$content = $element->plaintext . '<br>';
							}
							$words = explode(" ", $content);
							$twoTimes = 0;
							foreach ($words as $word) {
								if ($word === $keyword) {
									$twoTimes++;
								}
							}
							if ($twoTimes != 4) {
								global $errorMessages;
								$errorMessages[] = __("  Use the exact keyword more than 2-4 times", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(" Use the exact keyword more than 2-4 times", "SDT"); ?>
									</td>
								</tr>
								<?php
							} else {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 

								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e(" Only use exact keyword 2-4 times", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}

					add_action('sdt_code_copyanalysis_checks', 'sdt_exact_keyword_used_2_times', 10);
					