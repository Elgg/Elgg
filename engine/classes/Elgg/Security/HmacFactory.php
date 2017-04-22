<?php
namespace Elgg\Security;

use ElggCrypto;
use Elgg\Database\SiteSecret;

/**
 * Provides a factory for HMAC objects
 */
class HmacFactory {

	/**
	 * @var SiteSecret
	 */
	private $site_secret;

	/**
	 * @var ElggCrypto
	 */
	private $crypto;

	/**
	 * Constructor
	 *
	 * @param SiteSecret $secret Site secret
	 * @param ElggCrypto $crypto Elgg crypto service
	 */
	public function __construct(SiteSecret $secret, ElggCrypto $crypto) {
		$this->site_secret = $secret;
		$this->crypto = $crypto;
	}

	/**
	 * Get an HMAC token builder/validator object
	 *
	 * @param mixed  $data HMAC data or serializable data
	 * @param string $algo Hash algorithm
	 * @param string $key  Optional key (default uses site secret)
	 *
	 * @return Hmac
	 */
	public function getHmac($data, $algo = 'sha256', $key = '') {
		if (!$key) {
			$key = $this->site_secret->get(true);
		}
		return new Hmac($key, [$this->crypto, 'areEqual'], $data, $algo);
	}
}
