<?php
namespace Elgg\Database;

use Elgg\Config as ElggConfig;
use Elgg\Database;
use ElggCrypto;

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
 * @since  1.10.0
 */
class SiteSecret {

	const CONFIG_KEY = '__site_secret__';

	/**
	 * Constructor
	 *
	 * @param string $key Site key (32 hex chars, or "z" and 31 base64 chars)
	 */
	public function __construct($key) {
		$this->key = $key;
	}

	/**
	 * @var string
	 */
	private $key;

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
	public function get($raw = false) {
		if (!$this->key) {
			throw new \RuntimeException('Secret key is not set');
		}

		if (!$raw) {
			return $this->key;
		}

		// try to return binary key
		if ($this->key[0] === 'z') {
			// new keys are "z" + base64URL
			$base64 = strtr(substr($this->key, 1), '-_', '+/');
			$key = base64_decode($base64);
			if ($key !== false) {
				return $key;
			}

			// on failure, at least return string key :/
			return $this->key;
		}

		// old keys are hex
		return hex2bin($this->key);
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
	public function getStrength() {
		$secret = $this->get();
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

	/**
	 * Initialise the site secret (32 bytes: "z" to indicate format + 186-bit key in Base64 URL)
	 * and save to config table.
	 *
	 * Used during installation or regeneration.
	 *
	 * @param ElggCrypto  $crypto Crypto service
	 * @param ConfigTable $table  Config table
	 * @return SiteSecret
	 */
	public static function regenerate(ElggCrypto $crypto, ConfigTable $table) {
		$key = 'z' . $crypto->getRandomString(31);

		$table->set(self::CONFIG_KEY, $key);

		return new self($key);
	}

	/**
	 * Build a SiteSecret from config/storage, and remove the value from config memory.
	 *
	 * @param ElggConfig  $config Config service
	 * @param ConfigTable $table  Config table
	 *
	 * @return SiteSecret
	 */
	public static function load(ElggConfig $config, ConfigTable $table) {
		// in case it's in settings.php
		$key = $config->get(self::CONFIG_KEY);
		if ($key) {
			// don't leave this sitting around in config, which is more likely
			// to get dumped.
			$config->set(self::CONFIG_KEY, null);
		} else {
			$key = $table->get(self::CONFIG_KEY);
		}

		return new self($key);
	}
}
