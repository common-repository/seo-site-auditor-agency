<?php
if (!function_exists('sdt_found_keyword_in_domain')) {

	function sdt_found_keyword_in_domain($args) {
		extract($args, EXTR_OVERWRITE);
		$wordFound = false;
		$protocol = array('http://', 'https://', 'ftp://', 'www.');
		$url = explode('/', str_replace($protocol, '', $url));
		$domain = $url[0];

		$position = sdt_contains_keyword($domain, str_replace(' ', '', $keyword));
		if ($position || $position === 0) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			$wordFound = true;
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(' Found keyword in domain.', "SDT"); ?>
				</td>
			</tr>
			<?php
		}

		if (!$wordFound) {
			global $errorMessages;
			$errorMessages[] = __("  If possible, add the keyword in the url.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(' the exact word was not found in the domain.', "SDT"); ?>
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
add_action('sdt_code_url_checks', 'sdt_found_keyword_in_domain', 10);