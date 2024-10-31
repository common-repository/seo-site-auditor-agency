<?php
if (!function_exists('sdt_how_fast_it_loaded')) {

	function sdt_how_fast_it_loaded($args) {
		global $countSuccess;
		global $errorMessages;
		extract($args, EXTR_OVERWRITE);
		$speed_category = (int) $dataGoogleSpeed['category'];
		//validate speed
		if (!empty($speed_category) || $speed_category != "unavailable") {
			$messageSpeed = '';
			if ($speed_category > 90) {
				$countSuccess[] = 'is_correct';
				?> 
				<tr class="success">
					<td colspan="3">
						<i class='fas fa-check'></i> <?php _e('The page speed is fast.', "SDT"); ?>
					</td>
				</tr>
				<?php
			} elseif ($speed_category > 80) {
				$countSuccess[] = 'is_correct';
				?> 
				<tr class="inter-middle">
					<td colspan="3">
						<i class="fas fa-exclamation-triangle"></i> <?php _e('The page speed is average.', "SDT"); ?>
					</td>
				</tr>
				<?php
			} elseif ($speed_category < 80) {
				$errorMessages[] = __("This page is very slow.", "SDT");
				?> 
				<tr class="error">
					<td colspan="3">
						<i class='fas fa-times'></i> <?php _e('The page speed is very low', "SDT"); ?>
					</td>
				</tr>
				<?php
			}
		} else {
			$errorMessages[] = __("Can not evaluate the speed of this site.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e('Can not evaluate the speed of this site', "SDT"); ?>
				</td>
			</tr>
			<?php
		}
	}

}

add_action('sdt_code_speed_checks', 'sdt_how_fast_it_loaded', 11);
