<?php
if (!function_exists('sdt_moz_page_authority26')) {

	function sdt_moz_page_authority26($args) {
		?>
		<section>
			<article class="links-analysis">
				<h2  class="title-section" style="text-align: center;">
					<?php _e('PAGE LINK ANALYSIS', "SDT"); ?>
				</h2>
				<?php
				extract($args, EXTR_OVERWRITE);
				$pageAuthority = $mozAPI_data[0]["pda"];
				?>
				<div class="card">
					<div class="heading-card">
						<h3><?php _e("Domain Authority", "SDT"); ?></h3>
						<i class="fas fa-info-circle information"></i>
					</div>
					<div class="body-card">
						<?php
						if ($pageAuthority) {
							global $countSuccess;
							$countSuccess[] = 'is_correct';
							?> 
							<span class="number"><?php _e($pageAuthority); ?></span>
							<?php
						} else {
							global $errorMessages;
							$errorMessages[] = __("Moz Page Authority Missing.", "SDT");
							?> 

							<span class="text"> <?php _e("Moz Page Authority Missing.", "SDT"); ?></span>

						<?php }
						?>
					</div>
				</div>
				<?php
			}

		}
		add_action('sdt_pagelinks_analysis_checks', 'sdt_moz_page_authority26', 12);