<?php
/**
 * Shared memory cache description.
 * Extends ElggCache with functions useful to shared memory
 * style caches (static variables, memcache etc)
 *
 * @package    Elgg.Core
 * @subpackage Cache
 */
abstract class ElggSharedMemoryCache extends ElggCache {
	/**
	 * Namespace variable used to keep various bits of the cache
	 * separate.
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * Set the namespace of this cache.
	 * This is useful for cache types (like memcache or static variables) where there is one large
	 * flat area of memory shared across all instances of the cache.
	 *
	 * @param string $namespace Namespace for cache
	 *
	 * @return void
	 */
	public function setNamespace($namespace = "default") {
		$this->namespace = $namespace;
	}

	/**
	 * Get the namespace currently defined.
	 *
	 * @return string
	 */
	public function getNamespace() {
		return $this->namespace;
	}
}
