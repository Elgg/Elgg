<?php

namespace Elgg\I18n;

/**
 * Language class to ensure only valid languages are used.
 * 
 * @package    Elgg.Core
 * @subpackage I18n
 * @since      1.10
 * 
 * @access private
 */
final class Locale {
	
	/** @var string */
	private $locale;
	
	/**
	 * Use Locale::parse to construct
	 * 
	 * @param string $locale A string representation of the locale
	 */
	private function __construct($locale) {
		$this->locale = $locale;
	}
	
	/** @inheritDoc */
	public function __toString() {
		return $this->locale;
	}
	
	/**
	 * Create a language, asserting that the language code is valid.
	 *
	 * @param string $locale Language code
	 * 
	 * @return Locale
	 * 
	 * @throws InvalidLocaleException
	 */
	public static function parse($locale) {
		// TODO(anyone): Better sanitizing of locales using \Locale perhaps
		if (2 <= strlen($locale) && strlen($locale) <= 5) {
			return new Locale($locale);
		}
		
		throw new InvalidLocaleException("Unrecognized locale: $locale");
	}
}