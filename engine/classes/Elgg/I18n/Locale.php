<?php
namespace Elgg\I18n;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Language class to ensure only valid languages are used.
 * 
 * @since 1.11
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
		// TODO(evan): Better sanitizing of locales using \Locale perhaps
		if (strlen($locale) < 2 || strlen($locale) > 5) {
			throw new InvalidLocaleException("Unrecognized locale: $locale");
		}
		
		return new Locale($locale);
	}
}