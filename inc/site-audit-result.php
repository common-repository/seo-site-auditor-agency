<?php
$GLOBALS['path'] = "";
if (!class_exists("SDT_Result")) {

	class SDT_Result {

		private $sdt_nonce_key = "sdt_nonce";

		public function __construct() {
			$this->init();
		}

		function render_html_report() {
			if (!is_singular('page')) {
				return;
			}
			$post = get_queried_object();
			if (strpos($post->post_content, '[site_audit_result]') === false) {
				return;
			}
			$files = $this->get_report_files();
			if (empty($files) || strpos($files['file_path'], '.txt') === false) {
				return;
			}
			$body = wp_kses_post(file_get_contents($files['file_path']));
			$body = str_replace('src="image/jpeg', 'src="data:image/jpeg', $body);
			?>
			<html>
				<?php sdt_render_header(); ?>
				<style>
					.loader {
						display: none;
					}
					body {
						max-width: 1100px;
						margin: 30px auto;
					}
				</style>
				<body>
					<?php echo $body; ?>
				</body>
			</html>
			<?php
			die();
		}

		function get_report_files() {
			$lastUrl = null;
			if (!empty($_GET['su_report'])) {
				$base_file_path = SDT_RESULTS_PATH . '/' . preg_replace("/[^a-z0-9_\-\s]+/i", "", $_GET['su_report']);
				$img_file_path = $base_file_path . '.jpg';
				if (file_exists($img_file_path)) {
					$lastUrl = $img_file_path;
				}
				$txt_file_path = $base_file_path . '.txt';
				if (!$lastUrl && file_exists($txt_file_path)) {
					$lastUrl = $txt_file_path;
				}
			}

			if (!empty($_GET['url']) && empty($lastUrl)) {
				$url = esc_url($_GET['url']);
				$resultImg = glob(SDT_RESULTS_PATH . "/*");
				$encryptUrl = md5($url);

				foreach ($resultImg as $img) {
					if (sdt_contains_keyword($img, $encryptUrl) !== false) {
						$lastUrl = $img;
					}
				}
			}
			if (!empty($lastUrl)) {
				$out = array(
					'url' => str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $lastUrl),
				);
				$out['file_path'] = $lastUrl;
			} else {
				$out = false;
			}
			return $out;
		}

		public function render_img_report($atts = array(), $content = '') {
			$files = $this->get_report_files();
			ob_start();
			if (!empty($files)) {
				$newUrl = $files['url'];
				$newPath = $files['file_path'];
				include SDT_PATH . 'views/frontend/img-template.php';
			} else {
				?>
				<h2><?php _e('Audit results not found', "SDT"); ?></h2>
				<?php
			}
			return ob_get_clean();
		}

		public function init() {
			add_action('template_redirect', array($this, 'render_html_report'));
			add_shortcode("site_audit_result", array($this, "render_img_report"));
		}

	}

	$sdt_result = new SDT_Result();
}