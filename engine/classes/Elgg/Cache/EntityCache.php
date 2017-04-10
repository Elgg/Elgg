<?php
namespace Elgg\Cache;

use ElggEntity;
use ElggSession;

/**
 * Volatile cache for entities
 *
 * @access private
 */
class EntityCache {

	// @todo Pick a less arbitrary limit
	const MAX_SIZE = 256;

	/**
	 * @var ElggEntity[] GUID keys
	 */
	private $entities = [];

	/**
	 * @var bool[] GUID keys
	 */
	private $disabled_guids = [];

	/**
	 * @var ElggSession
	 */
	private $session;

	/**
	 * @var MetadataCache
	 */
	private $metadata_cache;

	/**
	 * @var array
	 */
	private $username_cache = [];
	
	/**
	 * Constructor
	 *
	 * @param ElggSession   $session        Session
	 * @param MetadataCache $metadata_cache MD cache
	 */
	public function __construct(ElggSession $session, MetadataCache $metadata_cache) {
		$this->session = $session;
		$this->metadata_cache = $metadata_cache;
	}

	/**
	 * Retrieve a entity from the cache.
	 *
	 * @param int $guid The GUID
	 *
	 * @return \ElggEntity|false false if entity not cached
	 */
	public function get($guid) {
		$guid = (int) $guid;

		if (isset($this->entities[$guid])) {
			return $this->entities[$guid];
		}

		return false;
	}

	/**
	 * Returns cached user entity by username
	 *
	 * @param string $username Username
	 * @return \ElggUser|false
	 */
	public function getByUsername($username) {
		if (isset($this->username_cache[$username])) {
			return $this->get($this->username_cache[$username]);
		}
		return false;
	}

	/**
	 * Cache an entity.
	 *
	 * @param ElggEntity $entity Entity to cache
	 * @return void
	 */
	public function set(ElggEntity $entity) {
		$guid = $entity->guid;

		if (!$guid || isset($this->entities[$guid]) || isset($this->disabled_guids[$guid])) {
			// have it or not saved
			return;
		}

		// Don't cache non-plugin entities while access control is off, otherwise they could be
		// exposed to users who shouldn't see them when control is re-enabled.
		if (!($entity instanceof \ElggPlugin) && $this->session->getIgnoreAccess()) {
			return;
		}

		// Don't store too many or we'll have memory problems
		if (count($this->entities) > self::MAX_SIZE) {
			$this->remove(array_rand($this->entities));
		}

		$this->entities[$guid] = $entity;

		if ($entity instanceof \ElggUser) {
			$this->username_cache[$entity->username] = $entity->guid;
		}
	}

	/**
	 * Invalidate this class's entry in the cache.
	 *
	 * @param int $guid The entity guid
	 * @return void
	 */
	public function remove($guid) {
		$guid = (int) $guid;
		
		if (!isset($this->entities[$guid])) {
			return;
		}

		unset($this->entities[$guid]);

		$username = array_search($guid, $this->username_cache);
		if ($username !== false) {
			unset($this->username_cache[$username]);
		}

		// Purge separate metadata cache. Original idea was to do in entity destructor, but that would
		// have caused a bunch of unnecessary purges at every shutdown. Doing it this way we have no way
		// to know that the expunged entity will be GCed (might be another reference living), but that's
		// OK; the metadata will reload if necessary.
		$this->metadata_cache->clear($guid);
	}

	/**
	 * Clear the entity cache
	 *
	 * @return void
	 */
	public function clear() {
		$this->entities = [];
		$this->username_cache = [];
	}

	/**
	 * Remove this entity from the entity cache and make sure it is not re-added
	 *
	 * @todo this is a workaround until #5604 can be implemented
	 *
	 * @param int $guid The entity guid
	 * @return void
	 */
	public function disableCachingForEntity($guid) {
		$this->remove($guid);
		$this->disabled_guids[$guid] = true;
	}

	/**
	 * Allow this entity to be stored in the entity cache
	 *
	 * @param int $guid The entity guid
	 * @return void
	 */
	public function enableCachingForEntity($guid) {
		unset($this->disabled_guids[$guid]);
	}
}
