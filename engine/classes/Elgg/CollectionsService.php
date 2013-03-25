<?php

/**
 * Use or create collections
 *
 * @see Elgg_Collection
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Collections
 */
class Elgg_CollectionsService {

	/**
	 * @var array cached references to collections
	 */
	protected $instances = array();

	/**
	 * Get a reference to a collection if it exists, and the current user can see (or can edit it)
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return Elgg_Collection|null
	 * @throws InvalidArgumentException
	 */
	public function fetch(ElggEntity $entity, $name) {
		if (!$name) {
			throw new InvalidArgumentException('$name must not be empty');
		}
		if (!$entity->guid) {
			throw new InvalidArgumentException('$entity must have a GUID (have been saved)');
		}
		// common case
		if (Elgg_Collection::canSeeExistenceMetadata($entity, $name)) {
			return $this->factory($entity, $name, true);
		}
		// This allows us to support hidden/differently owned metadata, but make sure anyone who can
		// edit the entity can always access/edit the collection. (the metadata is just an implementation
		// detail)
		if ($entity->canEdit() && $this->exists($entity, $name)) {
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
	 * @throws InvalidArgumentException
	 */
	public function exists($entity, $name) {
		if (!$name) {
			throw new InvalidArgumentException('$name must not be empty');
		}
		if (!$entity->guid) {
			throw new InvalidArgumentException('$entity must have a GUID (have been saved)');
		}
		$ia = elgg_set_ignore_access(true);
		if (!($entity instanceof ElggEntity)) {
			$entity = get_entity($entity);
		}
		$exists = ($entity && Elgg_Collection::canSeeExistenceMetadata($entity, $name));
		elgg_set_ignore_access($ia);
		return $exists;
	}

	/**
	 * Create (or fetch an existing) named collection on an entity. Good for creating a collection
	 * on demand for editing.
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return Elgg_Collection|null null if user is not permitted to create
	 */
	public function create(ElggEntity $entity, $name) {
		$coll = $this->fetch($entity, $name);
		if (!$coll && $entity->canEdit()) {
			$coll = $this->factory($entity, $name, false);
		}
		return $coll;
	}

	/**
	 * Makes sure only one instance is handed out of each possible collection
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @param bool $has_metadata
	 * @return Elgg_Collection
	 *
	 * @access private
	 */
	protected function factory(ElggEntity $entity, $name, $has_metadata = false) {
		$key = $entity->guid . '|' . $name;
		if (!isset($this->instances[$key]) || $this->instances[$key]->isDeleted()) {
			$this->instances[$key] = new Elgg_Collection($entity, $name, $has_metadata);
		}
		return $this->instances[$key];
	}

	/**
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return bool
	 */
	public function delete(ElggEntity $entity, $name) {
		$coll = $this->create($entity, $name);
		if ($coll) {
			$coll->delete();
			$key = $entity->guid . '|' . $name;
			unset($this->instances[$key]);
		}
		return true;
	}

	/*public function deleteAll(ElggEntity $entity) {
		if (!$entity->canEdit()) {
			return false;
		}
		$this->instances = array();
		elgg_delete_metadata(array(
			'guids' => $entity->guid,
			'wheres' => array(

			),
		));
	}/**/
}
