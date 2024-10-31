<?php
if (!function_exists('sdt_size_page')) {

	function sdt_size_page($args) {
		extract($args, EXTR_OVERWRITE);


		$sizeSite = $dataGoogleSpeed['siteSize'];
		$sizeSite = (int) $sizeSite;
		?>
		<tbody>
			<?php
			if (!empty($sizeSite) || $sizeSite != "unavailable") {
				$sizeSite = formatBytes($sizeSite, 2, false);
				$sizeSite = (double) $sizeSite;
				$limitSize = (double) 2.50;
				if ($sizeSite <= $limitSize) {
					global $countSuccess;
					$countSuccess[] = 'is_correct';
					?> 
					<tr class="success">
						<td colspan="3">
							<i class='fas fa-check'></i> <?php _e("the page size is ok", "SDT"); ?>
						</td>
					</tr>
					<?php
				} else {
					global $errorMessages;
					$errorMessages[] = __("Reduce the page size to less than 2.5MB, for better load speed.", "SDT");
					?> 
					<tr class="error">
						<td colspan="3">
							<i class='fas fa-times'></i> <?php _e('Reduce the page size', "SDT"); ?>
						</td>
					</tr>
					<?php
				}
			} else {
				global $errorMessages;
				$errorMessages[] = __("the page size is unavilable", "SDT");
				?> 
				<tr class="error">
					<td colspan="3">
						<i class='fas fa-times'></i> <?php _e('the page size is unavilable', "SDT"); ?>
					</td>
				</tr>
				<?php
			}
		}

	}
	add_action('sdt_code_speed_checks', 'sdt_size_page', 12);