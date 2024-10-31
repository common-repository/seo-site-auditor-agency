<?php
if (!function_exists('sdt_every_image_needs_alt_tag')) {

	function sdt_every_image_needs_alt_tag($args) {
		?>
		<section class="container-details-page">  
			<!-- image Analysis -->
			<article>
				<h2 class="title-section"><?php _e('IMAGE ANALYSIS', "SDT"); ?>  </h2>
				<div class="tabla">
					<table>
						<tbody>
							<?php
							global $html;
							extract($args, EXTR_OVERWRITE);

							$message = "";
							$imgs = $html->find('img');
							$countAllImg = 0;
							foreach ($imgs as $img) {
								$countAllImg++;
							}
							$countAlt = 0;
							foreach ($imgs as $img) {
								$alt[] = $img->alt;
							}
							if (empty($alt)) {
								$alt[] = array();
							}
							$countAlt = count($alt);

							if ($countAllImg == $countAlt) {
								global $countSuccess;
								$countSuccess[] = 'is_correct';
								?> 
								<tr class="success">
									<td colspan="3">
										<i class='fas fa-check'></i> <?php _e(" Every tag img has alt attribute", "SDT"); ?>
									</td>
								</tr>
								<?php
							} elseif ($countAlt < $countAllImg) {
								global $errorMessages;
								$errorMessages[] = __(" All images need keywords in the alt text.  Add your targeted keyword to at least 1 image and similar keywords to other images.", "SDT");
								?> 
								<tr class="error">
									<td colspan="3">
										<i class='fas fa-times'></i> <?php _e("  Some img tag does not have alt text", "SDT"); ?>
									</td>
								</tr>
								<?php
							}
						}

					}
					add_action('sdt_code_image_checks', 'sdt_every_image_needs_alt_tag', 10);
					