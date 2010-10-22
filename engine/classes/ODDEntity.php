<?php

/**
 * ODD Entity class.
 * @package Elgg
 * @subpackage Core
 */
class ODDEntity extends ODD {
	function __construct($uuid, $class, $subclass = "") {
		parent::__construct();

		$this->setAttribute('uuid', $uuid);
		$this->setAttribute('class', $class);
		$this->setAttribute('subclass', $subclass);
	}

	protected function getTagName() { return "entity"; }
}

/**
 * ODD Metadata class.
 * @package Elgg
 * @subpackage Core
 */
class ODDMetaData extends ODD {
	function __construct($uuid, $entity_uuid, $name, $value, $type = "", $owner_uuid = "") {
		parent::__construct();

		$this->setAttribute('uuid', $uuid);
		$this->setAttribute('entity_uuid', $entity_uuid);
		$this->setAttribute('name', $name);
		$this->setAttribute('type', $type);
		$this->setAttribute('owner_uuid', $owner_uuid);
		$this->setBody($value);
	}

	protected function getTagName() {
		return "metadata";
	}
}

/**
 * ODD Relationship class.
 * @package Elgg
 * @subpackage Core
 */
class ODDRelationship extends ODD {
	function __construct($uuid1, $type, $uuid2) {
		parent::__construct();

		$this->setAttribute('uuid1', $uuid1);
		$this->setAttribute('type', $type);
		$this->setAttribute('uuid2', $uuid2);
	}

	protected function getTagName() { return "relationship"; }
}