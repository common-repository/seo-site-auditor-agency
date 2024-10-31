<?php
if (!function_exists('sdt_moz_this_page_has3outbound_links')) {

	function sdt_moz_this_page_has3outbound_links($args) {
		extract($args, EXTR_OVERWRITE);
		$outbound_links = !empty($mozAPI_data[0]["ueid"]) ? (int) $mozAPI_data[0]["ueid"] : 0;
		?>
		<div class="card">
			<div class="heading-card">
				<h3><?php _e("DoFollow Backlinks", "SDT"); ?></h3>
				<i class="fas fa-info-circle information"></i>
			</div>
			<div class="body-card">
				<?php
				if ($outbound_links >= 3) {
					global $countSuccess;
					$countSuccess[] = 'is_correct';
					?> 
					<span class="number"><?php echo $outbound_links; ?></span>
					<?php
				} else {
					global $errorMessages;
					$errorMessages[] = __(" You should have at least 3 dofollow backlinks.", "SDT");
					?> 
					<span class="text"><?php _e("This page has no dofollow backlinks.", "SDT"); ?></span>         
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

}
add_action('sdt_pagelinks_analysis_checks', 'sdt_moz_this_page_has3outbound_links', 13);
