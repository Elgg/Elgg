<?php

/**
 * Use or create collections
 *
 * @see ElggCollection
 */
class ElggCollectionManager {

	/**
	 * @var array cached references to collections
	 */
	protected $instances = array();

	/**
	 * Get a reference to a collection if it exists
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return ElggCollection|null
	 */
	public function fetch(ElggEntity $entity, $name) {
		if (!$name || !$entity->guid) {
			return null;
		}
		// common case
		if (ElggCollection::canSeeExistenceMetadata($entity, $name)) {
			return $this->factory($entity, $name, true);
		}
		if (!$entity->canEdit()) {
			return null;
		}
		// This allows us to support hidden/differently owned metadata, but make sure anyone who can
		// edit the entity can always access/edit the collection. (the metadata is just an implementation
		// detail)
		if ($this->exists($entity, $name)) {
			return $this->factory($entity, $name, true);
		}
		return null;
	}

	/**
	 * Does the collection exist? This does not imply the current user can access it.
	 *
	 * @param ElggEntity|int $entity entity or GUID
	 * @param $name
	 * @return bool
	 */
	public function exists($entity, $name) {
		$ia = elgg_set_ignore_access(true);
		if (!($entity instanceof ElggEntity)) {
			$entity = get_entity($entity);
		}
		$exists = ($entity && ElggCollection::canSeeExistenceMetadata($entity, $name));
		elgg_set_ignore_access($ia);
		return $exists;
	}

	/**
	 * Create (or fetch an existing) named collection on an entity. Good for creating a collection
	 * on demand for editing.
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return ElggCollection|bool false if user is not permitted to create
	 */
	public function create(ElggEntity $entity, $name) {
		// check GUID, entity may not be saved
		if ($entity->guid && $entity->canEdit()) {
			$coll = $this->fetch($entity, $name);
			if (!$coll) {
				$coll = $this->factory($entity, $name, false);
			}
			return $coll;
		}
		return false;
	}

	/**
	 * Makes sure only one instance is handed out of each possible collection
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @param bool $has_metadata
	 * @return ElggCollection
	 *
	 * @access private
	 */
	protected function factory(ElggEntity $entity, $name, $has_metadata = false) {
		$key = $entity->guid . '|' . $name;
		if (!isset($this->instances[$key])) {
			$this->instances[$key] = new ElggCollection($entity, $name, $has_metadata);
		}
		return $this->instances[$key];
	}
}
