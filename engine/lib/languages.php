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
 * Get the current system/user language or "en".
 *
 * @return string The language code for the site/user or "en" if not set
 */
function get_current_language() {
	return _elgg_services()->translator->getCurrentLanguage();
}

/**
 * Detect the current system/user language or false.
 *
 * @return string The language code (eg "en") or false if not set
 */
function get_language() {
	return _elgg_services()->translator->detectLanguage();
}

/**
 * When given a full path, finds translation files and loads them
 *
 * @param string $path     Full path
 * @param bool   $load_all If true all languages are loaded, if
 *                         false only the current language + en are loaded
 * @param string $language Language code if other than current + en
 *
 * @return bool success
 */
function register_translations($path, $load_all = false, $language = null) {
	return _elgg_services()->translator->registerTranslations($path, $load_all, $language);
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
 * Check if a given language key exists.
 *
 * @note Translators should, whenever creating a "dynamically" named language key, always create an
 *       English (fallback) translation as well.
 *
 * @param string $key      The translation key
 * @param string $language The language. Provided an English translation exists for all created keys, then
 *                         devs can generally use the default "en", regardless of the site/user language.
 *
 * @return bool
 * @since 1.11
 */
function elgg_language_key_exists($key, $language = 'en') {
	return _elgg_services()->translator->languageKeyExists($key, $language);
}

return function(\Elgg\EventsService $events) {};
