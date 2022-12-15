<?php

namespace Elgg\Security;

use Elgg\Traits\TimeUsing;

/**
 * Provides a factory for HMAC objects
 */
class HmacFactory {

	use TimeUsing;
	
	/**
	 * @var SiteSecret
	 */
	protected $site_secret;

	/**
	 * @var Crypto
	 */
	protected $crypto;

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
	
	/**
	 * Generates a unique invite code for a user
	 *
	 * @param string $username The username of the user sending the invitation
	 *
	 * @return string Invite code
	 * @see self::validateInviteCode()
	 * @since 5.0
	 */
	public function generateInviteCode(string $username): string {
		$time = $this->getCurrentTime()->getTimestamp();
		$token = $this->getHmac([$time, $username])->getToken();
		
		return "{$time}.{$token}";
	}
	
	/**
	 * Validate a user's invite code
	 *
	 * @param string $username The username
	 * @param string $code     The invite code
	 *
	 * @return bool
	 * @see self::generateInviteCode()
	 * @since 5.0
	 */
	public function validateInviteCode(string $username, string $code): bool {
		// validate the format of the token created by self::generateInviteCode()
		$matches = [];
		if (!preg_match('~^(\d+)\.([a-zA-Z0-9\-_]+)$~', $code, $matches)) {
			return false;
		}
		
		$time = (int) $matches[1];
		$mac = $matches[2];
		
		return $this->getHmac([$time, $username])->matchesToken($mac);
	}
}
