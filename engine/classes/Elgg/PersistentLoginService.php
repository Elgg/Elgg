<?php
namespace Elgg;
/**
 * \Elgg\PersistentLoginService
 *
 * If a user selects a persistent login, a long, random token is generated and stored in the cookie
 * called "elggperm", and a hash of the token is stored in the DB. If the user's PHP session expires,
 * the session boot sequence will try to log the user in via the token in the cookie.
 *
 * Before Elgg 1.9, the token hashes were stored as "code" in the users_entity table.
 *
 * In Elgg 1.9, the token hashes are stored as "code" in the users_remember_me_cookies
 * table, allowing multiple browsers to maintain persistent logins.
 *
 * @todo Rename the "code" DB column to "hash"
 *
 * Legacy notes: This feature used to be called "remember me"; confusingly, both the tokens and the
 * hashes were called "codes"; old tokens were hexadecimal and lower entropy; new tokens are
 * base64 URL and always begin with the letter "z"; the boot sequence replaces old tokens whenever
 * possible.
 *
 * @package Elgg.Core
 *
 * @access private
 */
class PersistentLoginService {

	/**
	 * Constructor
	 *
	 * @param Database     $db            The DB service
	 * @param \ElggSession $session       The Elgg session
	 * @param \ElggCrypto  $crypto        The cryptography service
	 * @param array        $cookie_config The persistent login cookie settings
	 * @param string       $cookie_token  The token from the request cookie
	 * @param int          $time          The current time
	 */
	public function __construct(
			Database $db,
			\ElggSession $session,
			\ElggCrypto $crypto,
			array $cookie_config,
			$cookie_token,
			$time = null) {
		$this->db = $db;
		$this->session = $session;
		$this->crypto = $crypto;
		$this->cookie_config = $cookie_config;
		$this->cookie_token = $cookie_token;

		$prefix = $this->db->getTablePrefix();
		$this->table = "{$prefix}users_remember_me_cookies";
		$this->time = is_numeric($time) ? (int)$time : time();
	}

	/**
	 * Make the user's login persistent
	 *
	 * @param \ElggUser $user The user who logged in
	 * 
	 * @return void
	 */
	public function makeLoginPersistent(\ElggUser $user) {
		$token = $this->generateToken();
		$hash = $this->hashToken($token);

		$this->storeHash($user, $hash);
		$this->setCookie($token);
		$this->setSession($token);
	}

	/**
	 * Remove the persisted login token from client and server
	 * 
	 * @return void
	 */
	public function removePersistentLogin() {
		if ($this->cookie_token) {
			$client_hash = $this->hashToken($this->cookie_token);
			$this->removeHash($client_hash);
		}

		$this->setCookie("");
		$this->setSession("");
	}

	/**
	 * Handle a password change
	 *
	 * @param \ElggUser $subject  The user whose password changed
	 * @param \ElggUser $modifier The user who changed the password
	 * 
	 * @return void
	 */
	public function handlePasswordChange(\ElggUser $subject, \ElggUser $modifier = null) {
		$this->removeAllHashes($subject);
		if (!$modifier || ($modifier->guid !== $subject->guid) || !$this->cookie_token) {
			return;
		}

		$this->makeLoginPersistent($modifier);
	}

	/**
	 * Boot the persistent login session, possibly returning the user who should be
	 * silently logged in.
	 *
	 * @return \ElggUser|null
	 */
	public function bootSession() {
		if (!$this->cookie_token) {
			return null;
		}

		// is this token good?
		$cookie_hash = $this->hashToken($this->cookie_token);
		$user = $this->getUserFromHash($cookie_hash);
		if ($user) {
			$this->setSession($this->cookie_token);
			// note: if the token is legacy, we don't both replacing it here because
			// it will be replaced during the next request boot
			return $user;
		} else {
			if ($this->isLegacyToken($this->cookie_token)) {
				// may be attempt to brute force legacy low-entropy tokens
				call_user_func($this->_callable_sleep, 1);
			}
			$this->setCookie('');
		}
	}

	/**
	 * Replace the user's token if it's a legacy hexadecimal token
	 *
	 * @param \ElggUser $logged_in_user The logged in user
	 * 
	 * @return void
	 */
	public function replaceLegacyToken(\ElggUser $logged_in_user) {
		if (!$this->cookie_token || !$this->isLegacyToken($this->cookie_token)) {
			return;
		}

		// replace user's old weaker-entropy code with new one
		$this->removeHash($this->hashToken($this->cookie_token));
		$this->makeLoginPersistent($logged_in_user);
	}

	/**
	 * Find a user with the given hash
	 *
	 * @param string $hash The hashed token
	 * 
	 * @return \ElggUser|null
	 */
	public function getUserFromHash($hash) {
		if (!$hash) {
			return null;
		}

		$hash = $this->db->sanitizeString($hash);
		$query = "SELECT guid FROM {$this->table} WHERE code = '$hash'";
		try {
			$user_row = $this->db->getDataRow($query);
		} catch (\DatabaseException $e) {
			return $this->handleDbException($e);
		}
		if (!$user_row) {
			return null;
		}

		$user = call_user_func($this->_callable_get_user, $user_row->guid);
		return $user ? $user : null;
	}

	/**
	 * Store a hash in the DB
	 *
	 * @param \ElggUser $user The user for whom we're storing the hash
	 * @param string    $hash The hashed token
	 * 
	 * @return void
	 */
	protected function storeHash(\ElggUser $user, $hash) {
		// This prevents inserting the same hash twice, which seems to be happening in some rare cases
		// and for unknown reasons. See https://github.com/Elgg/Elgg/issues/8104
		$this->removeHash($hash);

		$time = time();
		$hash = $this->db->sanitizeString($hash);

		$query = "
			INSERT INTO {$this->table} (code, guid, timestamp)
		    VALUES ('$hash', {$user->guid}, $time)
		";
		try {
			$this->db->insertData($query);
		} catch (\DatabaseException $e) {
			$this->handleDbException($e);
		}
	}

	/**
	 * Remove a hash from the DB
	 *
	 * @param string $hash The hashed token to remove (unused before 1.9)
	 * @return void
	 */
	protected function removeHash($hash) {
		$hash = $this->db->sanitizeString($hash);

		$query = "DELETE FROM {$this->table} WHERE code = '$hash'";
		try {
			$this->db->deleteData($query);
		} catch (\DatabaseException $e) {
			$this->handleDbException($e);
		}
	}

	/**
	 * Swallow a schema not upgraded exception, otherwise rethrow it
	 *
	 * @param \DatabaseException $exception The exception to handle
	 * @param string             $default   The value to return if the table doesn't exist yet
	 * 
	 * @return mixed
	 *
	 * @throws \DatabaseException
	 */
	protected function handleDbException(\DatabaseException $exception, $default = null) {
		if (false !== strpos($exception->getMessage(), "users_remember_me_cookies' doesn't exist")) {
			// schema has not been updated so we swallow this exception
			return $default;
		} else {
			throw $exception;
		}
	}

	/**
	 * Remove all the hashes associated with a user
	 *
	 * @param \ElggUser $user The user for whom we're removing hashes
	 * 
	 * @return void
	 */
	protected function removeAllHashes(\ElggUser $user) {
		$query = "DELETE FROM {$this->table} WHERE guid = '{$user->guid}'";
		try {
			$this->db->deleteData($query);
		} catch (\DatabaseException $e) {
			$this->handleDbException($e);
		}
	}

	/**
	 * Create a hash from the token
	 *
	 * @param string $token The token to hash
	 * 
	 * @return string
	 */
	protected function hashToken($token) {
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
	protected function setCookie($token) {
		$cookie = new \ElggCookie($this->cookie_config['name']);
		foreach (array('expire', 'path', 'domain', 'secure', 'httponly') as $key) {
			$cookie->$key = $this->cookie_config[$key];
		}
		$cookie->value = $token;
		if (!$token) {
			$cookie->expire = $this->time - (86400 * 30);
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
	protected function setSession($token) {
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
	protected function generateToken() {
		return 'z' . $this->crypto->getRandomString(31);
	}

	/**
	 * Is the given token a legacy MD5 hash?
	 *
	 * @param string $token The token to analyze
	 * 
	 * @return bool
	 */
	protected function isLegacyToken($token) {
		return (isset($token[0]) && $token[0] !== 'z');
	}

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $table;

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
	 * @var \ElggCrypto
	 */
	protected $crypto;

	/**
	 * @var int
	 */
	protected $time;

	/**
	 * DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_get_user = 'get_user';

	/**
	 * DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_elgg_set_cookie = 'elgg_set_cookie';

	/**
	 * DO NOT USE. For unit test mocking
	 * @access private
	 */
	public $_callable_sleep = 'sleep';
}

