<?php
if (!function_exists('sdt_screenshot_page')) {

	function sdt_screenshot_page($args) {
		extract($args, EXTR_OVERWRITE);
		?>
		<section class="capture-wrapper" style="background: url(<?php echo SDT_URL . "assets/img/monitor.png"; ?>) no-repeat 0 0;     background-size: 100%;">

			<?php
			if ($dataGoogleSpeed['screenshot'] == "unavailable" || empty($dataGoogleSpeed['screenshot'])) {
				?>
				<img src="<?php SDT_PATH . "assets/img/no-screenshot.png"; ?>" class="capture">
				<?php
			} else {
				$screen_shot = $dataGoogleSpeed['screenshot'];

				$screen_shot = str_replace(array('_', '-'), array('/', '+'), $screen_shot);
				$screen_shot_image = $screen_shot;
				?>
				<img src="<?php echo $screen_shot_image; ?>" class="capture">
				<?php
			}
			?>

		</section>
		<?php
	}

}
add_action('sdt_screenShot', 'sdt_screenshot_page');
