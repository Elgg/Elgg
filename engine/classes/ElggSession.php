<?php

use Elgg\Config;
use Elgg\Database;
use Elgg\Exceptions\LoginException;
use Elgg\Exceptions\SecurityException;
use Elgg\Http\DatabaseSessionHandler;
use Elgg\SystemMessagesService;
use Elgg\Traits\Debug\Profilable;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Elgg Session Management
 *
 * Reserved keys: last_forward_from, msg, sticky_forms, user, guid, id, code, name, username
 *
 * @see elgg_get_session()
 */
class ElggSession {
	
	use Profilable;

	/**
	 * @var SessionInterface
	 */
	protected $storage;

	/**
	 * @var \ElggUser|null
	 */
	protected $logged_in_user;

	/**
	 * @var bool
	 */
	protected $ignore_access = false;

	/**
	 * @var bool
	 */
	protected $show_disabled_entities = false;

	/**
	 * Constructor
	 *
	 * @param SessionInterface $storage The underlying Session implementation
	 */
	public function __construct(SessionInterface $storage) {
		$this->storage = $storage;
	}
	
	/**
	 * Initializes the session and checks for the remember me cookie
	 *
	 * @return void
	 *
	 * @internal
	 */
	public function boot(): void {
	
		$this->beginTimer([__METHOD__]);
	
		$this->start();
	
		// test whether we have a user session
		if ($this->has('guid')) {
			$user = _elgg_services()->entityTable->get($this->get('guid'), 'user');
			if (!$user instanceof ElggUser) {
				// OMG user has been deleted.
				$this->invalidate();
				
				// redirect to homepage
				$this->endTimer([__METHOD__]);
				_elgg_services()->responseFactory->redirect('');
			}
		} else {
			$user = _elgg_services()->persistentLogin->bootSession();
			if ($user instanceof ElggUser) {
				_elgg_services()->persistentLogin->updateTokenUsage($user);
			}
		}
	
		if ($user instanceof ElggUser) {
			$this->setLoggedInUser($user);
			$user->setLastAction();
	
			// logout a user with open session who has been banned
			if ($user->isBanned()) {
				$this->logout();
			}
		}
	
		$this->endTimer([__METHOD__]);
	}

	/**
	 * Start the session
	 *
	 * @return boolean
	 * @throws RuntimeException If session fails to start.
	 * @since 1.9
	 */
	public function start() {
		
		if ($this->storage->getId()) {
			return true;
		}

		$result = $this->storage->start();
		$this->generateSessionToken();
		return $result;
	}

	/**
	 * Migrates the session to a new session id while maintaining session attributes
	 *
	 * @param boolean $destroy Whether to delete the session or let gc handle clean up
	 * @return boolean
	 * @since 1.9
	 */
	public function migrate($destroy = false) {
		return $this->storage->migrate($destroy);
	}

	/**
	 * Invalidates the session
	 *
	 * Deletes session data and session persistence. Starts a new session.
	 *
	 * @return boolean
	 * @since 1.9
	 */
	public function invalidate() {
		$this->storage->clear();
		$this->logged_in_user = null;
		$result = $this->migrate(true);
		$this->generateSessionToken();
		_elgg_services()->sessionCache->clear();
		return $result;
	}

	/**
	 * Save the session data and closes the session
	 *
	 * @return void
	 * @since 3.0
	 */
	public function save() {
		$this->storage->save();
	}

	/**
	 * Has the session been started
	 *
	 * @return boolean
	 * @since 1.9
	 */
	public function isStarted() {
		return $this->storage->isStarted();
	}

	/**
	 * Get the session ID
	 *
	 * @return string
	 * @since 1.9
	 */
	public function getID() {
		return $this->storage->getId();
	}

	/**
	 * Set the session ID
	 *
	 * @param string $id Session ID
	 * @return void
	 * @since 1.9
	 */
	public function setId($id) {
		$this->storage->setId($id);
	}

	/**
	 * Get the session name
	 *
	 * @return string
	 * @since 1.9
	 */
	public function getName() {
		return $this->storage->getName();
	}

	/**
	 * Set the session name
	 *
	 * @param string $name Session name
	 * @return void
	 * @since 1.9
	 */
	public function setName($name) {
		$this->storage->setName($name);
	}

	/**
	 * Get an attribute of the session
	 *
	 * @param string $name    Name of the attribute to get
	 * @param mixed  $default Value to return if attribute is not set (default is null)
	 * @return mixed
	 */
	public function get($name, $default = null) {
		return $this->storage->get($name, $default);
	}

	/**
	 * Set an attribute
	 *
	 * @param string $name  Name of the attribute to set
	 * @param mixed  $value Value to be set
	 * @return void
	 */
	public function set($name, $value) {
		$this->storage->set($name, $value);
	}

	/**
	 * Remove an attribute
	 *
	 * @param string $name The name of the attribute to remove
	 * @return mixed The removed attribute
	 * @since 1.9
	 */
	public function remove($name) {
		return $this->storage->remove($name);
	}

	/**
	 * Has the attribute been defined
	 *
	 * @param string $name Name of the attribute
	 * @return bool
	 * @since 1.9
	 */
	public function has($name) {
		return $this->storage->has($name);
	}
	
	/**
	 * Log in a user
	 *
	 * @param \ElggUser $user       A valid Elgg user object
	 * @param boolean   $persistent Should this be a persistent login?
	 *
	 * @return void
	 * @throws LoginException
	 * @since 4.3
	 */
	public function login(\ElggUser $user, bool $persistent = false): void {
		if ($user->isBanned()) {
			throw new LoginException(elgg_echo('LoginException:BannedUser'));
		}
	
		// give plugins a chance to reject the login of this user (no user in session!)
		if (!elgg_trigger_before_event('login', 'user', $user)) {
			throw new LoginException(elgg_echo('LoginException:Unknown'));
		}
		
		if (!$user->isEnabled()) {
			// fallback if no plugin provided a reason
			throw new LoginException(elgg_echo('LoginException:DisabledUser'));
		}
		
		// #5933: set logged in user early so code in login event will be able to
		// use elgg_get_logged_in_user_entity().
		$this->setLoggedInUser($user);
		$this->setUserToken($user);
	
		// re-register at least the core language file for users with language other than site default
		_elgg_services()->translator->registerTranslations(\Elgg\Project\Paths::elgg() . 'languages/');
	
		// if remember me checked, set cookie with token and store hash(token) for user
		if ($persistent) {
			_elgg_services()->persistentLogin->makeLoginPersistent($user);
		}
	
		// User's privilege has been elevated, so change the session id (prevents session fixation)
		$this->migrate();
	
		// check before updating last login to determine first login
		$first_login = empty($user->last_login);
		
		$user->setLastLogin();
		elgg_reset_authentication_failures($user);
	
		elgg_trigger_after_event('login', 'user', $user);
		
		if ($first_login) {
			elgg_trigger_event('login:first', 'user', $user);
			$user->first_login = time();
		}
	}
	
	/**
	 * Log the current user out
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function logout(): bool {
		$user = $this->getLoggedInUser();
		if (!$user) {
			return false;
		}
	
		if (!elgg_trigger_before_event('logout', 'user', $user)) {
			return false;
		}
	
		_elgg_services()->persistentLogin->removePersistentLogin();
	
		// pass along any messages into new session
		$old_msg = $this->get(SystemMessagesService::SESSION_KEY, []);
		$this->invalidate();
		$this->set(SystemMessagesService::SESSION_KEY, $old_msg);
	
		elgg_trigger_after_event('logout', 'user', $user);
	
		return true;
	}

	/**
	 * Sets the logged in user
	 *
	 * @param \ElggUser $user The user who is logged in
	 * @return void
	 * @since 1.9
	 */
	public function setLoggedInUser(\ElggUser $user) {
		$current_user = $this->getLoggedInUser();
		if ($current_user != $user) {
			$this->set('guid', $user->guid);
			$this->logged_in_user = $user;
			_elgg_services()->sessionCache->clear();
			_elgg_services()->entityCache->save($user);
			_elgg_services()->translator->setCurrentLanguage($user->language);
		}
	}

	/**
	 * Gets the logged in user
	 *
	 * @return \ElggUser|null
	 * @since 1.9
	 */
	public function getLoggedInUser() {
		return $this->logged_in_user;
	}

	/**
	 * Return the current logged in user by guid.
	 *
	 * @see elgg_get_logged_in_user_entity()
	 * @return int
	 */
	public function getLoggedInUserGuid() {
		$user = $this->getLoggedInUser();
		return $user ? $user->guid : 0;
	}
	
	/**
	 * Returns whether or not the viewer is currently logged in and an admin user.
	 *
	 * @return bool
	 */
	public function isAdminLoggedIn() {
		$user = $this->getLoggedInUser();
	
		return $user && $user->isAdmin();
	}
	
	/**
	 * Returns whether or not the user is currently logged in
	 *
	 * @return bool
	 */
	public function isLoggedIn() {
		return (bool) $this->getLoggedInUser();
	}

	/**
	 * Remove the logged in user
	 *
	 * @return void
	 * @since 1.9
	 */
	public function removeLoggedInUser() {
		$this->logged_in_user = null;
		$this->remove('guid');
		_elgg_services()->sessionCache->clear();
	}
	
	/**
	 * Set a user specific token in the session for the currently logged in user
	 *
	 * This will invalidate the session on a password change of the logged in user
	 *
	 * @param \ElggUser $user the user to set the token for (default: logged in user)
	 *
	 * @return void
	 * @since 3.3.25
	 */
	public function setUserToken(\ElggUser $user = null): void {
		if (!$user instanceof \ElggUser) {
			$user = $this->getLoggedInUser();
		}
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$this->set('__user_token', $this->generateUserToken($user));
	}
	
	/**
	 * Validate the user token stored in the session
	 *
	 * @param \ElggUser $user the user to check for
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\SecurityException
	 * @since 3.3.25
	 */
	public function validateUserToken(\ElggUser $user): void {
		$session_token = $this->get('__user_token');
		$user_token = $this->generateUserToken($user);
		
		if ($session_token !== $user_token) {
			throw new SecurityException(elgg_echo('session_expired'));
		}
	}
	
	/**
	 * Generate a token for a specific user
	 *
	 * @param \ElggUser $user the user to generate the token for
	 *
	 * @return string
	 * @since 3.3.25
	 */
	protected function generateUserToken(\ElggUser $user): string {
		$hmac = _elgg_services()->hmac->getHmac([
			$user->time_created,
			$user->guid,
		], 'sha256', $user->password_hash);
		
		return $hmac->getToken();
	}

	/**
	 * Get current ignore access setting.
	 *
	 * @return bool
	 */
	public function getIgnoreAccess() {
		return $this->ignore_access;
	}

	/**
	 * Set ignore access.
	 *
	 * @param bool $ignore Ignore access
	 *
	 * @return bool Previous setting
	 */
	public function setIgnoreAccess($ignore = true) {
		$prev = $this->ignore_access;
		$this->ignore_access = $ignore;

		return $prev;
	}

	/**
	 * Are disabled entities shown?
	 *
	 * @return bool
	 */
	public function getDisabledEntityVisibility() {
		return $this->show_disabled_entities;
	}

	/**
	 * Include disabled entities in queries
	 *
	 * @param bool $show Visibility status
	 *
	 * @return bool Previous setting
	 */
	public function setDisabledEntityVisibility($show = true) {
		$prev = $this->show_disabled_entities;
		$this->show_disabled_entities = $show;

		return $prev;
	}

	/**
	 * Adds a token to the session
	 *
	 * This is used in creation of CSRF token, and is passed to the client to allow validating tokens
	 * later, even if the PHP session was destroyed.
	 *
	 * @return void
	 */
	protected function generateSessionToken() {
		// Generate a simple token that we store server side
		if (!$this->has('__elgg_session')) {
			$this->set('__elgg_session', _elgg_services()->crypto->getRandomString(22));
		}
	}

	/**
	 * Get an isolated ElggSession that does not persist between requests
	 *
	 * @return self
	 *
	 * @internal
	 */
	public static function getMock() {
		$storage = new MockArraySessionStorage();
		$session = new Session($storage);
		return new self($session);
	}

	/**
	 * Create a session stored in the DB.
	 *
	 * @param Config   $config Config
	 * @param Database $db     Database
	 *
	 * @return ElggSession
	 *
	 * @internal
	 */
	public static function fromDatabase(Config $config, Database $db) {
		$params = $config->getCookieConfig()['session'];
		$options = [
			// session.cache_limiter is unfortunately set to "" by the NativeSessionStorage
			// constructor, so we must capture and inject it directly.
			'cache_limiter' => session_cache_limiter(),

			'name' => $params['name'],
			'cookie_path' => $params['path'],
			'cookie_domain' => $params['domain'],
			'cookie_secure' => $params['secure'],
			'cookie_httponly' => $params['httponly'],
			'cookie_lifetime' => $params['lifetime'],
		];

		$handler = new DatabaseSessionHandler($db);
		$storage = new NativeSessionStorage($options, $handler);
		$session = new Session($storage);
		return new self($session);
	}

	/**
	 * Create a session stored in files
	 *
	 * @param Config $config Config
	 *
	 * @return ElggSession
	 *
	 * @internal
	 */
	public static function fromFiles(Config $config) {
		$params = $config->getCookieConfig()['session'];
		$options = [
			// session.cache_limiter is unfortunately set to "" by the NativeSessionStorage
			// constructor, so we must capture and inject it directly.
			'cache_limiter' => session_cache_limiter(),

			'name' => $params['name'],
			'cookie_path' => $params['path'],
			'cookie_domain' => $params['domain'],
			'cookie_secure' => $params['secure'],
			'cookie_httponly' => $params['httponly'],
			'cookie_lifetime' => $params['lifetime'],
		];

		$storage = new NativeSessionStorage($options);
		$session = new Session($storage);
		return new self($session);
	}
}
