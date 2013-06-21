<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Translation
 * @since      1.9.0
 */
class Elgg_Translation_Translator {

	/** @var array Array of key to translation mapping arrays */
	protected $mappings = array();

	/** @var string Two letter language code for the primary language */
	protected $primaryLanguage;

	/** @var string Two letter language code for the secondary language */
	protected $secondaryLanguage;

	/**
	 * Constructor
	 * 
	 * @param string $primaryLanguage   The two letter code for the primary language
	 * @param string $secondaryLanguage Optional fallback language (default: 'en')
	 */
	public function constructor($primaryLanguage, $secondaryLanguage = 'en') {
		$this->primaryLanguage = $primaryLanguage;
		$this->secondaryLanguage = $secondaryLanguage;

		$this->mappings[$this->primaryLanguage] = array();
		$this->mappings[$this->secondaryLanguage] = array();
	}

	/**
	 * Get the translation of a message key 
	 *
	 * @param string $key      The message key
	 * @param array  $args     Optional array of arguments to pass through vsprintf()
	 * @param string $language Optional 2 letter language code to override current language
	 * @return string
	 */
	public function _($key, $args = array(), $language = '') {

		$translation = '';
		if ($language) {
			if (isset($this->mappings[$language]) && isset($this->mappings[$language][$key])) {
				$translation = $this->mappings[$language][$key];
			}
		}

		if (!$translation) {
			if (isset($this->mappings[$this->primaryLanguage][$key])) {
				$translation = $this->mappings[$this->primaryLanguage][$key];
			} else if (isset($this->mappings[$this->secondaryLanguage][$key])) {
				$translation = $this->mappings[$this->secondaryLanguage][$key];
			} else {
				$translation = $key;
			}
		}

		if ($args) {
			$translation = vsprintf($translation, $args);
		}

		return $translation;
	}

	/**
	 * Set the language used first for translation
	 * 
	 * @param string $language Two letter language code
	 * @return void
	 */
	public function setPrimaryLanguage($language) {
		$this->primaryLanguage = $language;
		if (!isset($this->mappings[$this->primaryLanguage])) {
			$this->mappings[$this->primaryLanguage] = array();
		}
	}

	/**
	 * Get the primary language
	 * 
	 * @return string Two letter code
	 */
	public function getPrimaryLanguage() {
		return $this->primaryLanguage;
	}

	/**
	 * Set the fallback language used for translation
	 * 
	 * @param string $language Two letter language code
	 * @return void
	 */
	public function setSecondaryLanguage($language) {
		$this->secondaryLanguage = $language;
		if (!isset($this->mappings[$this->secondaryLanguage])) {
			$this->mappings[$this->secondaryLanguage] = array();
		}
	}

	/**
	 * Get the secondary language
	 * 
	 * @return string Two letter language code
	 */
	public function getSecondaryLanguage() {
		return $this->secondaryLanguage;
	}

	/**
	 * Set the translation array for the specified language
	 * 
	 * @param string $language Two letter language code
	 * @param array  $data     Array of key => translation
	 * @return void
	 */
	public function setTranslationArray($language, $data) {
		$this->mappings[$language] = $data;
	}

	/**
	 * Add this array for the specified language translation array
	 * 
	 * This overwrites any existing keys for that language
	 * 
	 * @param string $language Two letter language code
	 * @param array  $data     Array of key => translation
	 * @return void
	 */
	public function addTranslationArray($language, $data) {
		if (isset($this->mappings[$language])) {
			$this->mappings[$language] = $data + $this->mappings[$language];
		} else {
			$this->mappings[$language] = $data;
		}
	}

	/**
	 * Get the translation array for the specified language
	 * 
	 * @param string $language Two letter language code
	 * @return array
	 */
	public function getTranslationArray($language) {
		if (isset($this->mappings[$language])) {
			return $this->mappings[$language];
		} else {
			return array();
		}
	}

	protected function validateLanguageCode($language) {
		// throw exception?
	}

}
