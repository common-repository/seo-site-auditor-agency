<?php
if (!function_exists('sdt_how_many_assets_were_loaded')) {

	function sdt_how_many_assets_were_loaded($args) {
		extract($args, EXTR_OVERWRITE);
		$countResources = $dataGoogleSpeed['assetsLoaded'];
		if (!empty($countResources) || $countResources != "unavailable") {

			switch ($countResources) {
				case $countResources <= 100:
					global $countSuccess;
					$countSuccess[] = 'is_correct';
					?> 
					<tr class="success">
						<td colspan="3">
							<i class='fas fa-check'></i> <?php _e('The assets number is excellent.', "SDT"); ?>
						</td>
					</tr>
					<?php
					break;
				case $countResources == 100 && $countResources <= 200:
					global $countSuccess;
					$countSuccess[] = 'is_correct';
					?> 
					<tr class="success">
						<td colspan="3">
							<i class='fas fa-exclamation-circle'></i> <?php _e('The assets number is acceptable.', "SDT"); ?>
						</td>
					</tr>
					<?php
					break;
				case $countResources > 200:
					global $errorMessages;
					$errorMessages[] = __("Reduce the number of page requests to improve load speed. 150 or less is ok. The lower the number, the better.", "SDT");
					?> 
					<tr class="error">
						<td colspan="3">
							<i class='fas fa-times'></i> <?php _e('Reduce the number of page requests to 150 or less.', "SDT"); ?>
						</td>
					</tr>
					<?php
					break;
				default:
					global $errorMessages;
					$errorMessages[] = __("Can not get the number of assets.", "SDT");
					?> 
					<tr class="error">
						<td colspan="3">
							<i class='fas fa-times'></i> <?php _e('Can not get the number of assets', "SDT"); ?>
						</td>
					</tr>
					<?php
					break;
			}
		} else {
			global $errorMessages;
			$errorMessages[] = __("Can not get the number of assets.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e('Can not get the number of assets', "SDT"); ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		</div>
		</article>
		</section>
		<?php
	}

}

add_action('sdt_code_speed_checks', 'sdt_how_many_assets_were_loaded', 13);
