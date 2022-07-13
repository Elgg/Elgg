<?php
/**
 * Elgg language module
 * Functions to manage language and translations.
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
	return _elgg_services()->translator->translate($message_key, $args, $language);
}

/**
 * Get the current system/user language or "en".
 *
 * @return string
 *
 * @since 4.3
 */
function elgg_get_current_language(): string {
	return _elgg_services()->translator->getCurrentLanguage();
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
