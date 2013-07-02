<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.9.0
 */
class Elgg_I18n_Translator {

	/** @var array Key to translation mapping array */
	protected $translation = array();

	/** @var string Two letter language code */
	protected $language;

	/**
	 * Constructor
	 * 
	 * @param string $language    The two letter code for the primary language
	 * @param array  $translation Translation array (key => translation)
	 */
	public function __construct($language, array $translation) {
		$this->language = $language;
		$this->translation = $translation;
	}

	/**
	 * Get the translation of a message key 
	 *
	 * @param string $key  The message key
	 * @param array  $args Optional array of arguments to pass through vsprintf()
	 * @return string|false The translation or false if no translation
	 */
	public function translate($key, $args = array()) {

		$translation = false;
		if (isset($this->translation[$key])) {
			$translation = $this->translation[$key];
			if ($args) {
				$translation = vsprintf($translation, $args);
			}
		}

		return $translation;
	}
}
