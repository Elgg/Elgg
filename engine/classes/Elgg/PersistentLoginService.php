<?php

namespace Elgg;

use Elgg\Database\Delete;
use Elgg\Database\Insert;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Exceptions\DatabaseException;
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
	 * @var string name of the persistent cookies database table
	 */
	const TABLE_NAME = 'users_remember_me_cookies';
	
	/**
	 * @var Database
	 */
	protected $db;
	
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
	 * @var callable
	 * @internal DO NOT USE. For unit test mocking
	 */
	public $_callable_get_user = 'get_user';
	
	/**
	 * @var callable
	 * @internal DO NOT USE. For unit test mocking
	 */
	public $_callable_elgg_set_cookie = 'elgg_set_cookie';
	
	/**
	 * @var callable
	 * @internal DO NOT USE. For unit test mocking
	 */
	public $_callable_sleep = 'sleep';
	
	/**
	 * Constructor
	 *
	 * @param Database     $db            The DB service
	 * @param \ElggSession $session       The Elgg session
	 * @param \ElggCrypto  $crypto        The cryptography service
	 * @param array        $cookie_config The persistent login cookie settings
	 * @param string       $cookie_token  The token from the request cookie
	 */
	public function __construct(
			Database $db,
			\ElggSession $session,
			\ElggCrypto $crypto,
			array $cookie_config,
			$cookie_token) {
		$this->db = $db;
		$this->session = $session;
		$this->crypto = $crypto;
		$this->cookie_config = $cookie_config;
		$this->cookie_token = $cookie_token;
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

		$this->storeHash($user, $hash);
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
			$this->removeHash($client_hash);
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
	public function bootSession(): ?\ElggUser {
		if (!$this->cookie_token) {
			return null;
		}

		// is this token good?
		$cookie_hash = $this->hashToken($this->cookie_token);
		$user = $this->getUserFromHash($cookie_hash);
		if ($user) {
			$this->setSessionToken($this->cookie_token);

			return $user;
		}
		
		$this->setCookie('');
		return null;
	}

	/**
	 * Find a user with the given hash
	 *
	 * @param string $hash The hashed token
	 *
	 * @return \ElggUser|null
	 */
	public function getUserFromHash(string $hash): ?\ElggUser {
		if (!$hash) {
			return null;
		}

		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('guid')
			->where($select->compare('code', '=', $hash, ELGG_VALUE_STRING));

		try {
			$user_row = $this->db->getDataRow($select);
		} catch (DatabaseException $e) {
			$this->handleDbException($e);
			return null;
		}
		
		if (empty($user_row)) {
			return null;
		}

		$user = call_user_func($this->_callable_get_user, $user_row->guid);
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
		$update = Update::table(self::TABLE_NAME);
		$update->set('timestamp', $update->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $user->guid, ELGG_VALUE_GUID))
			->andWhere($update->compare('code', '=', $this->hashToken($this->cookie_token), ELGG_VALUE_STRING));
		
		try {
			// not interested in number of updated rows, as an update in the same second won't update the row
			$this->db->updateData($update);
			
			// also update the cookie lifetime client-side
			$this->setCookie($this->cookie_token);
			
			return true;
		} catch (DatabaseException $e) {
			$this->handleDbException($e);
		}
		
		return false;
	}
	
	/**
	 * Remove all persistent codes from the database which have expired based on the cookie config
	 *
	 * @param int $time the base timestamp to use
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
		
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('timestamp', '<', $time->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		try {
			return (bool) $this->db->deleteData($delete);
		} catch (DatabaseException $e) {
			$this->handleDbException($e);
		}
		
		return false;
	}

	/**
	 * Store a hash in the DB
	 *
	 * @param \ElggUser $user The user for whom we're storing the hash
	 * @param string    $hash The hashed token
	 *
	 * @return void
	 */
	protected function storeHash(\ElggUser $user, string $hash): void {
		// This prevents inserting the same hash twice, which seems to be happening in some rare cases
		// and for unknown reasons. See https://github.com/Elgg/Elgg/issues/8104
		$this->removeHash($hash);

		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'code' => $insert->param($hash, ELGG_VALUE_STRING),
			'guid' => $insert->param($user->guid, ELGG_VALUE_GUID),
			'timestamp' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		try {
			$this->db->insertData($insert);
		} catch (DatabaseException $e) {
			$this->handleDbException($e);
		}
	}

	/**
	 * Remove a hash from the DB
	 *
	 * @param string $hash The hashed token to remove (unused before 1.9)
	 *
	 * @return void
	 */
	protected function removeHash(string $hash): void {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('code', '=', $hash, ELGG_VALUE_STRING));
		
		try {
			$this->db->deleteData($delete);
		} catch (DatabaseException $e) {
			$this->handleDbException($e);
		}
	}

	/**
	 * Swallow a schema not upgraded exception, otherwise rethrow it
	 *
	 * @param DatabaseException $exception The exception to handle
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	protected function handleDbException(DatabaseException $exception): void {
		if (false !== strpos($exception->getMessage(), self::TABLE_NAME . "' doesn't exist")) {
			// schema has not been updated so we swallow this exception
			return;
		}
		
		throw $exception;
	}

	/**
	 * Remove all the hashes associated with a user
	 *
	 * @param \ElggUser $user The user for whom we're removing hashes
	 *
	 * @return void
	 */
	public function removeAllHashes(\ElggUser $user): void {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('guid', '=', $user->guid, ELGG_VALUE_GUID));
		
		try {
			$this->db->deleteData($delete);
		} catch (DatabaseException $e) {
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
