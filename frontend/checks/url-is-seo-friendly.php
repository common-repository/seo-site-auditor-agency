<?php
if (!function_exists('sdt_url_is_seo_friendly')) {

	function sdt_url_is_seo_friendly($args) {
		?>
		<section class="container-details-page">    
			<!-- URL -->
			<article>
				<h2>URL</h2>
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
							extract($args, EXTR_OVERWRITE);
							if (preg_match('/^((http[s]?):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.])(.)([\w\-]+)?$/i', $url)) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e('URL is SEO friendly.', "SDT"); ?>
									</td>
								</tr>
								<?php
							} elseif (wp_http_validate_url($url)) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e('URL is SEO friendly.', "SDT"); ?>
									</td>
								</tr>
								<?php
							} else {
								global $errorMessages;
								$errorMessages[] = __(" URL is not SEO friendly. For best seo results, only use letters or dashes in your url when possible - https://www.example.com/like-this/", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e(' URL is not SEO friendly.', "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}
					add_action('sdt_code_url_checks', 'sdt_url_is_seo_friendly', 9);
					