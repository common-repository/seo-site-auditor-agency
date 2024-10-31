<?php
if (!function_exists('sdt_should_have_1000_words')) {

	function sdt_should_have_1000_words($args) {
		global $html;
		extract($args, EXTR_OVERWRITE);
		foreach ($html->find('html') as $element) {
			$content = $element->plaintext . '<br>';
		}
		$words = strip_tags($content);
		//delete whitespaces
		$words = preg_replace('/\s+/', ' ', trim($words));
		//create array 
		$words = explode(" ", $words);
		$words = array_filter(array_map('trim', $words));

		$countword = count($words);

		if ($countword >= 1000) {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 

			<tr class="success">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php printf(__(" This page has %d words, more than 1,000 words", "SDT"), (int) $countword); ?>
				</td>
			</tr>
			<?php
		} else {
			$errorMessages[] = __(" Add more words to the page.  1,000 words or more.", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php printf(__(" This page has %d words, less than 1,000 words", "SDT"), (int) $countword); ?>
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

add_action('sdt_code_copyanalysis_checks', 'sdt_should_have_1000_words', 16);
