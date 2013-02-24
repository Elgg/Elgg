<?php
/**
 * ODD Relationship class.
 *
 * @package    Elgg
 * @subpackage Core
 * @deprecated 1.9
 */
class ODDRelationship extends ODD {

	/**
	 * New ODD Relationship
	 *
	 * @param string $uuid1 First UUID
	 * @param string $type  Type of telationship
	 * @param string $uuid2 Second UUId
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
	 * @return string 'relationship'
	 */
	protected function getTagName() {
		return "relationship";
	}
}
