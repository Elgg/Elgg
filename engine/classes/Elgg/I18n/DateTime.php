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
	* Convert a date format to a strftime format
	*
	* Timezone conversion is done for unix. Windows users must exchange %z and %Z.
	*
	* Unsupported date formats : n, t, L, B, u, e, I, P, Z, c, r
	* Unsupported strftime formats : %U, %W, %C, %g, %r, %R, %T, %X, %c, %D, %F, %x
	*
	* @param string $dateFormat a date format
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
	 * Format the date using strftime() which supports locale output
	 *
	 * @param string $format   output format, supports date() formatting
	 * @param string $language the output language, defaults to current language
	 *
	 * @return string
	 */
	public function formatLocale(string $format, string $language = null) {
		// convert date() format to strftime() format
		$correct_format = $this->dateFormatToStrftime($format);
		if ($correct_format === false) {
			elgg_log("Unable to convert date format: '{$format}', using non-locale version", 'INFO');
			return $this->format($format);
		}
		
		// switch locale
		$current_locale = elgg()->locale->setLocaleFromLanguageKey(LC_TIME, $language);
		
		$result = strftime($correct_format, $this->getTimestamp());
		
		// restore locale
		elgg()->locale->setLocale(LC_TIME, $current_locale);
		
		if ($result === false) {
			elgg_log("Unable to generate locale representation for format: '{$correct_format}', using non-locale version", 'INFO');
			return $this->format($format);
		}
		
		return $result;
	}
}
