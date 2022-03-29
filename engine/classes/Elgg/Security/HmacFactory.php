<?php
namespace Elgg\Security;

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
	 * @var Crypto
	 */
	private $crypto;

	/**
	 * Constructor
	 *
	 * @param SiteSecret $secret Site secret
	 * @param Crypto     $crypto Elgg crypto service
	 */
	public function __construct(SiteSecret $secret, Crypto $crypto) {
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
