<?php
if (!function_exists('sdt_page_data_google')) {

	function sdt_page_data_google($args) {
		?>
		<section class="container-details-page container-section-speed">
			<article>
				<h2 class="title-section"><?php _e("SPEED", "SDT"); ?></h2>
				<div class="tabla">
					<table>              
						<thead>
							<tr>
								<?php
								extract($args, EXTR_OVERWRITE);
								$fastLoaded = $dataGoogleSpeed['fastLoaded'];
								$sizeSite = $dataGoogleSpeed['siteSize'];
								$assetsLoaded = $dataGoogleSpeed['assetsLoaded'];

								if (!empty($fastLoaded) && $fastLoaded != "unavailable") {
									?>
									<th style="background-color: <?php echo ( $fastLoaded < 90 ) ? '#c7c710' : ''; ?>"><?php _e("SCORE", "SDT"); ?> <span><?php echo (int) $fastLoaded; ?></span></th>
										<?php
									} else {
										?>
									<th><span><?php _e("the load time is unavailable", "SDT"); ?></span></th>
									<?php
								}

								if (!empty($sizeSite) && $sizeSite != "unavailable") {

									$sizeSite = formatBytes($sizeSite, 2, true);
									?>
									<th><?php _e("PAGE SIZE", "SDT"); ?><span ><?php echo $sizeSite; ?></span></th> 
									<?php
								} else {
									?>
									<th><span><?php _e("The page size is unavailable.", "SDT"); ?></span></th>
									<?php
								}

								if (!empty($assetsLoaded) && $assetsLoaded != "unavailable") {
									?>
									<th><?php _e("REQUESTS", "SDT"); ?><span><?php echo $assetsLoaded; ?></span></th><?php
								} else {
									?>
									<th><?php _e("the assets loaded is unavailable", "SDT"); ?></th>
									<?php
								}
								?>
							</tr>
						</thead>
						<?php
					}

				}
				add_action('sdt_code_speed_checks', 'sdt_page_data_google', 10);
				