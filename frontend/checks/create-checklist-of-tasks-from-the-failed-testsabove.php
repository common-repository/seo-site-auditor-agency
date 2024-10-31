<?php
if (!function_exists('sdt_create_checklist_of_tasks_from_the_failed_testsabove')) {

	function sdt_create_checklist_of_tasks_from_the_failed_testsabove($args) {
		?>
		<section class="container-details-page container-task">
			<h2  class="title-section">
				<?php _e('TASKS', "SDT"); ?>
			</h2>
			<article>
				<div class="tabla">
					<table> 
						<tbody>
							<?php
							extract($args, EXTR_OVERWRITE);
							foreach ($error_messages as $error_message) {
								?> 
								<tr>
									<td>
										<i class='chart'></i> <?php echo $error_message ?>
									</td>
								</tr>
								<?php
								$countErrors = count($error_messages);
								$countGood = count($count_success);
							}
							?>
						<input type="hidden" id="issuesFound" value="<?php echo esc_attr($countErrors); ?>">
						<input type="hidden" id="goodSignal" value="<?php echo esc_attr($countGood); ?>">
						</tbody>
					</table>
				</div>
			</article>
		</section>
		<?php
	}

}
add_action('sdt_tasklist_above', 'sdt_create_checklist_of_tasks_from_the_failed_testsabove');
