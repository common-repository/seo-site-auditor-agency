<?php
if (!function_exists('sdt_keyword_begins_title')) {

	function sdt_keyword_begins_title($args) {
		?>
		<section class="container-details-page">
			<article>
				<h2 class="title-section"><?php _e("TITLE TAG", "SDT"); ?></h2>
				<div class="tabla">
					<table>   
						<tbody>
							<?php
							global $html;
							extract($args, EXTR_OVERWRITE);
							$title = $html->find('title', 0);
							$position = sdt_contains_keyword($title->plaintext, $keyword);
							if (!$position) {
								global $errorMessages;
								$errorMessages[] = __(" Include keyword at beginning of title.", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(' The title does not begin with the keyword.', "SDT"); ?>
									</td>
								</tr>
								<?php
							}
							if ($position === 1 || $position === 0) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e('  Keyword begins title.', "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}

					add_action('sdt_code_title_checks', 'sdt_keyword_begins_title', 10);
					