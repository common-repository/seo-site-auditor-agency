<?php

/*
 * @wordpress-plugin
 * Plugin Name: WP Site Auditor
 * Plugin URI: https://greenjaymedia.com/wp-site-auditor/
 * Description: Embed a form on any page to generate a SEO report for any website + keyword and email it to the user.
 * Version: 1.2.9
 * Author: Green Jay Media
 * Author URI: https://greenjaymedia.com
 * License: GPL2
 * Text Domain:         SDT
 * Domain Path:         /languages
  */

//Text domain para la funcion de traduccion 
if (!defined("SDT_TEXT_DOMAIN")) {
	define("SDT_TEXT_DOMAIN", "SDT");
}

if (!defined("SDT_PATH")) {
	define("SDT_PATH", plugin_dir_path(__FILE__));
}

if (!defined("SDT_URL")) {
	define("SDT_URL", plugin_dir_url(__FILE__));
}

if (!defined("STD_CHECK")) {
	define("STD_CHECK", SDT_PATH . 'frontend/checks');
}

if (!defined("SDT_EXPORTED_CSVS_FOLDER")) {
	define("SDT_EXPORTED_CSVS_FOLDER", 'exported-csvs');
}
if (!defined("SDT_POST_TYPE")) {
	define("SDT_POST_TYPE", 'wpsa_submissions');
}

if (!defined("SDT_RESULTS_PATH")) {
	define("SDT_RESULTS_PATH", ABSPATH . "wp-content/site-auditor-results");
}
if (function_exists('ssaa_fs')) {
	ssaa_fs()->set_basename(true, __FILE__);
}

function sdt_get_files_list($directory_path, $file_format = '.php') {
	$files = glob(trailingslashit($directory_path) . '*' . $file_format);
	return $files;
}

require_once SDT_PATH . "vendor/autoload.php";

if (!class_exists("ReadCsv")) {
	require_once SDT_PATH . "vendor/vg-csv-libraries/read-csv.php";
}

if (!function_exists("vg_array_to_csv") && !function_exists("vg_get_csv")) {
	require_once SDT_PATH . "vendor/vg-csv-libraries/vegacorp-csv.php";
}

require_once SDT_PATH . "vendor/freemius/start.php";
require_once SDT_PATH . "inc/freemius-init.php";
require_once SDT_PATH . "vendor/vg-plugin-sdk/index.php";
require_once SDT_PATH . 'vendor/vg-plugin-sdk/settings-page.php';
require_once SDT_PATH . "inc/init.php";

$files = array_merge(sdt_get_files_list(SDT_PATH . "inc"), sdt_get_files_list(SDT_PATH . "backend"), sdt_get_files_list(SDT_PATH . "frontend"), sdt_get_files_list(SDT_PATH . "frontend/checks"));
foreach ($files as $key => $file) {
	require_once $file;
}
require_once SDT_PATH . "views/frontend/site-audit-shortcode-views.php";
