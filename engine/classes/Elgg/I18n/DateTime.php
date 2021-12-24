<?php

namespace Elgg\I18n;

use \DateTime as PHPDateTime;

/**
 * Extension of the DateTime class to support formating a date using the locale
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
		
		if (extension_loaded('intl')) {
			$result = $this->formatIntl($format, $language);
		} else {
			$result = $this->formatStrftime($format, $language);
		}
		
		if ($result === false) {
			elgg_log("Unable to generate locale representation for format: '{$format}', using non-locale version", 'INFO');
			return $this->format($format);
		}
		
		return $result;
	}
		
	/**
	 * Convert a date format to a strftime format
	 *
	 * Timezone conversion is done for unix. Windows users must exchange %z and %Z.
	 *
	 * Unsupported date formats : n, t, L, B, u, e, I, P, Z, c, r
	 * Unsupported strftime formats : %U, %W, %C, %g, %r, %R, %T, %X, %c, %D, %F, %x
	 *
	 * @param string $dateFormat a date format
	 *
	 * @return false|string
	 * @see https://secure.php.net/manual/en/function.strftime.php#96424
	 */
	protected function dateFormatToStrftime(string $dateFormat) {
		if (preg_match('/(?<!\\|%)[ntLBueIPZcr]/', $dateFormat)) {
			// unsupported characters found
			return false;
		}
		
		$caracs = [
			// Day - no strf eq : S
			'd' => '%d', 'D' => '%a', 'j' => '%e', 'l' => '%A', 'N' => '%u', 'w' => '%w', 'z' => '%j',
			// Week - no date eq : %U, %W
			'W' => '%V',
			// Month - no strf eq : n, t
			'F' => '%B', 'm' => '%m', 'M' => '%b',
			// Year - no strf eq : L; no date eq : %C, %g
			'o' => '%G', 'Y' => '%Y', 'y' => '%y',
			// Time - no strf eq : B, G, u; no date eq : %r, %R, %T, %X
			'a' => '%P', 'A' => '%p', 'g' => '%l', 'h' => '%I', 'H' => '%H', 'i' => '%M', 's' => '%S',
			// Timezone - no strf eq : e, I, P, Z
			'O' => '%z', 'T' => '%Z',
			// Full Date / Time - no strf eq : c, r; no date eq : %c, %D, %F, %x
			'U' => '%s',
			// less supported replacements
			// Day
			'S' => '',
			// Time
			'G' => '%k',
		];
		
		return strtr((string) $dateFormat, $caracs);
	}
	
	/**
	 * Try to format to a locale using strftime()
	 *
	 * @param string $format   output format, supports date() formatting
	 * @param string $language the output language
	 *
	 * @return string|false
	 * @since 4.1
	 */
	protected function formatStrftime(string $format, string $language) {
		// convert date() format to strftime() format
		$correct_format = $this->dateFormatToStrftime($format);
		if ($correct_format === false) {
			return false;
		}
		
		if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
			elgg_log('In order to get rid of the deprecated warnings about strftime() enable the "intl" PHP module', 'WARNING');
		}
		
		// switch locale
		$current_locale = _elgg_services()->locale->setLocaleFromLanguageKey(LC_TIME, $language);
		
		$result = strftime($correct_format, $this->getTimestamp());
		
		// restore locale
		_elgg_services()->locale->setLocale(LC_TIME, $current_locale);
		
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
		
		$locale = new \IntlDateFormatter(elgg_extract(0, $locale_for_language, $language), \IntlDateFormatter::FULL, \IntlDateFormatter::FULL);
		$locale->setPattern($correct_format);
		
		return $locale->format($this);
	}
}
