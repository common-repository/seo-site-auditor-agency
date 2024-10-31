<?php

use Sunra\PhpSimple\HtmlDomParser;
use Iodev\Whois\Whois;
use Iodev\Whois\Exceptions\ServerMismatchException;

//globals

$GLOBALS['errorMessages'] = array();
$GLOBALS['countSuccess'] = array();
// Create DOM from URL or file
$GLOBALS['html'] = new HtmlDomParser();

$GLOBALS['whois'] = Whois::create();

if (!function_exists('sdt_contains_keyword')) {

	function sdt_contains_keyword($string, $keyword) {
		return strpos(strtolower($string), strtolower($keyword));
	}

}
if (!function_exists('sdt_get_domain')) {

	function sdt_get_domain($url, $show = false) {
		$protocol = array('http://', 'https://', 'ftp://', 'www.');
		$urlSite = explode('/', str_replace($protocol, '', $url));
		$domain = $urlSite[0];

		if (!$show) {
			return $domain;
		}
		echo $domain;
	}

}

if (!function_exists('formatBytes')) {

	function formatBytes($size, $precision = 2, $value = false) {
		//$base = log($size, 1024);
		if (!$value) {
			$mb = number_format($size / 1048576, 2);
			return $mb;
		}
		return number_format($size / 1048576, 2) . ' MB';
	}

}
/**
 * Data from headers
 * 
 * Domain created and Expiries
 * 
 */
if (!function_exists('sdt_domain_data')) {

	function sdt_domain_data($args) {
		extract($args, EXTR_OVERWRITE);
		global $whois;
		$domain = sdt_get_domain($url);
		try {
			$transient_key = 'vgsa_whois_' . md5($domain);
			$info = get_transient($transient_key);

			if (!$info) {
				$info = $whois->loadDomainInfo($domain);
				set_transient($transient_key, $info, DAY_IN_SECONDS * 2);
			}

			if (!$info) {
				$data_server = array(
					'Domain created' => __('0', "SDT"),
					'Domain expires' => __('0', "SDT"),
					'expiration_timestamp' => strtotime('+9 years')
				);
				return $data_server;
			}
			$created = $info->getCreationDate();
			$expires = $info->getExpirationDate();
			if (!empty($created) && !empty($expires)) {

				$now = time(); // or your date as well
				$datediff = $expires - $now;
				$expiration_days = round($datediff / (60 * 60 * 24));
				$days = (int) round(($now - $created) / (60 * 60 * 24));

				if ($days < 365) {
					$age_text = sprintf(__('%d days', "SDT"), $days);
				} else {
					$age_text = sprintf(__('%d years', "SDT"), round($days / 365, 1));
				}
				if ($expiration_days < 365) {
					$expiration_text = sprintf(__('%d days', "SDT"), $expiration_days);
				} else {
					$expiration_text = sprintf(__('%d years', "SDT"), round($expiration_days / 365, 1));
				}

				$data_server = array(
					'Domain created' => $age_text,
					'Domain expires' => $expiration_text,
					'expiration_timestamp' => $expires
				);
				return $data_server;
			}
		} catch (ServerMismatchException $e) {
			//_e("TLD server (".$domain .") not found in current server hosts", "SDT");
			return array(
				'Domain created' => "",
				'Domain expires' => ""
			);
		}
	}

}

/**
 * Speed Google pageSpeed Insigths API
 */
if (!function_exists('std_google_page_speed_insights_api')) {

	function std_google_page_speed_insights_api($args, $viewData = false) {
		extract($args, EXTR_OVERWRITE);
		$settings = get_option('site_audit_settings', __('This option not found', "SDT"));
		$ch = curl_init();

		$page_speed_api_key = sdt_credentialsapi($settings, "googleSpeedpage"); //your API key

		$transient_key = 'vgsa_google_' . md5($url);
		$jsonResponse = get_transient($transient_key);

		if (!$jsonResponse) {

			$api_url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?category=PERFORMANCE&category=BEST_PRACTICES&strategy=DESKTOP&url=" . urlencode($url) . "&screenshot=true&key=$page_speed_api_key";
			$timeout = 60;
			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = curl_exec($ch);
			curl_close($ch);
			$jsonResponse = json_decode($result, true);
			set_transient($transient_key, $jsonResponse, DAY_IN_SECONDS);
		}
		if ($viewData) {
			return $jsonResponse;
		}

		$screenshot = 'unavailable';
		$assetsLoaded = "unavailable";
		$siteSize = "unavailable";
		$category = 0;

		if (!empty($jsonResponse['lighthouseResult']['audits']['final-screenshot']['details']['data'])) {
			$screenshot = $jsonResponse['lighthouseResult']['audits']['final-screenshot']['details']['data'];
		}
		if (!empty($jsonResponse['lighthouseResult']['audits']['resource-summary']['details']['items'][0]['requestCount'])) {
			$assetsLoaded = (int) $jsonResponse['lighthouseResult']['audits']['resource-summary']['details']['items'][0]['requestCount'];
		}
		if (!empty($jsonResponse['lighthouseResult']['audits']['resource-summary']['details']['items'][0]['transferSize'])) {
			$siteSize = (int) $jsonResponse['lighthouseResult']['audits']['resource-summary']['details']['items'][0]['transferSize'];
		}
		if (!empty($jsonResponse['lighthouseResult']['audits']['speed-index']['score'])) {
			$category = intval($jsonResponse['lighthouseResult']['audits']['speed-index']['score'] * 100);
		}

		$dataSpeed = array(
			'assetsLoaded' => $assetsLoaded,
			'siteSize' => $siteSize,
			'fastLoaded' => $category,
			'category' => $category,
			'screenshot' => $screenshot
		);



		return $dataSpeed;
	}

}
/**
 * Moz Api
 * 
 */
if (!function_exists('std_moz_API')) {

	function std_moz_API($args) {
		extract($args, EXTR_OVERWRITE);
		$protocol = array('http://', 'https://', 'ftp://');
		$urlSite = explode('/', str_replace($protocol, '', $url));
		$domain = $urlSite[0];

		// Add up all the bit flags you want returned.
		// Learn more here: https://moz.com/help/guides/moz-api/mozscape/api-reference/url-metrics
		//Domain Authority + External links + Page Authority

		$cols = (68719476736 + 34359738368 + 2048 + 32 );
		$transient_key = 'vgsa_moz_' . md5($domain) . $cols;
		$contents = get_transient($transient_key);

		if (!$contents) {

			//get credentials moz api
			$settings = get_option('site_audit_settings', __('This option not found', "SDT"));

			$credentials = sdt_credentialsapi($settings, "mozapi");
			// Get your access id and secret key here: https://moz.com/products/api/keys
			extract($credentials, EXTR_OVERWRITE);

			// Set your expires times for several minutes into the future.
			// An expires time excessively far in the future will not be honored by the Mozscape API.
			$expires = time() + 300;
			// Put each parameter on a new line.
			$stringToSign = $accessID . "\n" . $expires;
			// Get the "raw" or binary output of the hmac hash.
			$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
			// Base64-encode it and then url-encode that.
			$urlSafeSignature = urlencode(base64_encode($binarySignature));
			// Put it all together and you get your request URL.
			$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/?Cols=" . $cols . "&AccessID=" . $accessID . "&Expires=" . $expires . "&Signature=" . $urlSafeSignature;
			// Put your URLS into an array and json_encode them.

			$batchedDomains = array($domain);
			$encodedDomains = json_encode($batchedDomains);
			// Use Curl to send off your request.
			// Send your encoded list of domains through Curl's POSTFIELDS.
			$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $encodedDomains
			);
			$ch = curl_init($requestUrl);
			curl_setopt_array($ch, $options);
			$content = curl_exec($ch);
			curl_close($ch);
			$contents = json_decode($content, true);
			set_transient($transient_key, $contents, DAY_IN_SECONDS);
		}

		return $contents;
	}

}
/**
 * Function get logo of settings
 * 
 * @param Array $settings Un array de las opciones 
 * guardadas en el campo "site_audit_settings" en la tabla prefix_options
 *
 */
if (!function_exists('sdt_get_logo')) {

	function sdt_get_logo($settings) {
		foreach ($settings as $key => $setting) {
			if ($key === "sdt_logo") {
				if (is_array($setting)) {
					$url = $setting['url'];
				} else {
					$url = wp_get_attachment_url($setting);
				}
				?>
				<img src="<?php echo esc_url($url); ?>" alt="logo" > 
				<?php
			}
		}
	}

}
/**
 * Function get key and accessD google or Moz
 * 
 * @param Array $settings           Un array de las opciones
 * @param String $namesApiService   Un string para poder seleccionar de que API quiero las credenciales
 * 
 * @return String||Array
 * guardadas en el campo "site_audit_settings" en la tabla prefix_options
 *
 */
if (!function_exists('sdt_credentialsapi')) {

	function sdt_credentialsapi($settings, $namesApiService) {
		$credentials = array();
		foreach ($settings as $key => $setting) {
			$credentials[$key] = $setting;
		}
		switch ($namesApiService) {
			case 'googleSpeedpage':
				return $credentials['sdt_googleapi_key'];
				break;
			case 'mozapi':
				$credentialsMozapi = array(
					'accessID' => $credentials['sdt_mozapi_accessid'],
					'secretKey' => $credentials['sdt_mozapi_secretkey']
				);
				return $credentialsMozapi;
				break;
			default:
				return false;
				break;
		}
	}

}

if (!function_exists('sdt_get_head_and_footer')) {

	function sdt_get_head_and_footer($settings, $headerorfooter) {
		foreach ($settings as $key => $setting) {
			$tags[$key] = $setting;
		}
		switch ($headerorfooter) {
			case 'header':
				return wpautop($tags['sdt_pdf_header']);
				break;
			case 'footer':
				return wpautop($tags['sdt_pdf_footer']);
				break;
			default:
				return false;
				break;
		}
	}

}