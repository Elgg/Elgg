<?php

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;

/**
 * Cryptographic services
 *
 * @internal
 */
class ElggCrypto {

	/**
	 * Character set for temp passwords (no risk of embedded profanity/glyphs that look similar)
	 */
	const CHARS_PASSWORD = 'bcdfghjklmnpqrstvwxyz2346789';

	/**
	 * Character set for hexadecimal
	 */
	const CHARS_HEX = '0123456789abcdef';

	/**
	 * Generate a random string of specified length.
	 *
	 * Uses supplied character list for generating the new string.
	 * If no character list provided - uses Base64 URL character set.
	 *
	 * @param int         $length Desired length of the string
	 * @param string|null $chars  Characters to be chosen from randomly. If not given, the Base64 URL
	 *                            charset will be used.
	 *
	 * @return string The random string
	 *
	 * @throws ElggInvalidArgumentException
	 *
	 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
	 * @license   http://framework.zend.com/license/new-bsd New BSD License
	 *
	 * @see https://github.com/zendframework/zf2/blob/master/library/Zend/Math/Rand.php#L179
	 */
	public function getRandomString($length, $chars = null) {
		if ($length < 1) {
			throw new ElggInvalidArgumentException('Length should be >= 1');
		}

		if (empty($chars)) {
			$numBytes = ceil($length * 0.75);
			$bytes = random_bytes($numBytes);
			$string = substr(rtrim(base64_encode($bytes), '='), 0, $length);

			// Base64 URL
			return strtr($string, '+/', '-_');
		}

		if ($chars == self::CHARS_HEX) {
			// hex is easy
			$bytes = random_bytes(ceil($length / 2));
			return substr(bin2hex($bytes), 0, $length);
		}

		$listLen = strlen($chars);

		if ($listLen == 1) {
			return str_repeat($chars, $length);
		}

		$bytes  = random_bytes($length);
		$pos    = 0;
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$pos     = ($pos + ord($bytes[$i])) % $listLen;
			$result .= $chars[$pos];
		}

		return $result;
	}

	/**
	 * Are two strings equal (compared in constant time)?
	 *
	 * @param string $str1 First string to compare
	 * @param string $str2 Second string to compare
	 *
	 * @return bool
	 *
	 * Based on password_verify in PasswordCompat
	 * @author Anthony Ferrara <ircmaxell@php.net>
	 * @license http://www.opensource.org/licenses/mit-license.html MIT License
	 * @copyright 2012 The Authors
	 */
	public function areEqual($str1, $str2) {
		$len1 = $this->strlen($str1);
		$len2 = $this->strlen($str2);
		if ($len1 !== $len2) {
			return false;
		}

		$status = 0;
		for ($i = 0; $i < $len1; $i++) {
			$status |= (ord($str1[$i]) ^ ord($str2[$i]));
		}

		return $status === 0;
	}

	/**
	 * Count the number of bytes in a string
	 *
	 * We cannot simply use strlen() for this, because it might be overwritten by the mbstring extension.
	 * In this case, strlen() will count the number of *characters* based on the internal encoding. A
	 * sequence of bytes might be regarded as a single multibyte character.
	 *
	 * Use elgg_strlen() to count UTF-characters instead of bytes.
	 *
	 * @param string $binary_string The input string
	 *
	 * @return int The number of bytes
	 *
	 * From PasswordCompat\binary\_strlen
	 * @author Anthony Ferrara <ircmaxell@php.net>
	 * @license http://www.opensource.org/licenses/mit-license.html MIT License
	 * @copyright 2012 The Authors
	 */
	protected function strlen($binary_string) {
		if (function_exists('mb_strlen')) {
			return mb_strlen($binary_string, '8bit');
		}
		return strlen($binary_string);
	}
}
