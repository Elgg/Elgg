<?php
/**
 * ElggEntity The elgg entity superclass
 * This class holds methods for accessing the main entities table.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
abstract class ElggEntity implements
	Notable,    // Calendar interface
	Locatable,  // Geocoding interface
	Exportable, // Allow export of data
	Importable, // Allow import of data
	Loggable,	// Can events related to this object class be logged
	Iterator,	// Override foreach behaviour
	ArrayAccess // Override for array access
{
	/**
	 * The main attributes of an entity.
	 * Blank entries for all database fields should be created by the constructor.
	 * Subclasses should add to this in their constructors.
	 * Any field not appearing in this will be viewed as a
	 */
	protected $attributes;

	/**
	 * If set, overrides the value of getURL()
	 */
	protected $url_override;

	/**
	 * Icon override, overrides the value of getIcon().
	 */
	protected $icon_override;

	/**
	 * Temporary cache for metadata, permitting meta data access before a guid has obtained.
	 */
	protected $temp_metadata;

	/**
	 * Temporary cache for annotations, permitting meta data access before a guid has obtained.
	 */
	protected $temp_annotations;


	/**
	 * Volatile data structure for this object, allows for storage of data
	 * in-memory that isn't sync'd back to the metadata table.
	 */
	protected $volatile;

	/**
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 *
	 * @return void
	 */
	protected function initialise_attributes() {
		initialise_entity_cache();

		// Create attributes array if not already created
		if (!is_array($this->attributes)) {
			$this->attributes = array();
		}
		if (!is_array($this->temp_metadata)) {
			$this->temp_metadata = array();
		}
		if (!is_array($this->temp_annotations)) {
			$this->temp_annotations = array();
		}
		if (!is_array($this->volatile)) {
			$this->volatile = array();
		}

		$this->attributes['guid'] = "";
		$this->attributes['type'] = "";
		$this->attributes['subtype'] = "";

		$this->attributes['owner_guid'] = get_loggedin_userid();
		$this->attributes['container_guid'] = get_loggedin_userid();

		$this->attributes['site_guid'] = 0;
		$this->attributes['access_id'] = ACCESS_PRIVATE;
		$this->attributes['time_created'] = "";
		$this->attributes['time_updated'] = "";
		$this->attributes['last_action'] = '';
		$this->attributes['enabled'] = "yes";

		// There now follows a bit of a hack
		/* Problem: To speed things up, some objects are split over several tables, this means that it requires
		 * n number of database reads to fully populate an entity. This causes problems for caching and create events
		 * since it is not possible to tell whether a subclassed entity is complete.
		 * Solution: We have two counters, one 'tables_split' which tells whatever is interested how many tables
		 * are going to need to be searched in order to fully populate this object, and 'tables_loaded' which is how
		 * many have been loaded thus far.
		 * If the two are the same then this object is complete.
		 *
		 * Use: isFullyLoaded() to check
		 */
		$this->attributes['tables_split'] = 1;
		$this->attributes['tables_loaded'] = 0;
	}

	/**
	 * Clone an entity
	 *
	 * Resets the guid so that the entity can be saved as a distinct entity from
	 * the original. Creation time will be set when this new entity is saved.
	 * The owner and container guids come from the original entity. The clone
	 * method copies metadata but does not copy over annotations, or private settings.
	 *
	 * Note: metadata will have its owner and access id set when the entity is saved
	 * and it will be the same as that of the entity.
	 */
	public function __clone() {

		$orig_entity = get_entity($this->guid);
		if (!$orig_entity) {
			elgg_log("Failed to clone entity with GUID $this->guid", "ERROR");
			return;
		}

		$metadata_array = get_metadata_for_entity($this->guid);

		$this->attributes['guid'] = "";

		$this->attributes['subtype'] = $orig_entity->getSubtype();

		// copy metadata over to new entity - slightly convoluted due to
		// handling of metadata arrays
		if (is_array($metadata_array)) {
			// create list of metadata names
			$metadata_names = array();
			foreach ($metadata_array as $metadata) {
				$metadata_names[] = $metadata['name'];
			}
			// arrays are stored with multiple enties per name
			$metadata_names = array_unique($metadata_names);

			// move the metadata over
			foreach ($metadata_names as $name) {
				$this->set($name, $orig_entity->$name);
			}
		}
	}

	/**
	 * Return the value of a given key.
	 * If $name is a key field (as defined in $this->attributes) that value is returned, otherwise it will
	 * then look to see if the value is in this object's metadata.
	 *
	 * Q: Why are we not using __get overload here?
	 * A: Because overload operators cause problems during subclassing, so we put the code here and
	 * create overloads in subclasses.
	 *
	 * subtype is returned as an id rather than the subtype string. Use getSubtype()
	 * to get the subtype string.
	 *
	 * @param string $name
	 * @return mixed Returns the value of a given value, or null.
	 */
	public function get($name) {
		// See if its in our base attribute
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}

		// No, so see if its in the meta data for this entity
		$meta = $this->getMetaData($name);

		// getMetaData returns NULL if $name is not found
		return $meta;
	}

	/**
	 * Set the value of a given key, replacing it if necessary.
	 * If $name is a base attribute (as defined in $this->attributes) that value is set, otherwise it will
	 * set the appropriate item of metadata.
	 *
	 * Note: It is important that your class populates $this->attributes with keys for all base attributes, anything
	 * not in their gets set as METADATA.
	 *
	 * Q: Why are we not using __set overload here?
	 * A: Because overload operators cause problems during subclassing, so we put the code here and
	 * create overloads in subclasses.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Certain properties should not be manually changed!
			switch ($name) {
				case 'guid':
				case 'time_created':
				case 'time_updated':
				case 'last_action':
					return FALSE;
					break;
				default:
					$this->attributes[$name] = $value;
					break;
			}
		} else {
			return $this->setMetaData($name, $value);
		}

		return TRUE;
	}

	/**
	 * Get a given piece of metadata.
	 *
	 * @param string $name
	 */
	public function getMetaData($name) {
		if ((int) ($this->guid) > 0) {
			$md = get_metadata_byname($this->getGUID(), $name);
		} else {
			if (isset($this->temp_metadata[$name])) {
				return $this->temp_metadata[$name];
			}
		}

		if ($md && !is_array($md)) {
			return $md->value;
		} else if ($md && is_array($md)) {
			return metadata_array_to_values($md);
		}

		return null;
	}

	/**
	 * Class member get overloading
	 *
	 * @param string $name
	 * @return mixed
	 */
	function __get($name) {
		return $this->get($name);
	}

	/**
	 * Class member set overloading
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function __set($name, $value) {
		return $this->set($name, $value);
	}

	/**
	 * Supporting isset.
	 *
	 * @param string $name The name of the attribute or metadata.
	 * @return bool
	 */
	function __isset($name) {
		return $this->$name !== NULL;
	}

	/**
	 * Supporting unsetting of magic attributes.
	 *
	 * @param string $name The name of the attribute or metadata.
	 */
	function __unset($name) {
		if (array_key_exists($name, $this->attributes)) {
			$this->attributes[$name] = "";
		}
		else {
			$this->clearMetaData($name);
		}
	}

	/**
	 * Set a piece of metadata.
	 *
	 * @param string $name Name of the metadata
	 * @param mixed $value Value of the metadata
	 * @param string $value_type Types supported: integer and string. Will auto-identify if not set
	 * @param bool $multiple (does not support associative arrays)
	 * @return bool
	 */
	public function setMetaData($name, $value, $value_type = "", $multiple = false) {
		if (is_array($value)) {
			unset($this->temp_metadata[$name]);
			remove_metadata($this->getGUID(), $name);
			foreach ($value as $v) {
				if ((int) $this->guid > 0) {
					$multiple = true;
					if (!create_metadata($this->getGUID(), $name, $v, $value_type,
					$this->getOwner(), $this->getAccessID(), $multiple)) {
						return false;
					}
				} else {
					if (($multiple) && (isset($this->temp_metadata[$name]))) {
						if (!is_array($this->temp_metadata[$name])) {
							$tmp = $this->temp_metadata[$name];
							$this->temp_metadata[$name] = array();
							$this->temp_metadata[$name][] = $tmp;
						}

						$this->temp_metadata[$name][] = $value;
					}
					else {
						$this->temp_metadata[$name] = $value;
					}
				}
			}

			return true;
		} else {
			unset($this->temp_metadata[$name]);
			if ((int) $this->guid > 0) {
				$result = create_metadata($this->getGUID(), $name, $value, $value_type, $this->getOwner(), $this->getAccessID(), $multiple);
				return (bool)$result;
			} else {
				if (($multiple) && (isset($this->temp_metadata[$name]))) {
					if (!is_array($this->temp_metadata[$name])) {
						$tmp = $this->temp_metadata[$name];
						$this->temp_metadata[$name] = array();
						$this->temp_metadata[$name][] = $tmp;
					}

					$this->temp_metadata[$name][] = $value;
				}
				else {
					$this->temp_metadata[$name] = $value;
				}

				return true;
			}
		}
	}

	/**
	 * Clear metadata.
	 */
	public function clearMetaData($name = "") {
		if (empty($name)) {
			return clear_metadata($this->getGUID());
		} else {
			return remove_metadata($this->getGUID(),$name);
		}
	}


	/**
	 * Get a piece of volatile (non-persisted) data on this entity
	 */
	public function getVolatileData($name) {
		if (!is_array($this->volatile)) {
			$this->volatile = array();
		}

		if (array_key_exists($name, $this->volatile)) {
			return $this->volatile[$name];
		} else {
			return NULL;
		}
	}


	/**
	 * Set a piece of volatile (non-persisted) data on this entity
	 */
	public function setVolatileData($name, $value) {
		if (!is_array($this->volatile)) {
			$this->volatile = array();
		}

		$this->volatile[$name] = $value;
	}


	/**
	 * Remove all entities associated with this entity
	 *
	 * @return true
	 */
	public function clearRelationships() {
		remove_entity_relationships($this->getGUID());
		remove_entity_relationships($this->getGUID(),"",true);
		return true;
	}

	/**
	 * Add a relationship.
	 *
	 * @param int $guid Relationship to link to.
	 * @param string $relationship The type of relationship.
	 * @return bool
	 */
	public function addRelationship($guid, $relationship) {
		return add_entity_relationship($this->getGUID(), $relationship, $guid);
	}

	/**
	 * Remove a relationship
	 *
	 * @param int $guid
	 * @param str $relationship
	 * @return bool
	 */
	public function removeRelationship($guid, $relationship) {
		return remove_entity_relationship($this->getGUID(), $relationship, $guid);
	}

	/**
	 * Adds a private setting to this entity.
	 *
	 * @param $name
	 * @param $value
	 * @return unknown_type
	 */
	function setPrivateSetting($name, $value) {
		return set_private_setting($this->getGUID(), $name, $value);
	}

	/**
	 * Gets private setting for this entity
	 *
	 * @param $name
	 * @return unknown_type
	 */
	function getPrivateSetting($name) {
		return get_private_setting($this->getGUID(), $name);
	}

	/**
	 * Removes private setting for this entity.
	 *
	 * @param $name
	 * @return unknown_type
	 */
	function removePrivateSetting($name) {
		return remove_private_setting($this->getGUID(), $name);
	}

	/**
	 * Adds an annotation to an entity. By default, the type is detected automatically; however,
	 * it can also be set. Note that by default, annotations are private.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param int $access_id
	 * @param int $owner_id
	 * @param string $vartype
	 */
	function annotate($name, $value, $access_id = ACCESS_PRIVATE, $owner_id = 0, $vartype = "") {
		if ((int) $this->guid > 0) {
			return create_annotation($this->getGUID(), $name, $value, $vartype, $owner_id, $access_id);
		} else {
			$this->temp_annotations[$name] = $value;
		}
		return true;
	}

	/**
	 * Get the annotations for an entity.
	 *
	 * @param string $name
	 * @param int $limit
	 * @param int $offset
	 * @param string $order
	 */
	function getAnnotations($name, $limit = 50, $offset = 0, $order="asc") {
		if ((int) ($this->guid) > 0) {
			return get_annotations($this->getGUID(), "", "", $name, "", 0, $limit, $offset, $order);
		} else {
			return $this->temp_annotations[$name];
		}
	}

	/**
	 * Remove all annotations or all annotations for this entity.
	 *
	 * @param string $name
	 */
	function clearAnnotations($name = "") {
		return clear_annotations($this->getGUID(), $name);
	}

	/**
	 * Return the annotations for the entity.
	 *
	 * @param string $name The type of annotation.
	 */
	function countAnnotations($name = "") {
		return count_annotations($this->getGUID(), "", "", $name);
	}

	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name
	 */
	function getAnnotationsAvg($name) {
		return get_annotations_avg($this->getGUID(), "", "", $name);
	}

	/**
	 * Get the sum of integer type annotations of a given name.
	 *
	 * @param string $name
	 */
	function getAnnotationsSum($name) {
		return get_annotations_sum($this->getGUID(), "", "", $name);
	}

	/**
	 * Get the minimum of integer type annotations of given name.
	 *
	 * @param string $name
	 */
	function getAnnotationsMin($name) {
		return get_annotations_min($this->getGUID(), "", "", $name);
	}

	/**
	 * Get the maximum of integer type annotations of a given name.
	 *
	 * @param string $name
	 */
	function getAnnotationsMax($name) {
		return get_annotations_max($this->getGUID(), "", "", $name);
	}

	/**
	 * Gets an array of entities from a specific relationship type
	 *
	 * @param string $relationship Relationship type (eg "friends")
	 * @param true|false $inverse Is this an inverse relationship?
	 * @param int $limit Number of elements to return
	 * @param int $offset Indexing offset
	 * @return array|false An array of entities or false on failure
	 */
	function getEntitiesFromRelationship($relationship, $inverse = false, $limit = 50, $offset = 0) {
		return elgg_get_entities_from_relationship(array(
			'relationship' => $relationship,
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => $inverse,
			'limit' => $limit,
			'offset' => $offset
		));
	}

	/**
	 * Gets the number of of entities from a specific relationship type
	 *
	 * @param string $relationship Relationship type (eg "friends")
	 * @param bool $inverse_relationship
	 * @return int|false The number of entities or false on failure
	 */
	function countEntitiesFromRelationship($relationship, $inverse_relationship = FALSE) {
		return elgg_get_entities_from_relationship(array(
			'relationship' => $relationship,
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => $inverse_relationship,
			'count' => TRUE
		));
	}

	/**
	 * Determines whether or not the specified user (by default the current one) can edit the entity
	 *
	 * @param int $user_guid The user GUID, optionally (defaults to the currently logged in user)
	 * @return true|false
	 */
	function canEdit($user_guid = 0) {
		return can_edit_entity($this->getGUID(), $user_guid);
	}

	/**
	 * Determines whether or not the specified user (by default the current one) can edit metadata on the entity
	 *
	 * @param ElggMetadata $metadata The piece of metadata to specifically check
	 * @param int $user_guid The user GUID, optionally (defaults to the currently logged in user)
	 * @return true|false
	 */
	function canEditMetadata($metadata = null, $user_guid = 0) {
		return can_edit_entity_metadata($this->getGUID(), $user_guid, $metadata);
	}

	/**
	 * Returns whether the given user (or current user) has the ability to write to this container.
	 *
	 * @param int $user_guid The user.
	 * @return bool
	 */
	public function canWriteToContainer($user_guid = 0) {
		return can_write_to_container($user_guid, $this->getGUID());
	}

	/**
	 * Obtain this entity's access ID
	 *
	 * @return int The access ID
	 */
	public function getAccessID() {
		return $this->get('access_id');
	}

	/**
	 * Obtain this entity's GUID
	 *
	 * @return int GUID
	 */
	public function getGUID() {
		return $this->get('guid');
	}

	/**
	 * Get the owner of this entity
	 *
	 * @return int The owner GUID
	 */
	public function getOwner() {
		return $this->get('owner_guid');
	}

	/**
	 * Returns the actual entity of the user who owns this entity, if any
	 *
	 * @return ElggEntity The owning user
	 */
	public function getOwnerEntity() {
		return get_entity($this->get('owner_guid'));
	}

	/**
	 * Gets the type of entity this is
	 *
	 * @return string Entity type
	 */
	public function getType() {
		return $this->get('type');
	}

	/**
	 * Returns the subtype of this entity
	 *
	 * @return string The entity subtype
	 */
	public function getSubtype() {
		// If this object hasn't been saved, then return the subtype string.
		if (!((int) $this->guid > 0)) {
			return $this->get('subtype');
		}

		return get_subtype_from_id($this->get('subtype'));
	}

	/**
	 * Gets the UNIX epoch time that this entity was created
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeCreated() {
		return $this->get('time_created');
	}

	/**
	 * Gets the UNIX epoch time that this entity was last updated
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeUpdated() {
		return $this->get('time_updated');
	}

	/**
	 * Gets the display URL for this entity
	 *
	 * @return string The URL
	 */
	public function getURL() {
		if (!empty($this->url_override)) {
			return $this->url_override;
		}
		return get_entity_url($this->getGUID());
	}

	/**
	 * Overrides the URL returned by getURL
	 *
	 * @param string $url The new item URL
	 * @return string The URL
	 */
	public function setURL($url) {
		$this->url_override = $url;
		return $url;
	}

	/**
	 * Return a url for the entity's icon, trying multiple alternatives.
	 *
	 * @param string $size Either 'large','medium','small' or 'tiny'
	 * @return string The url or false if no url could be worked out.
	 */
	public function getIcon($size = 'medium') {
		if (isset($this->icon_override[$size])) {
			return $this->icon_override[$size];
		}
		return get_entity_icon_url($this, $size);
	}

	/**
	 * Set an icon override for an icon and size.
	 *
	 * @param string $url The url of the icon.
	 * @param string $size The size its for.
	 * @return bool
	 */
	public function setIcon($url, $size = 'medium') {
		$url = sanitise_string($url);
		$size = sanitise_string($size);

		if (!$this->icon_override) {
			$this->icon_override = array();
		}
		$this->icon_override[$size] = $url;

		return true;
	}

	/**
	 * Tests to see whether the object has been fully loaded.
	 *
	 * @return bool
	 */
	public function isFullyLoaded() {
		return ! ($this->attributes['tables_loaded'] < $this->attributes['tables_split']);
	}

	/**
	 * Save generic attributes to the entities table.
	 */
	public function save() {
		$guid = (int) $this->guid;
		if ($guid > 0) {
			cache_entity($this);

			return update_entity(
				$this->get('guid'),
				$this->get('owner_guid'),
				$this->get('access_id'),
				$this->get('container_guid')
			);
		} else {
			// Create a new entity (nb: using attribute array directly 'cos set function does something special!)
			$this->attributes['guid'] = create_entity($this->attributes['type'], $this->attributes['subtype'], $this->attributes['owner_guid'], $this->attributes['access_id'], $this->attributes['site_guid'], $this->attributes['container_guid']);
			if (!$this->attributes['guid']) {
				throw new IOException(elgg_echo('IOException:BaseEntitySaveFailed'));
			}

			// Save any unsaved metadata
			// @todo How to capture extra information (access id etc)
			if (sizeof($this->temp_metadata) > 0) {
				foreach($this->temp_metadata as $name => $value) {
					$this->$name = $value;
					unset($this->temp_metadata[$name]);
				}
			}

			// Save any unsaved annotations metadata.
			// @todo How to capture extra information (access id etc)
			if (sizeof($this->temp_annotations) > 0) {
				foreach($this->temp_annotations as $name => $value) {
					$this->annotate($name, $value);
					unset($this->temp_annotations[$name]);
				}
			}

			// set the subtype to id now rather than a string
			$this->attributes['subtype'] = get_subtype_id($this->attributes['type'], $this->attributes['subtype']);

			// Cache object handle
			if ($this->attributes['guid']) {
				cache_entity($this);
			}

			return $this->attributes['guid'];
		}
	}

	/**
	 * Load the basic entity information and populate base attributes array.
	 *
	 * @param int $guid
	 */
	protected function load($guid) {
		$row = get_entity_as_row($guid);

		if ($row) {
			// Create the array if necessary - all subclasses should test before creating
			if (!is_array($this->attributes)) {
				$this->attributes = array();
			}

			// Now put these into the attributes array as core values
			$objarray = (array) $row;
			foreach($objarray as $key => $value) {
				$this->attributes[$key] = $value;
			}

			// Increment the portion counter
			if (!$this->isFullyLoaded()) {
				$this->attributes['tables_loaded']++;
			}

			// Cache object handle
			if ($this->attributes['guid']) {
				cache_entity($this);
			}

			return true;
		}

		return false;
	}

	/**
	 * Disable this entity.
	 *
	 * @param string $reason Optional reason
	 * @param bool $recursive Recursively disable all contained entities?
	 */
	public function disable($reason = "", $recursive = true) {
		return disable_entity($this->get('guid'), $reason, $recursive);
	}

	/**
	 * Re-enable this entity.
	 */
	public function enable() {
		return enable_entity($this->get('guid'));
	}

	/**
	 * Is this entity enabled?
	 *
	 * @return boolean
	 */
	public function isEnabled() {
		if ($this->enabled == 'yes') {
			return true;
		}

		return false;
	}

	/**
	 * Delete this entity.
	 */
	public function delete() {
		return delete_entity($this->get('guid'));
	}

	// LOCATABLE INTERFACE /////////////////////////////////////////////////////////////

	/** Interface to set the location */
	public function setLocation($location) {
		$location = sanitise_string($location);

		$this->location = $location;

		return true;
	}

	/**
	 * Set latitude and longitude tags for a given entity.
	 *
	 * @param float $lat
	 * @param float $long
	 */
	public function setLatLong($lat, $long) {
		$lat = sanitise_string($lat);
		$long = sanitise_string($long);

		$this->set('geo:lat', $lat);
		$this->set('geo:long', $long);

		return true;
	}

	/**
	 * Get the contents of the ->geo:lat field.
	 *
	 */
	public function getLatitude() {
		return $this->get('geo:lat');
	}

	/**
	 * Get the contents of the ->geo:lat field.
	 *
	 */
	public function getLongitude() {
		return $this->get('geo:long');
	}

	/**
	 * Get the ->location metadata.
	 *
	 */
	public function getLocation() {
		return $this->get('location');
	}

	// NOTABLE INTERFACE ///////////////////////////////////////////////////////////////

	/**
	 * Calendar functionality.
	 * This function sets the time of an object on a calendar listing.
	 *
	 * @param int $hour If ommitted, now is assumed.
	 * @param int $minute If ommitted, now is assumed.
	 * @param int $second If ommitted, now is assumed.
	 * @param int $day If ommitted, now is assumed.
	 * @param int $month If ommitted, now is assumed.
	 * @param int $year If ommitted, now is assumed.
	 * @param int $duration Duration of event, remainder of the day is assumed.
	 */
	public function setCalendarTimeAndDuration($hour = NULL, $minute = NULL, $second = NULL, $day = NULL, $month = NULL, $year = NULL, $duration = NULL) {
		$start = mktime($hour, $minute, $second, $month, $day, $year);
		$end = $start + abs($duration);
		if (!$duration) {
			$end = get_day_end($day,$month,$year);
		}

		$this->calendar_start = $start;
		$this->calendar_end = $end;

		return true;
	}

	/**
	 * Return the start timestamp.
	 */
	public function getCalendarStartTime() {
		return (int)$this->calendar_start;
	}

	/**
	 * Return the end timestamp.
	 */
	public function getCalendarEndTime() {
		return (int)$this->calendar_end;
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array(
			'guid',
			'type',
			'subtype',
			'time_created',
			'time_updated',
			'container_guid',
			'owner_guid',
			'site_guid'
		);
	}

	/**
	 * Export this class into an array of ODD Elements containing all necessary fields.
	 * Override if you wish to return more information than can be found in $this->attributes (shouldn't happen)
	 */
	public function export() {
		$tmp = array();

		// Generate uuid
		$uuid = guid_to_uuid($this->getGUID());

		// Create entity
		$odd = new ODDEntity(
			$uuid,
			$this->attributes['type'],
			get_subtype_from_id($this->attributes['subtype'])
		);

		$tmp[] = $odd;

		$exportable_values = $this->getExportableValues();

		// Now add its attributes
		foreach ($this->attributes as $k => $v) {
			$meta = NULL;

			if (in_array( $k, $exportable_values)) {
				switch ($k) {
					case 'guid' : 			// Dont use guid in OpenDD
					case 'type' :			// Type and subtype already taken care of
					case 'subtype' :
					break;

					case 'time_created' :	// Created = published
						$odd->setAttribute('published', date("r", $v));
					break;

					case 'site_guid' : // Container
						$k = 'site_uuid';
						$v = guid_to_uuid($v);
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
					break;

					case 'container_guid' : // Container
						$k = 'container_uuid';
						$v = guid_to_uuid($v);
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
					break;

					case 'owner_guid' :			// Convert owner guid to uuid, this will be stored in metadata
						$k = 'owner_uuid';
						$v = guid_to_uuid($v);
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
					break;

					default :
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
				}

				// set the time of any metadata created
				if ($meta) {
					$meta->setAttribute('published', date("r",$this->time_created));
					$tmp[] = $meta;
				}
			}
		}

		// Now we do something a bit special.
		/*
		 * This provides a rendered view of the entity to foreign sites.
		 */

		elgg_set_viewtype('default');
		$view = elgg_view_entity($this, true);
		elgg_set_viewtype();

		$tmp[] = new ODDMetaData($uuid . "volatile/renderedentity/", $uuid, 'renderedentity', $view , 'volatile');

		return $tmp;
	}

	// IMPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Import data from an parsed xml data array.
	 *
	 * @param array $data
	 * @param int $version
	 */
	public function import(ODD $data) {
		if (!($data instanceof ODDEntity)) {
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnexpectedODDClass'));
		}

		// Set type and subtype
		$this->attributes['type'] = $data->getAttribute('class');
		$this->attributes['subtype'] = $data->getAttribute('subclass');

		// Set owner
		$this->attributes['owner_guid'] = get_loggedin_userid(); // Import as belonging to importer.

		// Set time
		$this->attributes['time_created'] = strtotime($data->getAttribute('published'));
		$this->attributes['time_updated'] = time();

		return true;
	}

	// SYSTEM LOG INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an identification for the object for storage in the system log.
	 * This id must be an integer.
	 *
	 * @return int
	 */
	public function getSystemLogID() {
		return $this->getGUID();
	}

	/**
	 * Return the class name of the object.
	 */
	public function getClassName() {
		return get_class($this);
	}

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 */
	public function getObjectFromID($id) {
		return get_entity($id);
	}

	/**
	 * Return the GUID of the owner of this object.
	 */
	public function getObjectOwnerGUID() {
		return $this->owner_guid;
	}

	/**
	 * Returns tags for this entity.
	 *
	 * @param array $tag_names Optionally restrict by tag metadata names.
	 * @return array
	 */
	public function getTags($tag_names = NULL) {
		global $CONFIG;

		if ($tag_names && !is_array($tag_names)) {
			$tag_names = array($tag_names);
		}

		$valid_tags = elgg_get_registered_tag_metadata_names();
		$entity_tags = array();

		foreach ($valid_tags as $tag_name) {
			if (is_array($tag_names) && !in_array($tag_name, $tag_names)) {
				continue;
			}

			if ($tags = $this->$tag_name) {
				// if a single tag, metadata returns a string.
				// if multiple tags, metadata returns an array.
				if (is_array($tags)) {
					$entity_tags = array_merge($entity_tags, $tags);
				} else {
					$entity_tags[] = $tags;
				}
			}
		}

		return $entity_tags;
	}

	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be displayed using foreach as a normal array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	private $valid = FALSE;

	function rewind() {
		$this->valid = (FALSE !== reset($this->attributes));
	}

	function current() {
		return current($this->attributes);
	}

	function key() {
		return key($this->attributes);
	}

	function next() {
		$this->valid = (FALSE !== next($this->attributes));
	}

	function valid() {
		return $this->valid;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be accessed like an associative array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	function offsetSet($key, $value) {
		if ( array_key_exists($key, $this->attributes) ) {
			$this->attributes[$key] = $value;
		}
	}

	function offsetGet($key) {
		if ( array_key_exists($key, $this->attributes) ) {
			return $this->attributes[$key];
		}
	}

	function offsetUnset($key) {
		if ( array_key_exists($key, $this->attributes) ) {
			$this->attributes[$key] = ""; // Full unsetting is dangerious for our objects
		}
	}

	function offsetExists($offset) {
		return array_key_exists($offset, $this->attributes);
	}
}