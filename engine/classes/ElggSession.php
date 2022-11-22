<?php

use Elgg\Config;
use Elgg\Database;
use Elgg\Database\SessionHandler as ElggSessionHandler;
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
			_elgg_services()->session_manager->setLoggedInUser($user);
			$user->setLastAction();
	
			// logout a user with open session who has been banned
			if ($user->isBanned()) {
				_elgg_services()->session_manager->logout();
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

		$handler = new ElggSessionHandler($db);
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
