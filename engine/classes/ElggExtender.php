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
abstract class ElggExtender extends ElggData implements
	Exportable,
	Loggable	// Can events related to this object class be logged
{
	
	/**
	 * Returns an attribute
	 *
	 * @param string $name Name
	 *
	 * @return mixed
	 */
	protected function get($name) {
		if (isset($this->attributes[$name])) {
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
						$msg = sprintf(elgg_echo('InstallationException:TypeNotSupported'),
							$this->attributes['value_type']);

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
	 * Return the owner guid of this extender.
	 *
	 * @return int
	 */
	public function getOwner() {
		return $this->owner_guid;
	}

	/**
	 * Return the owner entity.
	 *
	 * @return ElggEntity|false
	 * @since 1.7.0
	 */
	public function getOwnerEntity() {
		return get_user($this->owner_guid);
	}

	/**
	 * Return the entity this describes.
	 *
	 * @return ElggEntity The enttiy
	 */
	public function getEntity() {
		return get_entity($this->entity_guid);
	}

	/**
	 * Save this data to the appropriate database table.
	 *
	 * @return bool
	 */
	abstract public function save();

	/**
	 * Delete this data.
	 *
	 * @return bool
	 */
	abstract public function delete();

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

	/**
	 * Return a url for this extender.
	 *
	 * @return string
	 */
	public abstract function getURL();

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
	 * Return the class name of the object.
	 *
	 * @return string
	 */
	public function getClassName() {
		return get_class($this);
	}

	/**
	 * Return the GUID of the owner of this object.
	 *
	 * @return int
	 */
	public function getObjectOwnerGUID() {
		return $this->owner_guid;
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
