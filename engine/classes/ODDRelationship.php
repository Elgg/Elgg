<?php
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
