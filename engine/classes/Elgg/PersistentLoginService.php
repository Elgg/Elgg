<?php

namespace Elgg;

use Elgg\Database\UsersRememberMeCookiesTable;
use Elgg\Traits\TimeUsing;

/**
 * \Elgg\PersistentLoginService
 *
 * If a user selects a persistent login, a long, random token is generated and stored in the cookie
 * called "elggperm", and a hash of the token is stored in the DB. If the user's PHP session expires,
 * the session boot sequence will try to log the user in via the token in the cookie.
 *
 * @internal
 */
class PersistentLoginService {
	
	use TimeUsing;
	
	/**
	 * @var array
	 */
	protected $cookie_config;
	
	/**
	 * @var string
	 */
	protected $cookie_token;
	
	/**
	 * @var \ElggSession
	 */
	protected $session;
	
	/**
	 * @var \Elgg\Security\Crypto
	 */
	protected $crypto;
	
	/**
	 * @var UsersRememberMeCookiesTable
	 */
	protected $persistent_cookie_table;
	
	/**
	 * @var callable
	 * @internal DO NOT USE. For unit test mocking
	 */
	public $_callable_elgg_set_cookie = 'elgg_set_cookie';
	
	/**
	 * Constructor
	 *
	 * @param UsersRememberMeCookiesTable $cookie_table The persistent cookie storage table
	 * @param \ElggSession                $session      The Elgg session
	 * @param \Elgg\Security\Crypto       $crypto       The cryptography service
	 * @param \Elgg\Config                $config       The site configuration
	 * @param \Elgg\Http\Request          $request      The request
	 */
	public function __construct(
			UsersRememberMeCookiesTable $cookie_table,
			\ElggSession $session,
			\Elgg\Security\Crypto $crypto,
			\Elgg\Config $config,
			\Elgg\Http\Request $request) {
		$this->persistent_cookie_table = $cookie_table;
		$this->session = $session;
		$this->crypto = $crypto;
		
		$global_cookies_config = $config->getCookieConfig();
		
		$this->cookie_config = $global_cookies_config['remember_me'];
		$this->cookie_token = $request->cookies->get($this->cookie_config['name'], '');
	}

	/**
	 * Make the user's login persistent
	 *
	 * @param \ElggUser $user The user who logged in
	 *
	 * @return void
	 */
	public function makeLoginPersistent(\ElggUser $user): void {
		$token = $this->generateToken();
		$hash = $this->hashToken($token);

		$this->persistent_cookie_table->insertHash($user, $hash);
		$this->setCookie($token);
		$this->setSessionToken($token);
	}

	/**
	 * Remove the persisted login token from client and server
	 *
	 * @return void
	 */
	public function removePersistentLogin(): void {
		if ($this->cookie_token) {
			$client_hash = $this->hashToken($this->cookie_token);
			$this->persistent_cookie_table->deleteHash($client_hash);
		}

		$this->setCookie('');
		$this->setSessionToken('');
	}

	/**
	 * Handle a password change
	 *
	 * @param \ElggUser $subject  The user whose password changed
	 * @param \ElggUser $modifier The user who changed the password
	 *
	 * @return void
	 */
	public function handlePasswordChange(\ElggUser $subject, \ElggUser $modifier = null): void {
		$this->persistent_cookie_table->deleteAllHashes($subject);
		if (!$modifier || ($modifier->guid !== $subject->guid) || !$this->cookie_token) {
			return;
		}

		$this->makeLoginPersistent($subject);
	}

	/**
	 * Boot the persistent login session, possibly returning the user who should be
	 * silently logged in.
	 *
	 * @return \ElggUser|null
	 */
	public function bootSession(): ?\ElggUser {
		if (!$this->cookie_token) {
			return null;
		}

		// is this token good?
		$user = $this->getUserFromToken($this->cookie_token);
		if ($user) {
			$this->setSessionToken($this->cookie_token);

			return $user;
		}
		
		$this->setCookie('');
		return null;
	}

	/**
	 * Get a user from a persistent cookie token
	 *
	 * @param string $token the cookie token
	 *
	 * @return \ElggUser|null
	 */
	public function getUserFromToken(string $token): ?\ElggUser {
		if (empty($token)) {
			return null;
		}
		
		$hash = $this->hashToken($token);
		return $this->getUserFromHash($hash);
	}
	
	/**
	 * Find a user with the given hash
	 *
	 * @param string $hash The hashed token
	 *
	 * @return \ElggUser|null
	 * @todo make this a protected function or remove it in Elgg 5.0
	 */
	public function getUserFromHash(string $hash): ?\ElggUser {
		if (empty($hash)) {
			return null;
		}

		$user_row = $this->persistent_cookie_table->getRowFromHash($hash);
		if (empty($user_row)) {
			return null;
		}

		$user = get_user($user_row->guid);
		return ($user instanceof \ElggUser) ? $user : null;
	}
	
	/**
	 * Update the timestamp linked to a persistent cookie code, this indicates that the code was used recently
	 *
	 * @param \ElggUser $user the user to update the cookie code for
	 *
	 * @return bool|null
	 */
	public function updateTokenUsage(\ElggUser $user): ?bool {
		if (!$this->cookie_token) {
			return null;
		}
		
		// update the database record
		// not interested in number of updated rows, as an update in the same second won't update the row
		$this->persistent_cookie_table->updateHash($user, $this->hashToken($this->cookie_token));
		
		// also update the cookie lifetime client-side
		$this->setCookie($this->cookie_token);
		
		return true;
	}
	
	/**
	 * Remove all persistent codes from the database which have expired based on the cookie config
	 *
	 * @param int|\DateTime|string $time the base timestamp to use
	 *
	 * @return bool
	 */
	public function removeExpiredTokens($time): bool {
		$time = Values::normalizeTime($time);
		
		$expires = Values::normalizeTime($this->cookie_config['expire']);
		$diff = $time->diff($expires);
		
		$time->sub($diff);
		if ($time->getTimestamp() > time()) {
			return false;
		}
		
		return (bool) $this->persistent_cookie_table->deleteExpiredHashes($time->getTimestamp());
	}

	/**
	 * Create a hash from the token
	 *
	 * @param string $token The token to hash
	 *
	 * @return string
	 */
	protected function hashToken(string $token): string {
		// note: with user passwords, you'd want legit password hashing, but since these are randomly
		// generated and long tokens, rainbow tables aren't any help.
		return md5($token);
	}

	/**
	 * Store the token in the client cookie (or remove the cookie)
	 *
	 * @param string $token Empty string to remove cookie
	 *
	 * @return void
	 */
	protected function setCookie(string $token): void {
		$cookie = new \ElggCookie($this->cookie_config['name']);
		foreach (['expire', 'path', 'domain', 'secure', 'httpOnly'] as $key) {
			$cookie->$key = $this->cookie_config[strtolower($key)];
		}
		
		$cookie->value = $token;
		if (!$token) {
			$cookie->expire = $this->getCurrentTime('-30 days')->getTimestamp();
		}
		
		call_user_func($this->_callable_elgg_set_cookie, $cookie);
	}

	/**
	 * Store the token in the session (or remove it from the session)
	 *
	 * @param string $token The token to store in session. Empty string to remove.
	 *
	 * @return void
	 */
	protected function setSessionToken(string $token): void {
		if ($token) {
			$this->session->set('code', $token);
		} else {
			$this->session->remove('code');
		}
	}

	/**
	 * Generate a random token (base 64 URL)
	 *
	 * The first char is always "z" to indicate the value has more entropy than the
	 * previously generated ones.
	 *
	 * @return string
	 */
	protected function generateToken(): string {
		return 'z' . $this->crypto->getRandomString(31);
	}
}
