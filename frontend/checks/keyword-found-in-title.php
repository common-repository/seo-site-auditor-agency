<?php
if (!function_exists('std_keyword_found_in_title')) {

	function std_keyword_found_in_title($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		$title = $html->find('title', 0);
		$foundKeyword = sdt_contains_keyword($title->plaintext, $keyword);

		if (!$foundKeyword) {
			global $errorMessages;
			$errorMessages[] = __(" Include keyword in title, preferably at the beginning.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(' Keyword not found in title.', "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e(' Keyword found in title.', "SDT"); ?>
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
		<br>
		<?php
	}

}

add_action('sdt_code_title_checks', 'std_keyword_found_in_title', 11);