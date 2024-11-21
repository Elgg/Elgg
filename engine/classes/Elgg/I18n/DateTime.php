<?php

namespace Elgg\I18n;

use DateTime as PHPDateTime;

/**
 * Extension of the DateTime class to support formatting a date using the locale
 *
 * @since 3.0
 */
class DateTime extends PHPDateTime {
	
	/**
	 * Try to format the date using locale output
	 *
	 * @param string $format   output format, supports date() formatting
	 * @param string $language the output language, defaults to current language
	 *
	 * @return string
	 */
	public function formatLocale(string $format, string $language = null) {
		if (!isset($language)) {
			$language = _elgg_services()->translator->getCurrentLanguage();
		}
		
		$result = $this->formatIntl($format, $language);
		
		if ($result === false) {
			_elgg_services()->logger->info("Unable to generate locale representation for format: '{$format}', using non-locale version");
			return $this->format($format);
		}
		
		return $result;
	}
	
	/**
	 * Convert a date format to a ICU format
	 *
	 * @param string $dateFormat a date format
	 *
	 * @return false|string
	 * @see https://secure.php.net/manual/en/function.strftime.php#96424
	 * @since 4.1
	 */
	protected function dateFormatToICU(string $dateFormat) {
		if (preg_match('/(?<!\\|%)[BcILrtuUVZ]/', $dateFormat)) {
			// unsupported characters found
			return false;
		}
		
		$caracs = [
			// Day
			'd' => 'dd', 'D' => 'EEE', 'j' => 'd', 'l' => 'EEEE', 'N' => 'e', 'w' => 'c', 'z' => 'D',
			// Week
			'W' => 'w',
			// Month
			'F' => 'MMMM', 'm' => 'MM', 'M' => 'MMM', 'n' => 'M',
			// Year
			'o' => 'Y', 'Y' => 'yyyy', 'y' => 'yy',
			// Time
			'a' => 'a', 'A' => 'a', 'g' => 'h', 'G' => 'H', 'h' => 'hh', 'H' => 'HH', 'i' => 'mm', 's' => 'ss',
			// Timezone
			'e' => 'VV', 'O' => 'xx', 'P' => 'xxx', 'p' => 'XXX', 'T' => 'zzz',
			// less supported replacements
			// Day
			'S' => '',
		];
		
		return strtr((string) $dateFormat, $caracs);
	}
	
	/**
	 * Try to format to a locale using the 'intl' PHP module
	 *
	 * @param string $format   output format, supports date() formatting
	 * @param string $language the output language
	 *
	 * @return string|false
	 * @since 4.1
	 */
	protected function formatIntl(string $format, string $language) {
		$correct_format = $this->dateFormatToICU($format);
		if ($correct_format === false) {
			return false;
		}
		
		$locale_for_language = _elgg_services()->locale->getLocaleForLanguage($language);
		
		try {
			$locale = new \IntlDateFormatter(elgg_extract(0, $locale_for_language, $language), \IntlDateFormatter::FULL, \IntlDateFormatter::FULL);
			$locale->setPattern($correct_format);
			
			return $locale->format($this);
		} catch (\Throwable $t) {
			// something went wrong
			// @see https://github.com/Elgg/Elgg/issues/14712
		}
		
		return false;
	}
}
