<?php
if (!function_exists('sdt_find_sitemap_link')) {

	function sdt_find_sitemap_link($args) {
		?>
		<section class="container-details-page"> 
			<h2  class="title-section">
				<?php _e('CODE ANALYSIS', "SDT"); ?>
			</h2>
			<article>
				<div class="tabla">
					<table>
						<tbody>
							<?php
							extract($args, EXTR_OVERWRITE);
							$protocol = array('http://', 'https://', 'ftp://', 'www.');
							$url = explode('/', $url);
							$domain = $url[0] . '//' . $url[2];

							$sitemap_response = wp_remote_get($domain . './sitemap.xml');
							$found = !is_wp_error($sitemap_response);
							if (empty($found)) {
								global $errorMessages;
								$errorMessages[] = __(" Add link to an xml sitemap.", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(" Link to a sitemap.xml was not found.", "SDT"); ?>
									</td>
								</tr>
								<?php
							} else {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e(" Link to a sitemap.xml found.", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}

					add_action('sdt_code_analysis_checks', 'sdt_find_sitemap_link');
					