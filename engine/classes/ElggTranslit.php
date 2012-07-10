<?php
/**
 * Elgg Transliterate
 *
 * For creating "friendly titles" for URLs
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 *
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 *
 * @author      Steve Clay <steve@mrclay.org>
 * @package     Elgg.Core
 *
 * @access private Plugin authors should not use this directly
 */
class ElggTranslit {

	/**
	 * Create a version of a string for embedding in a URL
	 * @param string $string a UTF-8 string
	 * @param string $separator
	 * @return string
	 */
	static public function urlize($string, $separator = '-') {
		// Iñtërnâtiônàlizætiøn, AND 日本語!

		// try to force combined chars because the translit map and others expect it
		if (self::hasNormalizerSupport()) {
			$nfc = normalizer_normalize($string);
			if (is_string($nfc)) {
				$string = $nfc;
			}
		}
		// Internationalization, AND 日本語!
		$string = self::transliterateAscii($string);

		// more translation
		$string = strtr($string, array(
			// Euro/GBP
			"\xE2\x82\xAC" /* € */ => 'E', "\xC2\xA3" /* £ */ => 'GBP',
		));

		// remove all ASCII except 0-9a-zA-Z, hyphen, underscore, and whitespace
		// note: "x" modifier did not work with this pattern.
		$string = preg_replace('~['
			. '\x00-\x08'  # control chars
			. '\x0b\x0c'   # vert tab, form feed
			. '\x0e-\x1f'  # control chars
			. '\x21-\x2c'  # ! ... ,
			. '\x2e\x2f'   # . slash
			. '\x3a-\x40'  # : ... @
			. '\x5b-\x5e'  # [ ... ^
			. '\x60'       # `
			. '\x7b-\x7f'  # { ... DEL
			. ']~', '', $string);
		$string = strtr($string, '', '');

		// internationalization, and 日本語!
		// note: not using elgg_strtolower to keep this class portable
		$string = is_callable('mb_strtolower')
			? mb_strtolower($string, 'UTF-8')
			: strtolower($string);

		// split by ASCII chars not in 0-9a-zA-Z
		// note: we cannot use [^0-9a-zA-Z] because that matches multibyte chars.
		// note: "x" modifier did not work with this pattern.
		$pattern = '~['
			. '\x00-\x2f'  # controls ... slash
			. '\x3a-\x40'  # : ... @
			. '\x5b-\x60'  # [ ... `
			. '\x7b-\x7f'  # { ... DEL
			. ']+~x';

		// ['internationalization', 'and', '日本語']
		$words = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY);

		// ['internationalization', 'and', '%E6%97%A5%E6%9C%AC%E8%AA%9E']
		$words = array_map('urlencode', $words);

		// internationalization-and-%E6%97%A5%E6%9C%AC%E8%AA%9E
		return implode($separator, $words);
	}

	/**
	 * Transliterate Western multibyte chars to ASCII
	 * @param string $utf8 a UTF-8 string
	 * @return string
	 */
	static public function transliterateAscii($utf8) {
		static $map = null;
		if (!preg_match('/[\x80-\xff]/', $utf8)) {
			return $utf8;
		}
		if (null === $map) {
			$map = self::getAsciiTranslitMap();
		}
		return strtr($utf8, $map);
	}

	/**
	 * Get array of UTF-8 (NFC) character replacements.
	 *
	 * @return array
	 */
	static public function getAsciiTranslitMap() {
		return array(
			// Decompositions for Latin-1 Supplement
			"\xC2\xAA" /* ª */ => 'a', "\xC2\xBA" /* º */ => 'o', "\xC3\x80" /* À */ => 'A',
			"\xC3\x81" /* Á */ => 'A', "\xC3\x82" /* Â */ => 'A', "\xC3\x83" /* Ã */ => 'A',
			"\xC3\x84" /* Ä */ => 'A', "\xC3\x85" /* Å */ => 'A', "\xC3\x86" /* Æ */ => 'AE',
			"\xC3\x87" /* Ç */ => 'C', "\xC3\x88" /* È */ => 'E', "\xC3\x89" /* É */ => 'E',
			"\xC3\x8A" /* Ê */ => 'E', "\xC3\x8B" /* Ë */ => 'E', "\xC3\x8C" /* Ì */ => 'I',
			"\xC3\x8D" /* Í */ => 'I', "\xC3\x8E" /* Î */ => 'I', "\xC3\x8F" /* Ï */ => 'I',
			"\xC3\x90" /* Ð */ => 'D', "\xC3\x91" /* Ñ */ => 'N', "\xC3\x92" /* Ò */ => 'O',
			"\xC3\x93" /* Ó */ => 'O', "\xC3\x94" /* Ô */ => 'O', "\xC3\x95" /* Õ */ => 'O',
			"\xC3\x96" /* Ö */ => 'O', "\xC3\x99" /* Ù */ => 'U', "\xC3\x9A" /* Ú */ => 'U',
			"\xC3\x9B" /* Û */ => 'U', "\xC3\x9C" /* Ü */ => 'U', "\xC3\x9D" /* Ý */ => 'Y',
			"\xC3\x9E" /* Þ */ => 'TH', "\xC3\x9F" /* ß */ => 'ss', "\xC3\xA0" /* à */ => 'a',
			"\xC3\xA1" /* á */ => 'a', "\xC3\xA2" /* â */ => 'a', "\xC3\xA3" /* ã */ => 'a',
			"\xC3\xA4" /* ä */ => 'a', "\xC3\xA5" /* å */ => 'a', "\xC3\xA6" /* æ */ => 'ae',
			"\xC3\xA7" /* ç */ => 'c', "\xC3\xA8" /* è */ => 'e', "\xC3\xA9" /* é */ => 'e',
			"\xC3\xAA" /* ê */ => 'e', "\xC3\xAB" /* ë */ => 'e', "\xC3\xAC" /* ì */ => 'i',
			"\xC3\xAD" /* í */ => 'i', "\xC3\xAE" /* î */ => 'i', "\xC3\xAF" /* ï */ => 'i',
			"\xC3\xB0" /* ð */ => 'd', "\xC3\xB1" /* ñ */ => 'n', "\xC3\xB2" /* ò */ => 'o',
			"\xC3\xB3" /* ó */ => 'o', "\xC3\xB4" /* ô */ => 'o', "\xC3\xB5" /* õ */ => 'o',
			"\xC3\xB6" /* ö */ => 'o', "\xC3\xB8" /* ø */ => 'o', "\xC3\xB9" /* ù */ => 'u',
			"\xC3\xBA" /* ú */ => 'u', "\xC3\xBB" /* û */ => 'u', "\xC3\xBC" /* ü */ => 'u',
			"\xC3\xBD" /* ý */ => 'y', "\xC3\xBE" /* þ */ => 'th', "\xC3\xBF" /* ÿ */ => 'y',
			"\xC3\x98" /* Ø */ => 'O',
			// Decompositions for Latin Extended-A
			"\xC4\x80" /* Ā */ => 'A', "\xC4\x81" /* ā */ => 'a', "\xC4\x82" /* Ă */ => 'A',
			"\xC4\x83" /* ă */ => 'a', "\xC4\x84" /* Ą */ => 'A', "\xC4\x85" /* ą */ => 'a',
			"\xC4\x86" /* Ć */ => 'C', "\xC4\x87" /* ć */ => 'c', "\xC4\x88" /* Ĉ */ => 'C',
			"\xC4\x89" /* ĉ */ => 'c', "\xC4\x8A" /* Ċ */ => 'C', "\xC4\x8B" /* ċ */ => 'c',
			"\xC4\x8C" /* Č */ => 'C', "\xC4\x8D" /* č */ => 'c', "\xC4\x8E" /* Ď */ => 'D',
			"\xC4\x8F" /* ď */ => 'd', "\xC4\x90" /* Đ */ => 'D', "\xC4\x91" /* đ */ => 'd',
			"\xC4\x92" /* Ē */ => 'E', "\xC4\x93" /* ē */ => 'e', "\xC4\x94" /* Ĕ */ => 'E',
			"\xC4\x95" /* ĕ */ => 'e', "\xC4\x96" /* Ė */ => 'E', "\xC4\x97" /* ė */ => 'e',
			"\xC4\x98" /* Ę */ => 'E', "\xC4\x99" /* ę */ => 'e', "\xC4\x9A" /* Ě */ => 'E',
			"\xC4\x9B" /* ě */ => 'e', "\xC4\x9C" /* Ĝ */ => 'G', "\xC4\x9D" /* ĝ */ => 'g',
			"\xC4\x9E" /* Ğ */ => 'G', "\xC4\x9F" /* ğ */ => 'g', "\xC4\xA0" /* Ġ */ => 'G',
			"\xC4\xA1" /* ġ */ => 'g', "\xC4\xA2" /* Ģ */ => 'G', "\xC4\xA3" /* ģ */ => 'g',
			"\xC4\xA4" /* Ĥ */ => 'H', "\xC4\xA5" /* ĥ */ => 'h', "\xC4\xA6" /* Ħ */ => 'H',
			"\xC4\xA7" /* ħ */ => 'h', "\xC4\xA8" /* Ĩ */ => 'I', "\xC4\xA9" /* ĩ */ => 'i',
			"\xC4\xAA" /* Ī */ => 'I', "\xC4\xAB" /* ī */ => 'i', "\xC4\xAC" /* Ĭ */ => 'I',
			"\xC4\xAD" /* ĭ */ => 'i', "\xC4\xAE" /* Į */ => 'I', "\xC4\xAF" /* į */ => 'i',
			"\xC4\xB0" /* İ */ => 'I', "\xC4\xB1" /* ı */ => 'i', "\xC4\xB2" /* Ĳ */ => 'IJ',
			"\xC4\xB3" /* ĳ */ => 'ij', "\xC4\xB4" /* Ĵ */ => 'J', "\xC4\xB5" /* ĵ */ => 'j',
			"\xC4\xB6" /* Ķ */ => 'K', "\xC4\xB7" /* ķ */ => 'k', "\xC4\xB8" /* ĸ */ => 'k',
			"\xC4\xB9" /* Ĺ */ => 'L', "\xC4\xBA" /* ĺ */ => 'l', "\xC4\xBB" /* Ļ */ => 'L',
			"\xC4\xBC" /* ļ */ => 'l', "\xC4\xBD" /* Ľ */ => 'L', "\xC4\xBE" /* ľ */ => 'l',
			"\xC4\xBF" /* Ŀ */ => 'L', "\xC5\x80" /* ŀ */ => 'l', "\xC5\x81" /* Ł */ => 'L',
			"\xC5\x82" /* ł */ => 'l', "\xC5\x83" /* Ń */ => 'N', "\xC5\x84" /* ń */ => 'n',
			"\xC5\x85" /* Ņ */ => 'N', "\xC5\x86" /* ņ */ => 'n', "\xC5\x87" /* Ň */ => 'N',
			"\xC5\x88" /* ň */ => 'n', "\xC5\x89" /* ŉ */ => 'N', "\xC5\x8A" /* Ŋ */ => 'n',
			"\xC5\x8B" /* ŋ */ => 'N', "\xC5\x8C" /* Ō */ => 'O', "\xC5\x8D" /* ō */ => 'o',
			"\xC5\x8E" /* Ŏ */ => 'O', "\xC5\x8F" /* ŏ */ => 'o', "\xC5\x90" /* Ő */ => 'O',
			"\xC5\x91" /* ő */ => 'o', "\xC5\x92" /* Œ */ => 'OE', "\xC5\x93" /* œ */ => 'oe',
			"\xC5\x94" /* Ŕ */ => 'R', "\xC5\x95" /* ŕ */ => 'r', "\xC5\x96" /* Ŗ */ => 'R',
			"\xC5\x97" /* ŗ */ => 'r', "\xC5\x98" /* Ř */ => 'R', "\xC5\x99" /* ř */ => 'r',
			"\xC5\x9A" /* Ś */ => 'S', "\xC5\x9B" /* ś */ => 's', "\xC5\x9C" /* Ŝ */ => 'S',
			"\xC5\x9D" /* ŝ */ => 's', "\xC5\x9E" /* Ş */ => 'S', "\xC5\x9F" /* ş */ => 's',
			"\xC5\xA0" /* Š */ => 'S', "\xC5\xA1" /* š */ => 's', "\xC5\xA2" /* Ţ */ => 'T',
			"\xC5\xA3" /* ţ */ => 't', "\xC5\xA4" /* Ť */ => 'T', "\xC5\xA5" /* ť */ => 't',
			"\xC5\xA6" /* Ŧ */ => 'T', "\xC5\xA7" /* ŧ */ => 't', "\xC5\xA8" /* Ũ */ => 'U',
			"\xC5\xA9" /* ũ */ => 'u', "\xC5\xAA" /* Ū */ => 'U', "\xC5\xAB" /* ū */ => 'u',
			"\xC5\xAC" /* Ŭ */ => 'U', "\xC5\xAD" /* ŭ */ => 'u', "\xC5\xAE" /* Ů */ => 'U',
			"\xC5\xAF" /* ů */ => 'u', "\xC5\xB0" /* Ű */ => 'U', "\xC5\xB1" /* ű */ => 'u',
			"\xC5\xB2" /* Ų */ => 'U', "\xC5\xB3" /* ų */ => 'u', "\xC5\xB4" /* Ŵ */ => 'W',
			"\xC5\xB5" /* ŵ */ => 'w', "\xC5\xB6" /* Ŷ */ => 'Y', "\xC5\xB7" /* ŷ */ => 'y',
			"\xC5\xB8" /* Ÿ */ => 'Y', "\xC5\xB9" /* Ź */ => 'Z', "\xC5\xBA" /* ź */ => 'z',
			"\xC5\xBB" /* Ż */ => 'Z', "\xC5\xBC" /* ż */ => 'z', "\xC5\xBD" /* Ž */ => 'Z',
			"\xC5\xBE" /* ž */ => 'z', "\xC5\xBF" /* ſ */ => 's',
			// Decompositions for Latin Extended-B
			"\xC8\x98" /* Ș */ => 'S', "\xC8\x99" /* ș */ => 's',
			"\xC8\x9A" /* Ț */ => 'T', "\xC8\x9B" /* ț */ => 't',
			// unmarked
			"\xC6\xA0" /* Ơ */ => 'O', "\xC6\xA1" /* ơ */ => 'o',
			"\xC6\xAF" /* Ư */ => 'U', "\xC6\xB0" /* ư */ => 'u',
			// grave accent
			"\xE1\xBA\xA6" /* Ầ */ => 'A', "\xE1\xBA\xA7" /* ầ */ => 'a',
			"\xE1\xBA\xB0" /* Ằ */ => 'A', "\xE1\xBA\xB1" /* ằ */ => 'a',
			"\xE1\xBB\x80" /* Ề */ => 'E', "\xE1\xBB\x81" /* ề */ => 'e',
			"\xE1\xBB\x92" /* Ồ */ => 'O', "\xE1\xBB\x93" /* ồ */ => 'o',
			"\xE1\xBB\x9C" /* Ờ */ => 'O', "\xE1\xBB\x9D" /* ờ */ => 'o',
			"\xE1\xBB\xAA" /* Ừ */ => 'U', "\xE1\xBB\xAB" /* ừ */ => 'u',
			"\xE1\xBB\xB2" /* Ỳ */ => 'Y', "\xE1\xBB\xB3" /* ỳ */ => 'y',
			// hook
			"\xE1\xBA\xA2" /* Ả */ => 'A', "\xE1\xBA\xA3" /* ả */ => 'a',
			"\xE1\xBA\xA8" /* Ẩ */ => 'A', "\xE1\xBA\xA9" /* ẩ */ => 'a',
			"\xE1\xBA\xB2" /* Ẳ */ => 'A', "\xE1\xBA\xB3" /* ẳ */ => 'a',
			"\xE1\xBA\xBA" /* Ẻ */ => 'E', "\xE1\xBA\xBB" /* ẻ */ => 'e',
			"\xE1\xBB\x82" /* Ể */ => 'E', "\xE1\xBB\x83" /* ể */ => 'e',
			"\xE1\xBB\x88" /* Ỉ */ => 'I', "\xE1\xBB\x89" /* ỉ */ => 'i',
			"\xE1\xBB\x8E" /* Ỏ */ => 'O', "\xE1\xBB\x8F" /* ỏ */ => 'o',
			"\xE1\xBB\x94" /* Ổ */ => 'O', "\xE1\xBB\x95" /* ổ */ => 'o',
			"\xE1\xBB\x9E" /* Ở */ => 'O', "\xE1\xBB\x9F" /* ở */ => 'o',
			"\xE1\xBB\xA6" /* Ủ */ => 'U', "\xE1\xBB\xA7" /* ủ */ => 'u',
			"\xE1\xBB\xAC" /* Ử */ => 'U', "\xE1\xBB\xAD" /* ử */ => 'u',
			"\xE1\xBB\xB6" /* Ỷ */ => 'Y', "\xE1\xBB\xB7" /* ỷ */ => 'y',
			// tilde
			"\xE1\xBA\xAA" /* Ẫ */ => 'A', "\xE1\xBA\xAB" /* ẫ */ => 'a',
			"\xE1\xBA\xB4" /* Ẵ */ => 'A', "\xE1\xBA\xB5" /* ẵ */ => 'a',
			"\xE1\xBA\xBC" /* Ẽ */ => 'E', "\xE1\xBA\xBD" /* ẽ */ => 'e',
			"\xE1\xBB\x84" /* Ễ */ => 'E', "\xE1\xBB\x85" /* ễ */ => 'e',
			"\xE1\xBB\x96" /* Ỗ */ => 'O', "\xE1\xBB\x97" /* ỗ */ => 'o',
			"\xE1\xBB\xA0" /* Ỡ */ => 'O', "\xE1\xBB\xA1" /* ỡ */ => 'o',
			"\xE1\xBB\xAE" /* Ữ */ => 'U', "\xE1\xBB\xAF" /* ữ */ => 'u',
			"\xE1\xBB\xB8" /* Ỹ */ => 'Y', "\xE1\xBB\xB9" /* ỹ */ => 'y',
			// acute accent
			"\xE1\xBA\xA4" /* Ấ */ => 'A', "\xE1\xBA\xA5" /* ấ */ => 'a',
			"\xE1\xBA\xAE" /* Ắ */ => 'A', "\xE1\xBA\xAF" /* ắ */ => 'a',
			"\xE1\xBA\xBE" /* Ế */ => 'E', "\xE1\xBA\xBF" /* ế */ => 'e',
			"\xE1\xBB\x90" /* Ố */ => 'O', "\xE1\xBB\x91" /* ố */ => 'o',
			"\xE1\xBB\x9A" /* Ớ */ => 'O', "\xE1\xBB\x9B" /* ớ */ => 'o',
			"\xE1\xBB\xA8" /* Ứ */ => 'U', "\xE1\xBB\xA9" /* ứ */ => 'u',
			// dot below
			"\xE1\xBA\xA0" /* Ạ */ => 'A', "\xE1\xBA\xA1" /* ạ */ => 'a',
			"\xE1\xBA\xAC" /* Ậ */ => 'A', "\xE1\xBA\xAD" /* ậ */ => 'a',
			"\xE1\xBA\xB6" /* Ặ */ => 'A', "\xE1\xBA\xB7" /* ặ */ => 'a',
			"\xE1\xBA\xB8" /* Ẹ */ => 'E', "\xE1\xBA\xB9" /* ẹ */ => 'e',
			"\xE1\xBB\x86" /* Ệ */ => 'E', "\xE1\xBB\x87" /* ệ */ => 'e',
			"\xE1\xBB\x8A" /* Ị */ => 'I', "\xE1\xBB\x8B" /* ị */ => 'i',
			"\xE1\xBB\x8C" /* Ọ */ => 'O', "\xE1\xBB\x8D" /* ọ */ => 'o',
			"\xE1\xBB\x98" /* Ộ */ => 'O', "\xE1\xBB\x99" /* ộ */ => 'o',
			"\xE1\xBB\xA2" /* Ợ */ => 'O', "\xE1\xBB\xA3" /* ợ */ => 'o',
			"\xE1\xBB\xA4" /* Ụ */ => 'U', "\xE1\xBB\xA5" /* ụ */ => 'u',
			"\xE1\xBB\xB0" /* Ự */ => 'U', "\xE1\xBB\xB1" /* ự */ => 'u',
			"\xE1\xBB\xB4" /* Ỵ */ => 'Y', "\xE1\xBB\xB5" /* ỵ */ => 'y',
		);
	}

	/**
	 * Tests that "normalizer_normalize" exists and works
	 * @return bool
	 */
	static public function hasNormalizerSupport() {
		static $ret = null;
		if (null === $ret) {
			$form_c = "\xC3\x85"; // 'LATIN CAPITAL LETTER A WITH RING ABOVE' (U+00C5)
			$form_d = "A\xCC\x8A"; // A followed by 'COMBINING RING ABOVE' (U+030A)
			$ret = (function_exists('normalizer_normalize')
				    && $form_c === normalizer_normalize($form_d));
		}
		return $ret;
	}
}
