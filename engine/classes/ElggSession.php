<?php

/**
 * Elgg Session Management
 *
 * Reserved keys: last_forward_from, msg, sticky_forms, user, guid, id, code, name, username
 * Deprecated keys: user, id, name, username
 * 
 * \ArrayAccess was deprecated in Elgg 1.9. This means you should use 
 * $session->get('foo') rather than $session['foo'].
 * Warning: You can not access multidimensional arrays through \ArrayAccess like
 * this $session['foo']['bar']
 *
 * @package    Elgg.Core
 * @subpackage Session
 * @see        elgg_get_session()
 */
class ElggSession implements \ArrayAccess {

	/** @var \Elgg\Http\SessionStorage */
	protected $storage;

	/** @var \ElggUser */
	protected $loggedInUser;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Http\SessionStorage $storage The storage engine
	 * @access private Use elgg_get_session()
	 */
	public function __construct(\Elgg\Http\SessionStorage $storage) {
		$this->storage = $storage;
		$this->loggedInUser = null;
	}

	/**
	 * Start the session
	 *
	 * @return boolean
	 * @throws RuntimeException If session fails to start.
	 * @since 1.9
	 */
	public function start() {
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
		return $this->storage->regenerate($destroy);
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
		$this->loggedInUser = null;
		$result = $this->migrate(true);
		$this->generateSessionToken();
		return $result;
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
	public function getId() {
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
	 * Alias to offsetUnset()
	 *
	 * @param string $key Name
	 * @return void
	 * @deprecated 1.9 Use remove()
	 */
	public function del($key) {
		elgg_deprecated_notice(__METHOD__ . " has been deprecated.", 1.9);
		$this->remove($key);
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
	 * Sets the logged in user
	 * 
	 * @param \ElggUser $user The user who is logged in
	 * @return void
	 * @since 1.9
	 */
	public function setLoggedInUser(\ElggUser $user) {
		$this->set('guid', $user->guid);
		$this->loggedInUser = $user;
	}

	/**
	 * Gets the logged in user
	 * 
	 * @return \ElggUser
	 * @since 1.9
	 */
	public function getLoggedInUser() {
		return $this->loggedInUser;
	}

	/**
	 * Return the current logged in user by guid.
	 *
	 * @see elgg_get_logged_in_user_entity()
	 * @return int
	 */
	public function getLoggedInUserGuid() {
		$user = $this->getLoggedInUser();
		if ($user) {
			return $user->guid;
		}
	
		return 0;
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
		return (bool)$this->getLoggedInUser();
	}

	/**
	 * Remove the logged in user
	 * 
	 * @return void
	 * @since 1.9
	 */
	public function removeLoggedInUser() {
		$this->loggedInUser = null;
		$this->remove('guid');
	}

	/**
	 * Adds a token to the session
	 * 
	 * This is used in creation of CSRF token
	 * 
	 * @return void
	 */
	protected function generateSessionToken() {
		// Generate a simple token that we store server side
		if (!$this->has('__elgg_session')) {
			$this->set('__elgg_session', md5(microtime() . rand()));
		}
	}

	/**
	 * Test if property is set either as an attribute or metadata.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 * @deprecated 1.9 Use has()
	 */
	public function __isset($key) {
		elgg_deprecated_notice(__METHOD__ . " has been deprecated.", 1.9);
		// Note: We use offsetExists() for BC
		return $this->offsetExists($key);
	}

	/**
	 * Set a value, go straight to session.
	 *
	 * @param string $key   Name
	 * @param mixed  $value Value
	 *
	 * @return void
	 * @deprecated 1.9 Use set()
	 */
	public function offsetSet($key, $value) {
		elgg_deprecated_notice(__METHOD__ . " has been deprecated.", 1.9);
		$this->set($key, $value);
	}

	/**
	 * Get a variable from either the session, or if its not in the session
	 * attempt to get it from an api call.
	 *
	 * @see \ArrayAccess::offsetGet()
	 *
	 * @param mixed $key Name
	 *
	 * @return mixed
	 * @deprecated 1.9 Use get()
	 */
	public function offsetGet($key) {
		elgg_deprecated_notice(__METHOD__ . " has been deprecated.", 1.9);

		if (in_array($key, array('user', 'id', 'name', 'username'))) {
			elgg_deprecated_notice("Only 'guid' is stored in session for user now", 1.9);
			if ($this->loggedInUser) {
				switch ($key) {
					case 'user':
						return $this->loggedInUser;
						break;
					case 'id':
						return $this->loggedInUser->guid;
						break;
					case 'name':
					case 'username':
						return $this->loggedInUser->$key;
						break;
				}
			} else {
				return null;
			}
		}

		if ($this->has($key)) {
			return $this->get($key);
		}

		$orig_value = null;
		$value = _elgg_services()->hooks->trigger('session:get', $key, null, $orig_value);
		if ($orig_value !== $value) {
			elgg_deprecated_notice("Plugin hook session:get has been deprecated.", 1.9);
		}

		$this->set($key, $value);
		return $value;
	}

	/**
	 * Unset a value from the cache and the session.
	 *
	 * @see \ArrayAccess::offsetUnset()
	 *
	 * @param mixed $key Name
	 *
	 * @return void
	 * @deprecated 1.9 Use remove()
	 */
	public function offsetUnset($key) {
		elgg_deprecated_notice(__METHOD__ . " has been deprecated.", 1.9);
		$this->remove($key);
	}

	/**
	 * Return whether the value is set in either the session or the cache.
	 *
	 * @see \ArrayAccess::offsetExists()
	 *
	 * @param int $offset Offset
	 *
	 * @return bool
	 * @deprecated 1.9 Use has()
	 */
	public function offsetExists($offset) {
		elgg_deprecated_notice(__METHOD__ . " has been deprecated.", 1.9);

		if (in_array($offset, array('user', 'id', 'name', 'username'))) {
			elgg_deprecated_notice("Only 'guid' is stored in session for user now", 1.9);
			return (bool)$this->loggedInUser;
		}

		if ($this->has($offset)) {
			return true;
		}

		// Note: We use offsetGet() for BC
		if ($this->offsetGet($offset)) {
			return true;
		}

		return false;
	}
}
