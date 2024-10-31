<?php
if (!function_exists('sdt_html_ratio_is_too_low25')) {

	function sdt_html_ratio_is_too_low25($args) {
		global $html;
		$minimum_ratio = 25;
		extract($args, EXTR_OVERWRITE);
		foreach ($html->find('body') as $element) {
			$textplain = $element->plaintext;
		}
		$body = wp_strip_all_tags($textplain);
		$body = strlen($body);
		foreach ($html->find('html') as $element) {
			$htmlTextPlain = $element;
		}
		$htmlTextPlain = strlen($htmlTextPlain);
		//calculate Percent
		$percent = round($body / $htmlTextPlain * 100, 2);
		if ($percent < $minimum_ratio) {
			global $errorMessages;
			$errorMessages[] = sprintf(__("Text to HTML ratio should be > %d%%.", "SDT"), (int) $minimum_ratio);
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php printf(__(" Text to HTML ratio is < %d%%", "SDT"), (int) $minimum_ratio); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Text to HTML ratio is excellent.", "SDT"); ?>
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
		<br>
		<?php
	}

}
add_action('sdt_code_analysis_checks', 'sdt_html_ratio_is_too_low25', 13);
