<?php
namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10.0
 *
 * @access private
 */
final class Map {

	/** @var array Key to translation mapping array */
	private $translations = array();

	/**
	 * Get the translation of a message key
	 *
	 * @param string $key  The message key
	 * @param array  $args Optional array of arguments to pass through vsprintf()
	 * 
	 * @return string|null The translation or null if no translation
	 */
	public function get($key, $args = array()) {
		if (!isset($this->translations[$key])) {
			return NULL;
		}
		
		$translation = $this->translations[$key];
		if (!empty($args)) {
			return vsprintf($translation, $args);
		} else {
			return $translation;
		}
	}

	/**
	 * Gets the current translation as an array of key => translation
	 * 
	 * @return array
	 */
	public function getTranslations() {
		return $this->translations;
	}

	/**
	 * Set or update the translation array
	 *
	 * @param array $translation The translation array
	 * 
	 * @return void
	 */
	public function addTranslations(array $translation) {
		$this->translations = array_merge($this->translations, $translation);
	}
}
