<?php
namespace Elgg\Database;

/**
 * Manages a site-specific secret key, encoded as a 32 byte string "secret"
 *
 * The key can have two formats:
 *   - Since 1.8.17 all keys generated are Base64URL-encoded with the 1st character set to "z" so that
 *     the format can be recognized. With one character lost, this makes the keys effectively 186 bits.
 *   - Before 1.8.17 keys were hex-encoded (128 bits) but created from insufficiently random sources.
 *
 * The hex keys were created with rand() as the only decent source of entropy (the site's creation time
 * is not too difficult to find). As such, systems with a low getrandmax() value created particularly
 * weak keys. You can check key string using getStrength().
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class SiteSecret {

	/**
	 * Initialise the site secret (32 bytes: "z" to indicate format + 186-bit key in Base64 URL).
	 *
	 * Used during installation and saves as a datalist.
	 *
	 * Note: Old secrets were hex encoded.
	 *
	 * @return mixed The site secret hash or false
	 * @access private
	 */
	function init() {
		$secret = 'z' . _elgg_services()->crypto->getRandomString(31);

		if (_elgg_services()->datalist->set('__site_secret__', $secret)) {
			return $secret;
		}

		return false;
	}

	/**
	 * Returns the site secret.
	 *
	 * Used to generate difficult to guess hashes for sessions and action tokens.
	 *
	 * @param bool $raw If true, a binary key will be returned
	 *
	 * @return string Site secret.
	 * @access private
	 */
	function get($raw = false) {
		$secret = _elgg_services()->datalist->get('__site_secret__');
		if (!$secret) {
			$secret = init_site_secret();
		}

		if ($raw) {
			// try to return binary key
			if ($secret[0] === 'z') {
				// new keys are "z" + base64URL
				$base64 = strtr(substr($secret, 1), '-_', '+/');
				$key = base64_decode($base64);
				if ($key !== false) {
					// on failure, at least return string key :/
					return $key;
				}
			} else {
				// old keys are hex
				return hex2bin($secret);
			}
		}

		return $secret;
	}

	/**
	 * Get the strength of the site secret
	 *
	 * If "weak" or "moderate" is returned, this assumes we're running on the same system that created
	 * the key.
	 *
	 * @return string "strong", "moderate", or "weak"
	 * @access private
	 */
	function getStrength() {
		$secret = get_site_secret();
		if ($secret[0] !== 'z') {
			$rand_max = getrandmax();
			if ($rand_max < pow(2, 16)) {
				return 'weak';
			}
			if ($rand_max < pow(2, 32)) {
				return 'moderate';
			}
		}
		return 'strong';
	}

}
