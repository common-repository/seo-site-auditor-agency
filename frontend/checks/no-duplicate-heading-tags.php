<?php
/**
 * Function to check if some h1 or h2
 * repeat the same content
 * 
 * @param Array $args
 */
if (!function_exists('sdt_no_duplicate_heading_tags')) {

	function sdt_no_duplicate_heading_tags($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$countH1 = 0;
		$countH2 = 0;
		/* get h1 */
		foreach ($html->find('h1') as $element) {
			$arrayH1[] = $element->plaintext;
		}
		if (!empty($arrayH1)) {
			$repeteadH1 = array_filter(array_count_values($arrayH1), function($count) {
				return $count > 1;
			});
			foreach ($repeteadH1 as $key => $value) {
				$countH1++;
			}
		}


		/* get h2 */
		foreach ($html->find('h2') as $element) {
			$arrayH2[] = $element->plaintext;
		}
		if (!empty($arrayH2)) {
			$repeteadH2 = array_filter(array_count_values($arrayH2), function($count) {
				return $count > 1;
			});
			foreach ($repeteadH2 as $key => $value) {
				$countH2++;
			}
		}



		if ($countH2 != 0 && $countH2 != 0) {
			global $errorMessages;
			$errorMessages[] = __(" $total duplicate heading tags. Edit headings to make unique.", "SDT");
			$total = $countH1 + $countH2;
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" There are duplicate heading tags $total", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" There are no duplicate heading tags", "SDT"); ?>
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
add_action('sdt_code_headings_checks', 'sdt_no_duplicate_heading_tags');