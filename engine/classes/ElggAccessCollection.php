<?php

/**
 * Access collection class
 *
 * @property-read int    $id         The unique identifier (read-only)
 * @property      int    $owner_guid GUID of the owner
 * @property      string $name       Name of the collection
 * @property      string $subtype    Subtype of the collection
 */
class ElggAccessCollection extends ElggData {

	/**
	 * Create an access collection object
	 *
	 * @param stdClass $row Database row
	 */
	public function __construct(stdClass $row = null) {
		$this->initializeAttributes();

		foreach ((array) $row as $key => $value) {
			$this->attributes[$key] = $value;
		}
	}

	/**
	 * Initialize the attributes array
	 *
	 * @return void
	 * @see ElggData::initializeAttributes()
	 */
	protected function initializeAttributes(): void {
		parent::initializeAttributes();

		$this->attributes['id'] = null;
		$this->attributes['owner_guid'] = _elgg_services()->session_manager->getLoggedInUserGuid();
		$this->attributes['name'] = null;
		$this->attributes['subtype'] = null;
	}

	/**
	 * Set an attribute
	 *
	 * @param string $name  The name of the attribute
	 * @param mixed  $value The value of the attribute
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\ExceptionInterface
	 */
	public function __set($name, $value): void {
		switch ($name) {
			case 'id':
				$value = (int) $value;
				break;
			case 'subtype':
				if (isset($value)) {
					$value = trim((string) $value);
					if (strlen($value) > 255) {
						throw new \Elgg\Exceptions\LengthException('The "subtype" of an ElggAccessCollection cannot be greated than 255 characters');
					}
				}
				break;
			case 'name':
				$value = trim((string) $value);
				if (empty($value)) {
					throw new \Elgg\Exceptions\LengthException('The "name" of an ElggAccessCollection cannot be empty');
				}
				break;
			case 'owner_guid':
				$value = (int) $value;
				if (!_elgg_services()->entityTable->exists($value)) {
					throw new \Elgg\Exceptions\InvalidArgumentException("The 'owner_guid' ({$value}) for the ElggAccessColelction doesn't seem to exists");
				}
				break;
		}
		
		$this->attributes[$name] = $value;
	}

	/**
	 * Get an attribute
	 *
	 * @param string $name The name of the attribute to get
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return $this->attributes[$name] ?? null;
	}

	/**
	 * Returns owner entity of the collection
	 *
	 * @return \ElggEntity|null
	 */
	public function getOwnerEntity(): ?\ElggEntity {
		return _elgg_services()->entityTable->get($this->owner_guid);
	}

	/**
	 * Get readable access level name for this collection
	 *
	 * @return string
	 */
	public function getDisplayName(): string {

		$filter = function($name = null) {
			if (!isset($name)) {
				$name = _elgg_services()->translator->translate('access:limited:label');
			}
			
			$params = [
				'access_collection' => $this,
			];
			return (string) _elgg_services()->events->triggerResults('access_collection:name', $this->getType(), $params, $name);
		};

		$user = _elgg_services()->session_manager->getLoggedInUser();
		$owner = $this->getOwnerEntity();
		if (!$user || !$owner) {
			// User is not logged in or does not access to the owner entity:
			// return default 'Limited' label
			return $filter();
		}
		
		if ($user->isAdmin() || $owner->guid == $user->guid) {
			return $filter($this->name);
		}

		return $filter();
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(): bool {
		if ($this->id > 0) {
			return _elgg_services()->accessCollections->update($this);
		}
		
		return _elgg_services()->accessCollections->create($this);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(): bool {
		return _elgg_services()->accessCollections->delete($this);
	}

	/**
	 * Check if user can edit this collection
	 *
	 * @param int $user_guid GUID of the user
	 *
	 * @return bool
	 */
	public function canEdit(int $user_guid = null): bool {
		return _elgg_services()->accessCollections->canEdit($this->id, $user_guid);
	}

	/**
	 * Returns members of the access collection
	 *
	 * @param array $options ege options
	 *
	 * @return ElggEntity[]|int|false
	 */
	public function getMembers(array $options = []) {
		return _elgg_services()->accessCollections->getMembers($this->id, $options);
	}

	/**
	 * Checks if user is already in access collection
	 *
	 * @param int $member_guid GUID of the user
	 *
	 * @return bool
	 */
	public function hasMember(int $member_guid = 0): bool {
		return _elgg_services()->accessCollections->hasUser($member_guid, $this->id);
	}

	/**
	 * Adds a user to access collection
	 *
	 * @param int $member_guid GUID of the user
	 *
	 * @return bool
	 */
	public function addMember(int $member_guid = 0): bool {
		return _elgg_services()->accessCollections->addUser($member_guid, $this->id);
	}

	/**
	 * Removes a user from access collection
	 *
	 * @param int $member_guid GUID of the user
	 *
	 * @return bool
	 */
	public function removeMember(int $member_guid = 0): bool {
		return _elgg_services()->accessCollections->removeUser($member_guid, $this->id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getURL(): string {
		$type = $this->getType();
		$params = [
			'access_collection' => $this,
		];
		$url = _elgg_services()->events->triggerResults('access_collection:url', $type, $params);
		return elgg_normalize_url($url);
	}

	/**
	 * {@inheritdoc}
	 */
	public function toObject(array $params = []) {
		$object = new \Elgg\Export\AccessCollection();
		$object->type = $this->getType();
		$object->subtype = $this->getSubtype();
		$object->id = $this->id;
		$object->owner_guid = $this->owner_guid;
		$object->name = $this->name;

		$params['access_collection'] = $this;

		return _elgg_services()->events->triggerResults('to:object', 'access_collection', $params, $object);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSystemLogID(): int {
		return (int) $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObjectFromID(int $id) {
		return _elgg_services()->accessCollections->get($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType(): string {
		return 'access_collection';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubtype(): string {
		if (isset($this->subtype)) {
			return $this->subtype;
		}
		
		return $this->name;
	}
}
