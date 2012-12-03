<?php

/**
 * A named and ordered collection of entities handy for modifying elgg_get_entities() queries.
 *
 * A collection can be thought of as metadata that stores a list of entities in a way that's optimized
 * for SQL JOIN operations. For now, a central collection manager can be used to fetch or create collection
 * objects, but mostly plugin devs won't need to interact with these unless they want to alter collection
 * items.
 *
 * @note Use the collection manager to access collections, and the getAccessor() method to get
 *       an object for accessing/editing the items directly.
 *
 * @property int $access_id Access ID of the metadata. Setting persists this property immediately.
 */
class ElggCollection {

	const TABLE_UNPREFIXED = 'entity_relationships';
	const COL_PRIORITY = 'id';
	const COL_ITEM = 'guid_one';
	const COL_ENTITY_GUID = 'guid_two';
	const COL_KEY = 'relationship';
	const COL_TIME = 'time_created';

	/**
	 * @var ElggEntity
	 */
	protected $entity;

	/**
	 * @var int
	 */
	protected $entity_guid;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var bool
	 */
	protected $is_deleted = false;

	/**
	 * @var bool
	 */
	protected $logged_in_user_can_edit = null;

	/**
	 * @var int
	 */
	protected $logged_in_user_guid = null;

	/**
	 * @var string
	 */
	protected $relationship_key;

	/**
	 * @param ElggEntity $entity
	 * @param string $name
	 * @param bool $has_existence_metadata Does metadata exist to let the manager know about this collection?
	 *
	 * @access private
	 */
	public function __construct(ElggEntity $entity, $name, $has_existence_metadata) {
		$this->entity = $entity;
		$this->entity_guid = $entity->guid;
		$this->name = $name;
		$hash = base64_encode(md5("$this->entity_guid|$name", true));
		$this->relationship_key = "in_collection:$hash";
		if (!$has_existence_metadata) {
			create_metadata($this->entity_guid, "collection_exists:$name", '1', 'integer', 0, ACCESS_PUBLIC);
		}
	}

	/**
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return bool
	 *
	 * @access private
	 */
	public static function canSeeExistenceMetadata(ElggEntity $entity, $name) {
		return (bool) $entity->getMetaData("collection_exists:$name");
	}

	/**
	 * Can a user edit this collection?
	 *
	 * @param int $user_guid The GUID of the user (defaults to currently logged in user)
	 *
	 * @return bool
	 */
	public function canEdit($user_guid = 0) {
		if (! $user_guid) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		if ($this->is_deleted) {
			return false;
		}
		// cache permission of current user internally because this may get called a lot
		// by the item modification methods
		if ($user_guid === $this->logged_in_user_guid) {
			return $this->logged_in_user_can_edit;
		}
		$this->logged_in_user_guid = $user_guid;
		$this->logged_in_user_can_edit = false;
		// can edit only if not deleted and can edit the entity
		if (!$this->is_deleted && $this->entity->canEdit($user_guid)) {
			$this->logged_in_user_can_edit = true;
		}
		return $this->logged_in_user_can_edit;
	}

	/**
	 * @return int
	 */
	public function getEntityGuid() {
		return $this->entity_guid;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getRelationshipKey() {
		return $this->relationship_key;
	}

	/**
	 * Delete the collection (and its items)
	 *
	 * @return bool
	 */
	public function delete() {
		if ($this->canEdit()) {

			$this->getAccessor()->removeAll();
			elgg_delete_metadata(array(
				'guid' => $this->entity_guid,
				'metadata_name' => "collection_exists:$this->name",
			));

			$this->is_deleted = true;
			$this->logged_in_user_can_edit = false;
			return true;
		}
		return false;
	}

	/**
	 * @return ElggCollectionAccessor|null
	 */
	public function getAccessor() {
		if ($this->is_deleted) {
			return null;
		}
		return new ElggCollectionAccessor($this);
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws RuntimeException
	 */
	public function __get($name) {
		if ($name === 'access_id') {
			if ($this->is_deleted) {
				return null;
			}
			$prefix = elgg_get_config('dbprefix');
			$name_id = get_metastring_id("collection_exists:$this->name");
			$row = elgg_get_database()->getDataRow("
				SELECT owner_guid, access_id FROM {$prefix}metadata
				WHERE name_id = $name_id AND entity_guid = $this->entity_guid
				LIMIT 1
			");
			if ($row) {
				return $row->{$name};
			}
		}
		throw new RuntimeException('Property does not exist: ' . $name);
	}

	/**
	 * @param string $name
	 * @param int $value
	 * @throws RuntimeException
	 */
	public function __set($name, $value) {
		$value = (int)$value;
		if ($name === 'access_id') {
			if ($this->is_deleted) {
				throw new RuntimeException('Cannot set property after deletion');
			}
			if (!$this->canEdit()) {
				return;
			}
			// if the user can edit the entity, she must be allowed to
			// alter the owner/access level, regardless of the metadata's access.
			$prefix = elgg_get_config('dbprefix');
			$name_id = get_metastring_id("collection_exists:$this->name");
			update_data("
				UPDATE {$prefix}metadata SET $name = $value
				WHERE name_id = $name_id AND entity_guid = $this->entity_guid
			");
		}
	}

	/**
	 * @return boolean
	 */
	public function isDeleted()
	{
		return $this->is_deleted;
	}
}
