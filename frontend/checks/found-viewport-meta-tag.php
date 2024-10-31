<?php
if (!function_exists('sdt_found_viewport_meta_tag')) {

	function sdt_found_viewport_meta_tag($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$viewport = 'viewport';
		$notif = '';
		foreach ($html->find('meta') as $element) {

			$foundMeta = sdt_contains_keyword($element->name, $viewport);
			if ($foundMeta || $foundMeta === 0) {
				$notif = true;
			}
		}
		if (empty($notif)) {
			global $errorMessages;
			$errorMessages[] = __(" Meta viewport tag needs to be added to header.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e("  Meta viewport tags not found.", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Meta viewport tags found.", "SDT"); ?>
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
add_action('sdt_mobile_analysis_checks', 'sdt_found_viewport_meta_tag', 12);