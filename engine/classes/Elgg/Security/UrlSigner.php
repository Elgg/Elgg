<?php

namespace Elgg\Security;

use Elgg\Exceptions\HttpException;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * Component for creating signed URLs
 *
 * @internal
 */
class UrlSigner {

	const KEY_MAC = '__elgg_mac';
	const KEY_EXPIRES = '__elgg_exp';

	/**
	 * Normalizes and signs the URL with SHA256 HMAC key
	 *
	 * @note Signed URLs do not offer CSRF protection and should not be used instead of action tokens.
	 *
	 * @param string $url     URL to sign
	 * @param string $expires Expiration time
	 *                        Accepts a string suitable for strtotime()
	 *                        Falsey values indicate non-expiring URL
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function sign($url, $expires = false) {
		$url = elgg_normalize_url($url);
		
		$parts = parse_url($url);

		if (isset($parts['query'])) {
			$query = elgg_parse_str($parts['query']);
		} else {
			$query = [];
		}

		if (isset($query[self::KEY_MAC])) {
			throw new InvalidArgumentException('URL has already been signed');
		}

		if ($expires) {
			$query[self::KEY_EXPIRES] = strtotime($expires);
		}

		ksort($query);

		$parts['query'] = http_build_query($query);

		$url = elgg_http_build_url($parts, false);

		$token = elgg_build_hmac($url)->getToken();

		return elgg_http_add_url_query_elements($url, [
			self::KEY_MAC => $token,
		]);
	}

	/**
	 * Validates HMAC signature
	 *
	 * @param string $url URL to validate
	 * @return bool
	 */
	public function isValid($url) {

		$parts = parse_url($url);

		if (isset($parts['query'])) {
			$query = elgg_parse_str($parts['query']);
		} else {
			$query = [];
		}
		
		if (!isset($query[self::KEY_MAC])) {
			// No signature found
			return false;
		}

		$token = $query[self::KEY_MAC];
		unset($query[self::KEY_MAC]);

		if (isset($query[self::KEY_EXPIRES]) && $query[self::KEY_EXPIRES] < time()) {
			// Signature has expired
			return false;
		}

		ksort($query);
		
		$parts['query'] = http_build_query($query);

		$url = elgg_http_build_url($parts, false);
		
		return elgg_build_hmac($url)->matchesToken($token);
	}
	
	/**
	 * Assert that an url is signed correctly
	 *
	 * @param string $url the url to check
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function assertValid($url) {
		if (!$this->isValid($url)) {
			throw new HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
		}
	}
}
