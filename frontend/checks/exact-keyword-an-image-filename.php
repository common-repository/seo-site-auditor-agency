<?php
if (!function_exists('sdt_exact_keyword_an_image_filename')) {

	function sdt_exact_keyword_an_image_filename($args) {
		extract($args, EXTR_OVERWRITE);
		global $html;
		$count = 0;
		foreach ($html->find('img') as $img) {
			$positionWord = sdt_contains_keyword($img->src, $keyword);
			if ($positionWord || $positionWord === 0) {
				$count++;
			}
		}

		if (!$count) {
			global $errorMessages;
			$errorMessages[] = __(" Include keyword in an image file name, using dashes between words.  Example: image-file-name.jpg", "SDT");
			?> 
			<tr class="error">
				<td colspan="3">
					<i class='fas fa-times'></i> <?php _e(" Not found exact keyword in an image file name", "SDT"); ?>
				</td>
			</tr>
			<?php
		} else {
			global $countSuccess;
			$countSuccess[] = 'is_correct';
			?> 
			<tr class="success">
				<td colspan="3">
					<i class='fas fa-check'></i> <?php _e("Found exact keyword in an image file name", "SDT"); ?>
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

add_action('sdt_code_image_checks', 'sdt_exact_keyword_an_image_filename', 12);