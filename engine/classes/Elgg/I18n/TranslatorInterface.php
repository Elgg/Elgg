<?php
namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Can "translate" language keys into various human-readable, localized strings.
 *
 * TODO(ewinslow): Remove the "Interface" suffix
 *
 * @since 1.11
 *
 * @access private
 */
interface TranslatorInterface {
	/**
	 * Given a message key, returns a best-effort translated string.
	 *
	 * If the translator doesn't know how to translate into the specified locale,
	 * it can try translating into a related or similar locale (e.g. en-US => en).
	 *
	 * If no locale is specified, or if no translation can be found for the specified
	 * locale, the translator may choose to fall back to some other language(s).
	 *
	 * It should never throw exceptions, since lack of translation should never be
	 * cause to bring down an app or cancel a request. However, implementations may
	 * log warnings to alert admins that requested language strings are missing.
	 *
	 * @param string $key    A key identifying the message to translate.
	 * @param array  $args   An array of arguments with which to format the message.
	 * @param Locale $locale Optionally, the standard language code
	 *                       (defaults to site/user default, then English)
	 *
	 * @return string The final, best-effort translation.
	 */
	function translate($key, array $args = [], Locale $locale = null);
}
