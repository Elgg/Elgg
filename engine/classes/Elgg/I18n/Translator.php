<?php
namespace Elgg\I18n;

/**
 * Translator
 * 
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 * 
 * @access private
 */
interface Translator {
	/**
	 * Get the translation of a message key.
	 * 
	 * If no translation can be found, returns the key.
	 *
	 * @param string $key      The message key
	 * @param array  $args     Optional array of arguments to pass through vsprintf()
	 * @param Locale $language Optional 2 letter language code to override default language
	 * 
	 * @return string
	 */
	public function translate($key, $args = array(), Locale $language = NULL);
}