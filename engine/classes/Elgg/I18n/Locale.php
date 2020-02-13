<?php

namespace Elgg\I18n;

use Elgg\Exceptions\I18n\InvalidLocaleException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Language class to ensure only valid languages are used.
 *
 * @since 1.11
 * @internal
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
	
	/**
	 * {@inheritDoc}
	 */
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
		if (!preg_match('~^[a-z0-9_]{2,20}$~', $locale)) {
			throw new InvalidLocaleException("Unrecognized locale: $locale");
		}
		
		return new Locale($locale);
	}
}
