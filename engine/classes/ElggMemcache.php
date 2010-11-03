<?php
/**
 * Memcache wrapper class.
 *
 * @package    Elgg.Core
 * @subpackage Memcache
 */
class ElggMemcache extends ElggSharedMemoryCache {
	/**
	 * Minimum version of memcached needed to run
	 *
	 */
	private static $MINSERVERVERSION = '1.1.12';

	/**
	 * Memcache object
	 */
	private $memcache;

	/**
	 * Expiry of saved items (default timeout after a day to prevent anything getting too stale)
	 */
	private $expires = 86400;

	/**
	 * The version of memcache running
	 */
	private $version = 0;

	/**
	 * Connect to memcache.
	 *
	 * @param string $namespace The namespace for this cache to write to -
	 * note, namespaces of the same name are shared!
	 */
	function __construct($namespace = 'default') {
		global $CONFIG;

		$this->setNamespace($namespace);

		// Do we have memcache?
		if (!class_exists('Memcache')) {
			throw new ConfigurationException(elgg_echo('memcache:notinstalled'));
		}

		// Create memcache object
		$this->memcache	= new Memcache;

		// Now add servers
		if (!$CONFIG->memcache_servers) {
			throw new ConfigurationException(elgg_echo('memcache:noservers'));
		}

		if (is_callable(array($this->memcache, 'addServer'))) {
			foreach ($CONFIG->memcache_servers as $server) {
				if (is_array($server)) {
					$this->memcache->addServer(
						$server[0],
						isset($server[1]) ? $server[1] : 11211,
						isset($server[2]) ? $server[2] : FALSE,
						isset($server[3]) ? $server[3] : 1,
						isset($server[4]) ? $server[4] : 1,
						isset($server[5]) ? $server[5] : 15,
						isset($server[6]) ? $server[6] : TRUE
					);

				} else {
					$this->memcache->addServer($server, 11211);
				}
			}
		} else {
			// don't use elgg_echo() here because most of the config hasn't been loaded yet
			// and it caches the language, which is hard coded in $CONFIG->language as en.
			// overriding it with real values later has no effect because it's already cached.
			elgg_log("This version of the PHP memcache API doesn't support multiple servers.", 'ERROR');

			$server = $CONFIG->memcache_servers[0];
			if (is_array($server)) {
				$this->memcache->connect($server[0], $server[1]);
			} else {
				$this->memcache->addServer($server, 11211);
			}
		}

		// Get version
		$this->version = $this->memcache->getVersion();
		if (version_compare($this->version, ElggMemcache::$MINSERVERVERSION, '<')) {
			$msg = elgg_echo('memcache:versiontoolow',
				array(ElggMemcache::$MINSERVERVERSION,
				$this->version
			));

			throw new ConfigurationException($msg);
		}

		// Set some defaults
		if (isset($CONFIG->memcache_expires)) {
			$this->expires = $CONFIG->memcache_expires;
		}
	}

	/**
	 * Set the default expiry.
	 *
	 * @param int $expires The lifetime as a unix timestamp or time from now. Defaults forever.
	 *
	 * @return void
	 */
	public function setDefaultExpiry($expires = 0) {
		$this->expires = $expires;
	}

	/**
	 * Combine a key with the namespace.
	 * Memcache can only accept <250 char key. If the given key is too long it is shortened.
	 *
	 * @deprecated 1.8 Use ElggMemcache::_makeMemcacheKey()
	 *
	 * @param string $key The key
	 *
	 * @return string The new key.
	 */
	private function make_memcache_key($key) {
		elgg_deprecated_notice('ElggMemcache::make_memcache_key() is deprecated by ::_makeMemcacheKey()', 1.8);

		return $this->_makeMemcacheKey($key);
	}

	/**
	 * Combine a key with the namespace.
	 * Memcache can only accept <250 char key. If the given key is too long it is shortened.
	 *
	 * @param string $key The key
	 *
	 * @return string The new key.
	 */
	private function _makeMemcacheKey($key) {
		$prefix = $this->getNamespace() . ":";

		if (strlen($prefix . $key) > 250) {
			$key = md5($key);
		}

		return $prefix . $key;
	}

	/**
	 * Saves a name and value to the cache
	 *
	 * @param string $key  Name
	 * @param string $data Value
	 *
	 * @return bool
	 */
	public function save($key, $data) {
		$key = $this->_makeMemcacheKey($key);

		$result = $this->memcache->set($key, $data, null, $this->expires);
		if (!$result) {
			elgg_log("MEMCACHE: FAILED TO SAVE $key", 'ERROR');
		}

		return $result;
	}

	/**
	 * Retrieves data.
	 *
	 * @param string $key    Name of data to retrieve
	 * @param int    $offset Offset
	 * @param int    $limit  Limit
	 *
	 * @return mixed
	 */
	public function load($key, $offset = 0, $limit = null) {
		$key = $this->_makeMemcacheKey($key);

		$result = $this->memcache->get($key);
		if (!$result) {
			elgg_log("MEMCACHE: FAILED TO LOAD $key", 'ERROR');
		}

		return $result;
	}

	/**
	 * Delete data
	 *
	 * @param string $key Name of data
	 *
	 * @return bool
	 */
	public function delete($key) {
		$key = $this->_makeMemcacheKey($key);

		return $this->memcache->delete($key, 0);
	}

	/**
	 * Clears the entire cache?
	 *
	 * @todo write or remove.
	 *
	 * @return true
	 */
	public function clear() {
		// DISABLE clearing for now - you must use delete on a specific key.
		return true;

		// @todo Namespaces as in #532
	}
}
