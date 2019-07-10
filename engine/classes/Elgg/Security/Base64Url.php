<?php

namespace Elgg\Security;

/**
 * Encode and decode Base 64 URL
 *
 * @internal
 */
class Base64Url {

	/**
	 * Encode base 64 URL
	 *
	 * @param string $bytes Bytes to encode
	 * @return string
	 */
	public static function encode($bytes) {
		$bytes = base64_encode($bytes);
		$bytes = rtrim($bytes, '=');
		return strtr($bytes, '+/', '-_');
	}

	/**
	 * Decode base 64 URL
	 *
	 * @param string $bytes Bytes to decode
	 * @return string|false
	 */
	public static function decode($bytes) {
		$bytes = strtr($bytes, '-_', '+/');
		return base64_decode($bytes);
	}
}
