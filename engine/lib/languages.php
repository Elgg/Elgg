<?php
/**
 * Elgg language module
 * Functions to manage language and translations.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Add a translation.
 *
 * Translations are arrays in the Zend Translation array format, eg:
 *
 *	$english = array('message1' => 'message1', 'message2' => 'message2');
 *  $german = array('message1' => 'Nachricht1','message2' => 'Nachricht2');
 *
 * @param string $country_code Standard country code (eg 'en', 'nl', 'es')
 * @param array $language_array Formatted array of strings
 * @return true|false Depending on success
 */
function add_translation($country_code, $language_array) {
	global $CONFIG;
	if (!isset($CONFIG->translations)) {
		$CONFIG->translations = array();
	}

	$country_code = strtolower($country_code);
	$country_code = trim($country_code);
	if (is_array($language_array) && sizeof($language_array) > 0 && $country_code != "") {
		if (!isset($CONFIG->translations[$country_code])) {
			$CONFIG->translations[$country_code] = $language_array;
		} else {
			$CONFIG->translations[$country_code] = $language_array + $CONFIG->translations[$country_code];
		}
		return true;
	}
	return false;
}

/**
 * Detect the current language being used by the current site or logged in user.
 *
 * @return string The language code for the site/user or "en" if not set
 */
function get_current_language() {
	global $CONFIG;

	$language = get_language();

	if (!$language) {
		$language = 'en';
	}

	return $language;
}

/**
 * Gets the current language in use by the system or user.
 *
 * @return string The language code (eg "en") or false if not set
 */
function get_language() {
	global $CONFIG;

	$user = get_loggedin_user();
	$language = false;

	if (($user) && ($user->language)) {
		$language = $user->language;
	}

	if ((!$language) && (isset($CONFIG->language)) && ($CONFIG->language)) {
		$language = $CONFIG->language;
	}

	if ($language) {
		return $language;
	}

	return false;
}

/**
 * Given a message shortcode, returns an appropriately translated full-text string
 *
 * @param string $message_key The short message code
 * @param string $language Optionally, the standard language code (defaults to site/user default, then English)
 * @return string Either the translated string or the original English string
 */
function elgg_echo($message_key, $language = "") {
	global $CONFIG;

	static $CURRENT_LANGUAGE;
	if (!$CURRENT_LANGUAGE) {
		$CURRENT_LANGUAGE = get_language();
	}
	if (!$language) {
		$language = $CURRENT_LANGUAGE;
	}

	if (isset($CONFIG->translations[$language][$message_key])) {
		return $CONFIG->translations[$language][$message_key];
	} else if (isset($CONFIG->translations["en"][$message_key])) {
		return $CONFIG->translations["en"][$message_key];
	}

	return $message_key;
}

/**
 * When given a full path, finds translation files and loads them
 *
 * @param string $path Full path
 * @param bool $load_all If true all languages are loaded, if false only the current language + en are loaded
 */
function register_translations($path, $load_all = false) {
	global $CONFIG;

	// Make a note of this path just incase we need to register this language later
	if(!isset($CONFIG->language_paths)) $CONFIG->language_paths = array();
	$CONFIG->language_paths[$path] = true;

	// Get the current language based on site defaults and user preference
	$current_language = get_current_language();
	elgg_log("Translations loaded from: $path");

	if ($handle = opendir($path)) {
		while ($language = readdir($handle)) {
			if (
				((in_array($language, array('en.php', $current_language . '.php'))) /*&& (!is_dir($path . $language))*/) ||
				(($load_all) && (strpos($language, '.php')!==false)/* && (!is_dir($path . $language))*/)
			) {
				include_once($path . $language);
			}
		}
	} else {
		elgg_log("Missing translation path $path", 'ERROR');
	}
}

/**
 * Reload all translations from all registered paths.
 *
 * This is only called by functions which need to know all possible translations, namely the
 * statistic gathering ones.
 *
 * @todo Better on demand loading based on language_paths array
 *
 * @return bool
 */
function reload_all_translations() {
	global $CONFIG;

	static $LANG_RELOAD_ALL_RUN;
	if ($LANG_RELOAD_ALL_RUN) {
		return null;
	}

	foreach ($CONFIG->language_paths as $path => $dummy) {
		register_translations($path, true);
	}

	$LANG_RELOAD_ALL_RUN = true;
}

/**
 * Return an array of installed translations as an associative array "two letter code" => "native language name".
 */
function get_installed_translations() {
	global $CONFIG;

	// Ensure that all possible translations are loaded
	reload_all_translations();

	$installed = array();

	foreach ($CONFIG->translations as $k => $v) {
		$installed[$k] = elgg_echo($k, $k);
		if (isadminloggedin()) {
			$completeness = get_language_completeness($k);
			if (($completeness < 100) && ($k != 'en')) {
				$installed[$k] .= " (" . $completeness . "% " . elgg_echo('complete') . ")";
			}
		}
	}

	return $installed;
}

/**
 * Return the level of completeness for a given language code (compared to english)
 */
function get_language_completeness($language) {
	global $CONFIG;

	// Ensure that all possible translations are loaded
	reload_all_translations();

	$language = sanitise_string($language);

	$en = count($CONFIG->translations['en']);

	$missing = get_missing_language_keys($language);
	if ($missing) {
		$missing = count($missing);
	} else {
		$missing = 0;
	}

	//$lang = count($CONFIG->translations[$language]);
	$lang = $en - $missing;

	return round(($lang / $en) * 100, 2);
}

/**
 * Return the translation keys missing from a given language, or those that are identical to the english version.
 */
function get_missing_language_keys($language) {
	global $CONFIG;

	// Ensure that all possible translations are loaded
	reload_all_translations();

	$missing = array();

	foreach ($CONFIG->translations['en'] as $k => $v) {
		if ((!isset($CONFIG->translations[$language][$k]))
		|| ($CONFIG->translations[$language][$k] == $CONFIG->translations['en'][$k])) {
			$missing[] = $k;
		}
	}

	if (count($missing)) {
		return $missing;
	}

	return false;
}

register_translations(dirname(dirname(dirname(__FILE__))) . "/languages/");
