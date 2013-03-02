<?php

/**
 * Magic session class.
 * This class is intended to extend the $_SESSION magic variable by providing an API hook
 * to plug in other values.
 *
 * Primarily this is intended to provide a way of supplying "logged in user"
 * details without touching the session (which can cause problems when
 * accessed server side).
 *
 * If a value is present in the session then that value is returned, otherwise
 * a plugin hook 'session:get', '$var' is called, where $var is the variable
 * being requested.
 *
 * Setting values will store variables in the session in the normal way.
 *
 * LIMITATIONS: You can not access multidimensional arrays
 *
 * @package    Elgg.Core
 * @subpackage Session
 */
class ElggSession implements ArrayAccess {

	/** Local cache of trigger retrieved variables */
	private static $__localcache;

	/** @var Elgg_Http_SessionStorage */
	protected $storage;

	/**
	 * Constructor
	 *
	 * @param Elgg_Http_SessionStorage $storage The storage engine
	 */
	public function __construct(Elgg_Http_SessionStorage $storage = null) {
		$this->storage = $storage ? $storage : new Elgg_Http_NativeSessionStorage();
	}

	/**
	 * Start the session
	 *
	 * @return boolean
	 * @throws RuntimeException If session fails to start.
	 */
	public function start() {
		return $this->storage->start();
	}

	/**
	 * Migrates the session to a new session id while maintaining session attributes
	 *
	 * @param boolean $destroy Whether to delete the session or let gc handle clean up
	 * @return boolean
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
	 */
	public function invalidate() {
		$this->storage->clear();
		return $this->migrate(true);
	}

	/**
	 * Has the session been started
	 *
	 * @return boolean
	 */
	public function isStarted() {
		return $this->storage->isStarted();
	}

	/**
	 * Get the session ID
	 *
	 * @return string
	 */
	public function getId() {
		return $this->storage->getId();
	}

	/**
	 * Set the session ID
	 *
	 * @param string $id Session ID
	 */
	public function setId($id) {
		$this->storage->setId($id);
	}

	/**
	 * Get the session name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->storage->getName();
	}

	/**
	 * Set the session name
	 *
	 * @param $name Session name
	 */
	public function setName($name) {
		$this->storage->setName($name);
	}

	/**
	 * Test if property is set either as an attribute or metadata.
	 *
	 * @param string $key The name of the attribute or metadata.
	 *
	 * @return bool
	 */
	public function __isset($key) {
		return $this->offsetExists($key);
	}

	/**
	 * Set a value, go straight to session.
	 *
	 * @param string $key   Name
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	public function offsetSet($key, $value) {
		$_SESSION[$key] = $value;
	}

	/**
	 * Get a variable from either the session, or if its not in the session
	 * attempt to get it from an api call.
	 *
	 * @see ArrayAccess::offsetGet()
	 *
	 * @param mixed $key Name
	 *
	 * @return mixed
	 */
	public function offsetGet($key) {
		if (!ElggSession::$__localcache) {
			ElggSession::$__localcache = array();
		}

		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		if (isset(ElggSession::$__localcache[$key])) {
			return ElggSession::$__localcache[$key];
		}

		$value = NULL;
		$value = elgg_trigger_plugin_hook('session:get', $key, NULL, $value);

		ElggSession::$__localcache[$key] = $value;

		return ElggSession::$__localcache[$key];
	}

	/**
	 * Unset a value from the cache and the session.
	 *
	 * @see ArrayAccess::offsetUnset()
	 *
	 * @param mixed $key Name
	 *
	 * @return void
	 */
	public function offsetUnset($key) {
		unset(ElggSession::$__localcache[$key]);
		unset($_SESSION[$key]);
	}

	/**
	 * Return whether the value is set in either the session or the cache.
	 *
	 * @see ArrayAccess::offsetExists()
	 *
	 * @param int $offset Offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset) {
		if (isset(ElggSession::$__localcache[$offset])) {
			return true;
		}

		if (isset($_SESSION[$offset])) {
			return true;
		}

		if ($this->offsetGet($offset)) {
			return true;
		}

		return false;
	}

	/**
	 * Get an attribute of the session
	 *
	 * @param string $name    Name of the attribute to get
	 * @param mixed  $default Value to return if attribute is not set (default is null)
	 * @return mixed
	 */
	public function get($name, $default = null) {
		$value = $this->offsetGet($name);
		if ($value === null) {
			return $default;
		} else {
			return $value;
		}
	}

	/**
	 * Set an attribute
	 *
	 * @param string $name  Name of the attribute to set
	 * @param mixed  $value Value to be set
	 *
	 * @return void
	 */
	public function set($name, $value) {
		$this->offsetSet($name, $value);
	}

	/**
	 * Remove an attribute
	 *
	 * @param string $name The name of the attribute to remove
	 *
	 * @return mixed The removed attribute
	 * @since 1.9
	 */
	public function remove($name) {
		$value = $this->get($name);
		$this->offsetUnset($name);
		return $value;
	}

	/**
	 * Alias to offsetUnset()
	 *
	 * @param string $key Name
	 *
	 * @return void
	 * @deprecated 1.9 Use remove()
	 */
	public function del($key) {
		elgg_deprecated_notice("ElggSession::del has been deprecated.", 1.9);
		$this->remove($key);
	}

	/**
	 * Has the attribute been defined
	 *
	 * @param string $name Name of the attribute
	 *
	 * @return bool
	 * @since 1.9
	 */
	public function has($name) {
		return $this->offsetExists($name);
	}

}
