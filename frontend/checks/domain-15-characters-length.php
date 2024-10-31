<?php
if (!function_exists('sdt_domain_15_characters_length')) {

	function sdt_domain_15_characters_length($args) {
		extract($args, EXTR_OVERWRITE);
		$domain = str_replace(array(
			'http://',
			'https://',
			'//',
				), '', parse_url($url, PHP_URL_HOST));
		$domain = rtrim($domain, '.com');
		$domain = rtrim($domain, '.net');
		$domain = rtrim($domain, '.org');
		$domain = rtrim($domain, '.es');
		$domain = rtrim($domain, '.us');
		?>   
		<section class="container-details-page">
			<h2  class="title-section">
				<?php _e('DOMAIN ANALYSIS', "SDT"); ?>
			</h2>
			<article>
				<div class="tabla">
					<table>
						<thead>
							<tr> 
								<th class="info-extr txt-light-grey" >
									<?php
									_e($args['url']);
									?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$message = '';

							$lenght = strlen($domain);
							if ($lenght <= 20) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e(" Domain contains < 20 characters in length ", "SDT"); ?>
									</td>
								</tr>
								<?php
							} else {
								global $errorMessages;
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(" Domain contains > 20 characters in length", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}

					add_action('sdt_domain_analysis_checks', 'sdt_domain_15_characters_length');
					