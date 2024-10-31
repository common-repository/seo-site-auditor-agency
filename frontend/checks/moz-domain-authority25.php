<?php
if (!function_exists('sdt_moz_domain_authority25')) {

	function sdt_moz_domain_authority25($args) {
		extract($args, EXTR_OVERWRITE);
		$domainAuthority = $mozAPI_data[0]["upa"];
		?>
		<div class="card">
			<div class="heading-card">
				<h3><?php _e('Page Authority', "SDT"); ?> </h3>
				<i class="fas fa-info-circle information"></i>
			</div>
			<div class="body-card">
				<?php
				if ($domainAuthority) {
					global $countSuccess;
					$countSuccess[] = 'is_correct';
					?> 
					<span class="number"><?php _e($domainAuthority); ?></span>

					<?php
				} else {
					global $errorMessages;
					$errorMessages[] = __("Moz Domain Authority Missing.", "SDT");
					?> 
					<span class="text"><?php _e(' Moz Domain Authority Missing.', "SDT"); ?></span>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

}
add_action('sdt_pagelinks_analysis_checks', 'sdt_moz_domain_authority25', 13);