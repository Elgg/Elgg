<?php
/**
 * ODD Metadata class.
 *
 * @package    Elgg.Core
 * @subpackage ODD
 * @deprecated 1.9
 */
class ODDMetaData extends ODD {

	/**
	 * New ODD metadata
	 *
	 * @param string $uuid        Unique ID
	 * @param string $entity_uuid Another unique ID
	 * @param string $name        Name
	 * @param string $value       Value
	 * @param string $type        Type
	 * @param string $owner_uuid  Owner ID
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
	 * @return string 'metadata'
	 */
	protected function getTagName() {
		return "metadata";
	}
}
