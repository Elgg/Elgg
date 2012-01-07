<?php
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
