<?php
/**
 * \ElggCrypto
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
	 * Character set for hexadecimal
	 */
	const CHARS_HEX = '0123456789abcdef';

	/**
	 * Generate a string of highly randomized bytes (over the full 8-bit range).
	 *
	 * @param int $length Number of bytes needed
	 * @return string Random bytes
	 *
	 * @author George Argyros <argyros.george@gmail.com>
	 * @copyright 2012, George Argyros. All rights reserved.
	 * @license Modified BSD
	 * @link https://github.com/GeorgeArgyros/Secure-random-bytes-in-PHP/blob/master/srand.php Original
	 *
	 * Redistribution and use in source and binary forms, with or without
	 * modification, are permitted provided that the following conditions are met:
	 *    * Redistributions of source code must retain the above copyright
	 *      notice, this list of conditions and the following disclaimer.
	 *    * Redistributions in binary form must reproduce the above copyright
	 *      notice, this list of conditions and the following disclaimer in the
	 *      documentation and/or other materials provided with the distribution.
	 *    * Neither the name of the <organization> nor the
	 *      names of its contributors may be used to endorse or promote products
	 *      derived from this software without specific prior written permission.
	 *
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	 * DISCLAIMED. IN NO EVENT SHALL GEORGE ARGYROS BE LIABLE FOR ANY
	 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	 */
	public function getRandomBytes($length) {
		$SSLstr = '4'; // http://xkcd.com/221/

		/**
		 * Our primary choice for a cryptographic strong randomness function is
		 * openssl_random_pseudo_bytes.
		 */
		if (function_exists('openssl_random_pseudo_bytes') && substr(PHP_OS, 0, 3) !== 'WIN') {
			$SSLstr = openssl_random_pseudo_bytes($length, $strong);
			if ($strong) {
				return $SSLstr;
			}
		}

		/**
		 * If mcrypt extension is available then we use it to gather entropy from
		 * the operating system's PRNG. This is better than reading /dev/urandom
		 * directly since it avoids reading larger blocks of data than needed.
		 */
		if (function_exists('mcrypt_create_iv') && substr(PHP_OS, 0, 3) !== 'WIN') {
			$str = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
			if ($str !== false) {
				return $str;
			}
		}

		/**
		 * No build-in crypto randomness function found. We collect any entropy
		 * available in the PHP core PRNGs along with some filesystem info and memory
		 * stats. To make this data cryptographically strong we add data either from
		 * /dev/urandom or if its unavailable, we gather entropy by measuring the
		 * time needed to compute a number of SHA-1 hashes.
		 */
		$str = '';
		$bits_per_round = 2; // bits of entropy collected in each clock drift round
		$msec_per_round = 400; // expected running time of each round in microseconds
		$hash_len = 20; // SHA-1 Hash length
		$total = $length; // total bytes of entropy to collect

		$handle = @fopen('/dev/urandom', 'rb');
		if ($handle && function_exists('stream_set_read_buffer')) {
			@stream_set_read_buffer($handle, 0);
		}

		do {
			$bytes = ($total > $hash_len) ? $hash_len : $total;
			$total -= $bytes;

			//collect any entropy available from the PHP system and filesystem
			$entropy = rand() . uniqid(mt_rand(), true) . $SSLstr;
			$entropy .= implode('', @fstat(@fopen(__FILE__, 'r')));
			$entropy .= memory_get_usage() . getmypid();
			$entropy .= serialize($_ENV) . serialize($_SERVER);
			if (function_exists('posix_times')) {
				$entropy .= serialize(posix_times());
			}
			if (function_exists('zend_thread_id')) {
				$entropy .= zend_thread_id();
			}

			if ($handle) {
				$entropy .= @fread($handle, $bytes);
			} else {
				// Measure the time that the operations will take on average
				for ($i = 0; $i < 3; $i++) {
					$c1 = microtime(true);
					$var = sha1(mt_rand());
					for ($j = 0; $j < 50; $j++) {
						$var = sha1($var);
					}
					$c2 = microtime(true);
					$entropy .= $c1 . $c2;
				}

				// Based on the above measurement determine the total rounds
				// in order to bound the total running time.
				$rounds = (int) ($msec_per_round * 50 / (int) (($c2 - $c1) * 1000000));

				// Take the additional measurements. On average we can expect
				// at least $bits_per_round bits of entropy from each measurement.
				$iter = $bytes * (int) (ceil(8 / $bits_per_round));

				for ($i = 0; $i < $iter; $i++) {
					$c1 = microtime();
					$var = sha1(mt_rand());
					for ($j = 0; $j < $rounds; $j++) {
						$var = sha1($var);
					}
					$c2 = microtime();
					$entropy .= $c1 . $c2;
				}
			}

			// We assume sha1 is a deterministic extractor for the $entropy variable.
			$str .= sha1($entropy, true);

		} while ($length > strlen($str));

		if ($handle) {
			@fclose($handle);
		}

		return substr($str, 0, $length);
	}

	/**
	 * Get an HMAC token builder/validator object
	 *
	 * @param mixed  $data HMAC data or serializable data
	 * @param string $algo Hash algorithm
	 * @param string $key  Optional key (default uses site secret)
	 *
	 * @return \Elgg\Security\Hmac
	 */
	public function getHmac($data, $algo = 'sha256', $key = '') {
		if (!$key) {
			$key = _elgg_services()->siteSecret->get(true);
		}
		return new Elgg\Security\Hmac($key, [$this, 'areEqual'], $data, $algo);
	}

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
	 * @throws InvalidArgumentException
	 *
	 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
	 * @license   http://framework.zend.com/license/new-bsd New BSD License
	 *
	 * @see https://github.com/zendframework/zf2/blob/master/library/Zend/Math/Rand.php#L179
	 */
	public function getRandomString($length, $chars = null) {
		if ($length < 1) {
			throw new \InvalidArgumentException('Length should be >= 1');
		}

		if (empty($chars)) {
			$numBytes = ceil($length * 0.75);
			$bytes    = $this->getRandomBytes($numBytes);
			$string = substr(rtrim(base64_encode($bytes), '='), 0, $length);

			// Base64 URL
			return strtr($string, '+/', '-_');
		}

		if ($chars == self::CHARS_HEX) {
			// hex is easy
			$bytes = $this->getRandomBytes(ceil($length / 2));
			return substr(bin2hex($bytes), 0, $length);
		}

		$listLen = strlen($chars);

		if ($listLen == 1) {
			return str_repeat($chars, $length);
		}

		$bytes  = $this->getRandomBytes($length);
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
