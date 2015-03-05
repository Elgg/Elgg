<?php
/**
 * Elgg language module
 * Functions to manage language and translations.
 *
 * @package Elgg.Core
 * @subpackage Languages
 */

/**
 * Given a message key, returns an appropriately translated full-text string
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
	return _elgg_services()->translator->translate($message_key, $args, $language);
}

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
 * @return bool Depending on success
 */
function add_translation($country_code, $language_array) {
	return _elgg_services()->translator->addTranslation($country_code, $language_array);
}

/**
 * Detect the current language being used by the current site or logged in user.
 *
 * @return string The language code for the site/user or "en" if not set
 */
function get_current_language() {
	return _elgg_services()->translator->getCurrentLanguage();
}

/**
 * Gets the current language in use by the system or user.
 *
 * @return string The language code (eg "en") or false if not set
 */
function get_language() {
	return _elgg_services()->translator->getLanguage();
}

/**
 * Load both core and plugin translations for a specific language
 *
 * This can be used to load translations on-demand in case we need
 * to translate something to a language not loaded by default for
 * the current user.
 *
 * @param $language Language code
 * @return bool
 *
 * @since 1.9.4
 * @throws PluginException
 * @access private
 */
function _elgg_load_translations_for_language($language) {
	global $CONFIG;

	// Try to load translations from system cache
	if (!empty($CONFIG->system_cache_enabled)) {
		$data = elgg_load_system_cache("$language.lang");
		if ($data) {
			$added = add_translation($language, unserialize($data));

			if ($added) {
				// Translations were successfully loaded from system cache
				return true;
			}
		}
	}

	// Read translations from the core languages directory
	_elgg_register_translations_for_language(dirname(dirname(dirname(__FILE__))) . "/languages/", $language);

	// Get active plugins
	$plugins = elgg_get_plugins('active');

	if (!$plugins) {
		// Active plugins were not found, so no need to register plugin translations
		return true;
	}

	foreach ($plugins as $plugin) {
		$languages_path = "{$plugin->getPath()}languages/";

		if (!is_dir($languages_path)) {
			// This plugin doesn't have anything to translate
			continue;
		}

		$language_file = "{$languages_path}{$language}.php";

		if (!file_exists($language_file)) {
			// This plugin doesn't have translations for the requested language

			$name = $plugin->getFriendlyName();
			elgg_log("Plugin $name is missing translations for $language language", 'NOTICE');

			continue;
		}

		// Register translations from the plugin languages directory
		if (!_elgg_register_translations_for_language($languages_path, $language)) {
			$msg = elgg_echo('ElggPlugin:Exception:CannotRegisterLanguages',
							array($plugin->getID(), $plugin->guid, $languages_path));
			throw new PluginException($msg);
		}
	}

	return true;
}

/**
 * When given a full path, finds translation files and loads them
 *
 * @param string $path     Full path
 * @param bool   $load_all If true all languages are loaded, if
 *                         false only the current language + en are loaded
 *
 * @return bool success
 */
function register_translations($path, $load_all = false) {
	return _elgg_services()->translator->registerTranslations($path, $load_all);
}

/**
 * When given a full path, finds translation files for a language and loads them
 *
 * This function was added in 1.9.4 to make it possible to load translations
 * for individual languages on-demand. This is needed in order to send
 * notifications in the recipient's language (see #3151 and #7241).
 *
 * @todo Replace this function in 1.10 by adding $language as the third parameter
 *       to register_translations().
 *
 * @access private
 * @since 1.9.4
 *
 * @param string $path     Full path of the directory (with trailing slash)
 * @param string $language Language code
 * @return bool success
 */
function _elgg_register_translations_for_language($path, $language) {
	global $CONFIG;

	$path = sanitise_filepath($path);

	// Make a note of this path just in case we need to register this language later
	if (!isset($CONFIG->language_paths)) {
		$CONFIG->language_paths = array();
	}
	$CONFIG->language_paths[$path] = true;

	$language_file = "{$path}{$language}.php";

	if (!file_exists($language_file)) {
		elgg_log("Could not find language file: $language_file", 'NOTICE');

		return false;
	}

	$result = include_once($language_file);

	elgg_log("Translations loaded from: $language_file", "INFO");

	// The old (< 1.9) translation files call add_translation() independently.
	// The new ones however just return the translations array. In this case
	// we need to add the translation here.
	if (is_array($result)) {
		return add_translation($language, $result);
	}

	return true;
}

/**
 * Reload all translations from all registered paths.
 *
 * This is only called by functions which need to know all possible translations.
 *
 * @todo Better on demand loading based on language_paths array
 *
 * @return void
 */
function reload_all_translations() {
	return _elgg_services()->translator->reloadAllTranslations();
}

/**
 * Return an array of installed translations as an associative
 * array "two letter code" => "native language name".
 *
 * @return array
 */
function get_installed_translations() {
	return _elgg_services()->translator->getInstalledTranslations();
}

/**
 * Return the level of completeness for a given language code (compared to english)
 *
 * @param string $language Language
 *
 * @return int
 */
function get_language_completeness($language) {
	return _elgg_services()->translator->getLanguageCompleteness($language);
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
	return _elgg_services()->translator->getMissingLanguageKeys($language);
}

/**
 * Check if a give language key exists
 *
 * @param string $key      The translation key
 * @param string $language The language
 *
 * @return bool
 * @since 1.11
 */
function elgg_language_key_exists($key, $language = 'en') {
	return _elgg_services()->translator->languageKeyExists($key, $language);
}

/**
 * Initializes simplecache views for translations
 * 
 * @return void
 */
function _elgg_translations_init() {
	$translations = _elgg_services()->translator->getAllLanguageCodes();
	foreach ($translations as $language_code) {
		// make the js view available for each language
		elgg_extend_view("js/languages/$language_code.js", "js/languages");
	
		// register the js view for use in simplecache
		elgg_register_simplecache_view("js/languages/$language_code.js");
	}
}

return function(\Elgg\EventsService $events) {
	$events->registerHandler('init', 'system', '_elgg_translations_init');
};