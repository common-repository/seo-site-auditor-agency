<?php
if (!function_exists('sdt_found31_links_unique_domains')) {

	function sdt_found31_links_unique_domains($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$total_backlinks = !empty($mozAPI_data[0]['uid']) ? (int) $mozAPI_data[0]['uid'] : 0;
		?>
		<div class="card">
			<div class="heading-card">
				<h3><?php _e("Total Backlinks", "SDT"); ?></h3>
				<i class="fas fa-info-circle information"></i>
			</div>
			<div class="body-card">
				<?php
				if ($total_backlinks >= 3) {
					global $countSuccess;
					$countSuccess[] = 'is_correct';
					?> 
					<span class="number"><?php echo $total_backlinks; ?></span>
					<?php
				} else {
					global $errorMessages;
					$errorMessages[] = __(" You should have at least 3 backlinks.", "SDT");
					?> 
					<span class="text"><?php _e("This page has no backlinks.", "SDT"); ?></span>         
					<?php
				}
				?>
			</div>
		</div>
		</article>
		</section>
		<br>
		<br>
		<?php
	}

}
add_action('sdt_pagelinks_analysis_checks', 'sdt_found31_links_unique_domains', 14);
