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
function elgg_echo($message_key, array $args = [], $language = "") {
	return elgg()->echo($message_key, $args, $language);
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
	return elgg()->translator->addTranslation($country_code, $language_array);
}

/**
 * Get the current system/user language or "en".
 *
 * @return string The language code for the site/user or "en" if not set
 */
function get_current_language() {
	return elgg()->translator->getCurrentLanguage();
}

/**
 * Detect the current system/user language or false.
 *
 * @return string The language code (eg "en") or false if not set
 */
function get_language() {
	return elgg()->translator->getCurrentLanguage();
}

/**
 * Return an array of installed translations as an associative
 * array "two letter code" => "native language name".
 *
 * @param boolean $calculate_completeness Set to true if you want a completeness postfix added to the language text
 *
 * @return array
 */
function get_installed_translations($calculate_completeness = false) {
	return elgg()->translator->getInstalledTranslations($calculate_completeness);
}

/**
 * Return the level of completeness for a given language code (compared to english)
 *
 * @param string $language Language
 *
 * @return int
 */
function get_language_completeness($language) {
	return elgg()->translator->getLanguageCompleteness($language);
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
	return elgg()->translator->languageKeyExists($key, $language);
}

/**
 * Returns an array of available languages
 *
 * @return array
 * @since 3.0
 */
function elgg_get_available_languages() {
	return elgg()->translator->getAvailableLanguages();
}
