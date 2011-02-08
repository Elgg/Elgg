<?php
/**
 * Elgg language module
 * Functions to manage language and translations.
 *
 * @package Elgg.Core
 * @subpackage Languages
 */

/**
 * Add a translation.
 *
 * Translations are arrays in the Zend Translation array format, eg:
 *
 *	$english = array('message1' => 'message1', 'message2' => 'message2');
 *  $german = array('message1' => 'Nachricht1','message2' => 'Nachricht2');
 *
 * @param string $country_code   Standard country code (eg 'en', 'nl', 'es')
 * @param array  $language_array Formatted array of strings
 *
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

	$user = elgg_get_logged_in_user_entity();
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
 * @param array  $args        An array of arguments to pass through vsprintf().
 * @param string $language    Optionally, the standard language code
 *                            (defaults to site/user default, then English)
 *
 * @return string Either the translated string, the English string,
 * or the original language string.
 */
function elgg_echo($message_key, $args = array(), $language = "") {
	global $CONFIG;

	static $CURRENT_LANGUAGE;

	// old param order is deprecated
	if (!is_array($args)) {
		elgg_deprecated_notice(
			'As of Elgg 1.8, the 2nd arg to elgg_echo() is an array of string replacements and the 3rd arg is the language.',
			1.8
		);

		$language = $args;
		$args = array();
	}

	if (!$CURRENT_LANGUAGE) {
		$CURRENT_LANGUAGE = get_language();
	}
	if (!$language) {
		$language = $CURRENT_LANGUAGE;
	}

	if (isset($CONFIG->translations[$language][$message_key])) {
		$string = $CONFIG->translations[$language][$message_key];
	} else if (isset($CONFIG->translations["en"][$message_key])) {
		$string = $CONFIG->translations["en"][$message_key];
	} else {
		$string = $message_key;
	}

	// only pass through if we have arguments to allow backward compatibility
	// with manual sprintf() calls.
	if ($args) {
		$string = vsprintf($string, $args);
	}

	return $string;
}

/**
 * When given a full path, finds translation files and loads them
 *
 * @param string $path     Full path
 * @param bool   $load_all If true all languages are loaded, if
 *                         false only the current language + en are loaded
 *
 * @return void
 */
function register_translations($path, $load_all = false) {
	global $CONFIG;

	$path = sanitise_filepath($path);

	// Make a note of this path just incase we need to register this language later
	if (!isset($CONFIG->language_paths)) {
		$CONFIG->language_paths = array();
	}
	$CONFIG->language_paths[$path] = true;

	// Get the current language based on site defaults and user preference
	$current_language = get_current_language();
	elgg_log("Translations loaded from: $path");

	// only load these files unless $load_all is true.
	$load_language_files = array(
		'en.php',
		"$current_language.php"
	);

	$load_language_files = array_unique($load_language_files);

	$handle = opendir($path);
	if (!$handle) {
		elgg_log("Could not open language path: $path", 'ERROR');
		return false;
	}

	$return = true;
	while (false !== ($language = readdir($handle))) {
		// ignore bad files
		if (substr($language, 0, 1) == '.' || substr($language, -4) !== '.php') {
			continue;
		}

		if (in_array($language, $load_language_files) || $load_all) {
			if (!include_once($path . $language)) {
				$return = false;
				continue;
			}
		}
	}

	return $return;
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
 * Return an array of installed translations as an associative
 * array "two letter code" => "native language name".
 *
 * @return array
 */
function get_installed_translations() {
	global $CONFIG;

	// Ensure that all possible translations are loaded
	reload_all_translations();

	$installed = array();

	foreach ($CONFIG->translations as $k => $v) {
		$installed[$k] = elgg_echo($k, array(), $k);
		if (elgg_is_admin_logged_in()) {
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
 *
 * @param string $language Language
 *
 * @return int
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
 * Return the translation keys missing from a given language,
 * or those that are identical to the english version.
 *
 * @param string $language The language
 *
 * @return mixed
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
