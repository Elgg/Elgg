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
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public function __construct($language, array $translation) {
		Elgg_I18n_Translator::assertValidLanguage($language);
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
	public function get($key, $args = array()) {

		$translation = false;
		if (isset($this->translation[$key])) {
			$translation = $this->translation[$key];
			if ($args) {
				$translation = vsprintf($translation, $args);
			}
		}

		return $translation;
	}

	/**
	 * Get the language of this translation
	 *
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * Gets the current translation as an array of key => translation
	 * 
	 * @return array
	 */
	public function getTranslation() {
		return $this->translation;
	}

	/**
	 * Set or update the translation array
	 *
	 * @param array $translation The translation array
	 * @param bool  $replace     Whether to replace or add to the translation
	 */
	public function setTranslation(array $translation, $replace = false) {
		if ($replace) {
			$this->translation = $translation;
		} else {
			$this->translation = array_merge($this->translation, $translation);
		}
	}

	/**
	 * Assert that the language code is valid
	 *
	 * @param string $language Two letter language code
	 * @return void
	 * @throws Elgg_I18n_InvalidLanguageException
	 */
	public static function assertValidLanguage($language) {
		if (strlen($language) != 2) {
			throw new Elgg_I18n_InvalidLanguageException("$language is not a valid language code");
		}
	}

}
