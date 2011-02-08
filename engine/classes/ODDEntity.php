<?php

/**
 * ODD Entity class.
 *
 * @package    Elgg.Core
 * @subpackage ODD
 */
class ODDEntity extends ODD {

	/**
	 * New ODD Entity
	 *
	 * @param string $uuid     A universally unique ID
	 * @param string $class    Class
	 * @param string $subclass Subclass
	 */
	function __construct($uuid, $class, $subclass = "") {
		parent::__construct();

		$this->setAttribute('uuid', $uuid);
		$this->setAttribute('class', $class);
		$this->setAttribute('subclass', $subclass);
	}

	/**
	 * Returns entity.
	 *
	 * @return 'entity'
	 */
	protected function getTagName() {
		return "entity";
	}
}

/**
 * ODD Metadata class.
 *
 * @package    Elgg.Core
 * @subpackage ODD
 */
class ODDMetaData extends ODD {

	/**
	 * New ODD metadata
	 *
	 * @param unknown_type $uuid        Unique ID
	 * @param unknown_type $entity_uuid Another unique ID
	 * @param unknown_type $name        Name
	 * @param unknown_type $value       Value
	 * @param unknown_type $type        Type
	 * @param unknown_type $owner_uuid  Owner ID
	 */
	function __construct($uuid, $entity_uuid, $name, $value, $type = "", $owner_uuid = "") {
		parent::__construct();

		$this->setAttribute('uuid', $uuid);
		$this->setAttribute('entity_uuid', $entity_uuid);
		$this->setAttribute('name', $name);
		$this->setAttribute('type', $type);
		$this->setAttribute('owner_uuid', $owner_uuid);
		$this->setBody($value);
	}

	/**
	 * Returns 'metadata'
	 *
	 * @return 'metadata'
	 */
	protected function getTagName() {
		return "metadata";
	}
}

/**
 * ODD Relationship class.
 *
 * @package    Elgg
 * @subpackage Core
 */
class ODDRelationship extends ODD {

	/**
	 * New ODD Relationship
	 *
	 * @param unknown_type $uuid1 First UUID
	 * @param unknown_type $type  Type of telationship
	 * @param unknown_type $uuid2 Second UUId
	 */
	function __construct($uuid1, $type, $uuid2) {
		parent::__construct();

		$this->setAttribute('uuid1', $uuid1);
		$this->setAttribute('type', $type);
		$this->setAttribute('uuid2', $uuid2);
	}

	/**
	 * Returns 'relationship'
	 *
	 * @return 'relationship'
	 */
	protected function getTagName() {
		return "relationship";
	}
}
