<?php
namespace Elgg\Database;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
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
	 * @return string Site secret.
	 * @access private
	 */
	function get() {
		$secret = _elgg_services()->datalist->get('__site_secret__');
		if (!$secret) {
			$secret = init_site_secret();
		}
	
		return $secret;
	}
	
	/**
	 * Get the strength of the site secret
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