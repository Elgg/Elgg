<?php
/**
 * ElggCrypto
 *
 * @package    Elgg.Core
 * @subpackage Crypto
 *
 * @access private
 */
class ElggCrypto {

	/**
	 * Character set for temp passwords (no risk of embedded profanity/glyphs that look similar)
	 */
	const CHARS_PASSWORD = 'bcdfghjklmnpqrstvwxyz2346789';

	/**
	 * Returns a string of highly randomized bytes (over the full 8-bit range).
	 *
	 * This function is better than simply calling mt_rand() or any other built-in
	 * PHP function because it can return a long string of bytes (compared to < 4
	 * bytes normally from mt_rand()) and uses the best available pseudo-random
	 * source.
	 *
	 * @param int $count The number of characters (bytes) to return in the string.
	 * @return string
	 *
	 * @copyright Copyright 2001 - 2012 by the original authors
	 *            https://github.com/drupal/drupal/blob/7.x/COPYRIGHT.txt
	 * @license   https://github.com/drupal/drupal/blob/7.x/LICENSE.txt GPL 2
	 *
	 * @see https://github.com/drupal/drupal/blob/7.x/includes/bootstrap.inc#L1942
	 */
	public static function getRandomBytes($count)  {
		// $random_state does not use drupal_static as it stores random bytes.
		static $random_state, $bytes, $php_compatible;
		// Initialize on the first call. The contents of $_SERVER includes a mix of
		// user-specific and system information that varies a little with each page.
		if (!isset($random_state)) {
			$random_state = print_r($_SERVER, true);
			if (function_exists('getmypid')) {
				// Further initialize with the somewhat random PHP process ID.
				$random_state .= getmypid();
			}
			$bytes = '';
		}
		if (strlen($bytes) < $count) {
			// PHP versions prior 5.3.4 experienced openssl_random_pseudo_bytes()
			// locking on Windows and rendered it unusable.
			if (!isset($php_compatible)) {
				$php_compatible = version_compare(PHP_VERSION, '5.3.4', '>=');
			}
			// /dev/urandom is available on many *nix systems and is considered the
			// best commonly available pseudo-random source.
			if ($fh = @fopen('/dev/urandom', 'rb')) {
				// PHP only performs buffered reads, so in reality it will always read
				// at least 4096 bytes. Thus, it costs nothing extra to read and store
				// that much so as to speed any additional invocations.
				$bytes .= fread($fh, max(4096, $count));
				fclose($fh);
			} elseif ($php_compatible && function_exists('openssl_random_pseudo_bytes')) {
				// openssl_random_pseudo_bytes() will find entropy in a system-dependent
				// way.
				$bytes .= openssl_random_pseudo_bytes($count - strlen($bytes));
			}
			// If /dev/urandom is not available or returns no bytes, this loop will
			// generate a good set of pseudo-random bytes on any system.
			// Note that it may be important that our $random_state is passed
			// through hash() prior to being rolled into $output, that the two hash()
			// invocations are different, and that the extra input into the first one -
			// the microtime() - is prepended rather than appended. This is to avoid
			// directly leaking $random_state via the $output stream, which could
			// allow for trivial prediction of further "random" numbers.
			while (strlen($bytes) < $count) {
				$random_state = hash('sha256', microtime() . mt_rand() . $random_state);
				$bytes .= hash('sha256', mt_rand() . $random_state, true);
			}
		}
		$output = substr($bytes, 0, $count);
		$bytes = substr($bytes, $count);
		return $output;
	}

	/**
	 * Generate a random string of specified length.
	 *
	 * Uses supplied character list for generating the new string.
	 * If no character list provided - uses Base64 URL character set.
	 *
	 * @param  int         $length Desired length of the string
	 * @param  string|null $chars  Characters to be chosen from randomly. If not given, the Base64 URL
	 *                             charset will be used.
	 *
	 * @return string The random string
	 *
	 * @throws InvalidArgumentException
	 *
	 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
	 * @license   http://framework.zend.com/license/new-bsd New BSD License
	 *
	 * @see https://github.com/zendframework/zf2/blob/master/library/Zend/Math/Rand.php#L179
	 */
	public static function getRandomString($length, $chars = null)
	{
		if ($length < 1) {
			throw new InvalidArgumentException('Length should be >= 1');
		}

		if (empty($chars)) {
			$numBytes = ceil($length * 0.75);
			$bytes    = self::getRandomBytes($numBytes);
			$string = substr(rtrim(base64_encode($bytes), '='), 0, $length);

			// Base64 URL
			return strtr($string, '+/', '-_');
		}

		$listLen = strlen($chars);

		if ($listLen == 1) {
			return str_repeat($chars, $length);
		}

		$bytes  = self::getRandomBytes($length);
		$pos    = 0;
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$pos     = ($pos + ord($bytes[$i])) % $listLen;
			$result .= $chars[$pos];
		}

		return $result;
	}
}
