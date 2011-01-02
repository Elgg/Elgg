<?php
/**
 * The base class for ElggEntity extenders.
 *
 * Extenders allow you to attach extended information to an
 * ElggEntity.  Core supports two: ElggAnnotation, ElggMetadata,
 * and ElggRelationship
 *
 * Saving the extender data to database is handled by the child class.
 *
 * @tip Plugin authors would probably want to extend either ElggAnnotation
 * or ElggMetadata instead of this class.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Extender
 * @link       http://docs.elgg.org/DataModel/Extenders
 * @see        ElggAnnotation
 * @see        ElggMetadata
 */
abstract class ElggExtender extends ElggData
{
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = NULL;
	}

	/**
	 * Returns an attribute
	 *
	 * @param string $name Name
	 *
	 * @return mixed
	 */
	protected function get($name) {
		if (array_key_exists($name, $this->attributes)) {
			// Sanitise value if necessary
			if ($name == 'value') {
				switch ($this->attributes['value_type']) {
					case 'integer' :
						return (int)$this->attributes['value'];
						break;

					//case 'tag' :
					//case 'file' :
					case 'text' :
						return ($this->attributes['value']);
						break;

					default :
						$msg = elgg_echo('InstallationException:TypeNotSupported', array(
							$this->attributes['value_type']));

						throw new InstallationException($msg);
						break;
				}
			}

			return $this->attributes[$name];
		}
		return null;
	}

	/**
	 * Set an attribute
	 *
	 * @param string $name       Name
	 * @param mixed  $value      Value
	 * @param string $value_type Value type
	 *
	 * @return boolean
	 */
	protected function set($name, $value, $value_type = "") {
		$this->attributes[$name] = $value;
		if ($name == 'value') {
			$this->attributes['value_type'] = detect_extender_valuetype($value, $value_type);
		}

		return true;
	}

	/**
	 * Get the GUID of the extender's owner entity.
	 *
	 * @return int The owner GUID
	 */
	public function getOwnerGUID() {
		return $this->owner_guid;
	}

	/**
	 * Return the guid of the entity's owner.
	 *
	 * @return int The owner GUID
	 * @deprecated 1.8 Use getOwnerGUID
	 */
	public function getOwner() {
		elgg_deprecated_notice("ElggExtender::getOwner deprecated for ElggExtender::getOwnerGUID", 1.8);
		return $this->getOwnerGUID();
	}

	/**
	 * Get the entity that owns this extender
	 *
	 * @return ElggEntity
	 */
	public function getOwnerEntity() {
		return get_entity($this->owner_guid);
	}

	/**
	 * Get the entity this describes.
	 *
	 * @return ElggEntity The entity
	 */
	public function getEntity() {
		return get_entity($this->entity_guid);
	}

	/**
	 * Returns if a user can edit this extended data.
	 *
	 * @param int $user_guid The GUID of the user (defaults to currently logged in user)
	 *
	 * @return bool
	 */
	public function canEdit($user_guid = 0) {
		return can_edit_extender($this->id, $this->type, $user_guid);
	}

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array(
			'id',
			'entity_guid',
			'name',
			'value',
			'value_type',
			'owner_guid',
			'type',
		);
	}

	/**
	 * Export this object
	 *
	 * @return array
	 */
	public function export() {
		$uuid = get_uuid_from_object($this);

		$meta = new ODDMetadata($uuid, guid_to_uuid($this->entity_guid), $this->attributes['name'],
			$this->attributes['value'], $this->attributes['type'], guid_to_uuid($this->owner_guid));
		$meta->setAttribute('published', date("r", $this->time_created));

		return $meta;
	}

	/*
	 * SYSTEM LOG INTERFACE
	 */

	/**
	 * Return an identification for the object for storage in the system log.
	 * This id must be an integer.
	 *
	 * @return int
	 */
	public function getSystemLogID() {
		return $this->id;
	}

	/**
	 * Return a type of extension.
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Return a subtype. For metadata & annotations this is the 'name' and
	 * for relationship this is the relationship type.
	 *
	 * @return string
	 */
	public function getSubtype() {
		return $this->name;
	}

}
