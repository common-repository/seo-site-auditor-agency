<?php

if (!class_exists("SDT_Site_Audit")) {

	class SDT_Site_Audit {

		private static $instance;
		var $args = array();
		var $vg_plugin_sdk = null;

		private function __construct() {
			
		}

		public static function get_instance() {
			if (!isset(self::$instance)) {
				$myclass = __CLASS__;
				self::$instance = new $myclass;
				self::$instance->init();
			}
			return self::$instance;
		}

		public function __clone() {
			trigger_error("Clonation of this object is forbidden", E_USER_ERROR);
		}

		public function __wakeup() {
			trigger_error("You can't unserealize an instance of " . get_class($this) . " class.");
		}

		public function remove_directory_dots($files) {
			array_shift($files);
			array_shift($files);
			return $files;
		}

		public function late_init() {
			
		}

		public function init() {
			global $site_audit_settings;
			$site_audit_settings = get_option('site_audit_settings', array());
			$this->args = array(
				'main_plugin_file' => __FILE__,
				'show_welcome_page' => true,
				'welcome_page_file' => SDT_PATH . 'views/welcome-page-content.php',
				'logo' => SDT_URL . 'assets/img/logo.png',
				'demo_video_url' => 'https://www.youtube.com/embed/EG1NE3X5yNs?rel=0&amp;controls=0&amp;showinfo=0', // @todo
				'plugin_name' => 'Site Auditor',
				'plugin_prefix' => 'wpsewcc_',
				'plugin_version' => '1.2.1',
				'plugin_options' => get_option('site_audit_settings', false),
				'buy_link' => ssaa_fs()->get_trial_url(),
				'buy_link_text' => __('Try premium plugin for FREE - 14 Days'),
				'website' => 'https://greenjaymedia.com/wp-site-auditor/',
			);
			$this->vg_plugin_sdk = new VG_Freemium_Plugin_SDK($this->args);

			add_action("plugins_loaded", array($this, "late_init"));
			add_action("admin_menu", array($this, "register_menu_page"));
			$lang_path = str_replace(wp_normalize_path(WP_PLUGIN_DIR) . '/', '', wp_normalize_path(SDT_PATH . 'lang/'));
			load_plugin_textdomain("SDT", false, $lang_path);
		}

		function register_menu_page() {
			add_menu_page(
					$this->args['plugin_name'], $this->args['plugin_name'], 'manage_options', $this->args['plugin_prefix'] . 'welcome_page', array($this->vg_plugin_sdk, 'render_welcome_page'), SDT_URL . 'assets/img/icon.png'
			);
		}

	}

}

//Plugin instance
$SDT_plugin = SDT_Site_Audit::get_instance();
