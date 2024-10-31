<?php
if (!function_exists('sdt_favicon_found')) {

	function sdt_favicon_found($args) {
		global $html;
		extract($args);

		foreach ($html->find('html') as $element) {
			$plain_html = $element;
		}

		$found = sdt_contains_keyword($plain_html, '<link rel="icon"') !== false;

		if (empty($found)) {
			global $errorMessages;
			$errorMessages[] = __(" Add a favicon to the header of page.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Favicon not Found.", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(" Favicon Found.", "SDT"); ?>
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
add_action('sdt_domain_analysis_checks', 'sdt_favicon_found', 13);