<?php
/**
 * Elgg entities.
 * Functions to manage all elgg entities (sites, groups, objects and users).
 *
 * @package Elgg
 * @subpackage Core
 */

/// Cache objects in order to minimise database access.
global $ENTITY_CACHE;
$ENTITY_CACHE = NULL;

/// Cache subtype searches
global $SUBTYPE_CACHE;
$SUBTYPE_CACHE = NULL;

/// Require the locatable interface
// @todo Move this into start.php?
require_once('location.php');

/**
 * ElggEntity The elgg entity superclass
 * This class holds methods for accessing the main entities table.
 *
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
	 * @return bool
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

/**
 * Initialise the entity cache.
 */
function initialise_entity_cache() {
	global $ENTITY_CACHE;

	if (!$ENTITY_CACHE) {
		//select_default_memcache('entity_cache'); // @todo Replace with memcache?
		$ENTITY_CACHE = array();
	}
}

/**
 * Invalidate this class' entry in the cache.
 *
 * @param int $guid The guid
 */
function invalidate_cache_for_entity($guid) {
	global $ENTITY_CACHE;

	$guid = (int)$guid;

	unset($ENTITY_CACHE[$guid]);
	//$ENTITY_CACHE->delete($guid);
}

/**
 * Cache an entity.
 *
 * @param ElggEntity $entity Entity to cache
 */
function cache_entity(ElggEntity $entity) {
	global $ENTITY_CACHE;

	$ENTITY_CACHE[$entity->guid] = $entity;
}

/**
 * Retrieve a entity from the cache.
 *
 * @param int $guid The guid
 */
function retrieve_cached_entity($guid) {
	global $ENTITY_CACHE;

	$guid = (int)$guid;

	if (isset($ENTITY_CACHE[$guid])) {
		if ($ENTITY_CACHE[$guid]->isFullyLoaded()) {
			return $ENTITY_CACHE[$guid];
		}
	}

	return false;
}

/**
 * As retrieve_cached_entity, but returns the result as a stdClass (compatible with load functions that
 * expect a database row.)
 *
 * @param int $guid The guid
 */
function retrieve_cached_entity_row($guid) {
	$obj = retrieve_cached_entity($guid);
	if ($obj) {
		$tmp = new stdClass;

		foreach ($obj as $k => $v) {
			$tmp->$k = $v;
		}

		return $tmp;
	}

	return false;
}

/**
 * Return the integer ID for a given subtype, or false.
 *
 * @todo Move to a nicer place?
 *
 * @param string $type
 * @param string $subtype
 */
function get_subtype_id($type, $subtype) {
	global $CONFIG, $SUBTYPE_CACHE;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	if ($subtype=="") {
		//return $subtype;
		return FALSE;
	}

	// Todo: cache here? Or is looping less efficient that going to the db each time?
	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes
		where type='$type' and subtype='$subtype'");

	if ($result) {
		if (!$SUBTYPE_CACHE) {
			//select_default_memcache('subtype_cache');
			$SUBTYPE_CACHE = array();
		}

		$SUBTYPE_CACHE[$result->id] = $result;
		return $result->id;
	}

	return FALSE;
}

/**
 * For a given subtype ID, return its identifier text.
 *
 * @todo Move to a nicer place?
 *
 * @param int $subtype_id
 */
function get_subtype_from_id($subtype_id) {
	global $CONFIG, $SUBTYPE_CACHE;

	$subtype_id = (int)$subtype_id;

	if (!$subtype_id) {
		return false;
	}

	if (isset($SUBTYPE_CACHE[$subtype_id])) {
		return $SUBTYPE_CACHE[$subtype_id]->subtype;
	}

	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id=$subtype_id");
	if ($result) {
		if (!$SUBTYPE_CACHE) {
			//select_default_memcache('subtype_cache');
			$SUBTYPE_CACHE = array();
		}

		$SUBTYPE_CACHE[$subtype_id] = $result;
		return $result->subtype;
	}

	return false;
}

/**
 * This function tests to see if a subtype has a registered class handler.
 *
 * @param string $type The type
 * @param string $subtype The subtype
 * @return a class name or null
 */
function get_subtype_class($type, $subtype) {
	global $CONFIG, $SUBTYPE_CACHE;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	// Todo: cache here? Or is looping less efficient that going to the db each time?
	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes
		where type='$type' and subtype='$subtype'");

	if ($result) {
		if (!$SUBTYPE_CACHE) {
			//select_default_memcache('subtype_cache');
			$SUBTYPE_CACHE = array();
		}

		$SUBTYPE_CACHE[$result->id] = $result;
		return $result->class;
	}

	return NULL;
}

/**
 * This function tests to see if a subtype has a registered class handler by its id.
 *
 * @param int $subtype_id The subtype
 * @return a class name or null
 */
function get_subtype_class_from_id($subtype_id) {
	global $CONFIG, $SUBTYPE_CACHE;

	$subtype_id = (int)$subtype_id;

	if (!$subtype_id) {
		return false;
	}

	if (isset($SUBTYPE_CACHE[$subtype_id])) {
		return $SUBTYPE_CACHE[$subtype_id]->class;
	}

	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id=$subtype_id");

	if ($result) {
		if (!$SUBTYPE_CACHE) {
			//select_default_memcache('subtype_cache');
			$SUBTYPE_CACHE = array();
		}
		$SUBTYPE_CACHE[$subtype_id] = $result;
		return $result->class;
	}

	return NULL;
}

/**
 * This function will register a new subtype, returning its ID as required.
 *
 * @param string $type The type you're subtyping
 * @param string $subtype The subtype label
 * @param string $class Optional class handler (if you don't want it handled by the generic elgg handler for the type)
 */
function add_subtype($type, $subtype, $class = "") {
	global $CONFIG;
	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);
	$class = sanitise_string($class);

	// Short circuit if no subtype is given
	if ($subtype == "") {
		return 0;
	}

	$id = get_subtype_id($type, $subtype);

	if ($id==0) {
		return insert_data("insert into {$CONFIG->dbprefix}entity_subtypes (type, subtype, class) values ('$type','$subtype','$class')");
	}

	return $id;
}

/**
 * Update an existing entity.
 *
 * @param int $guid
 * @param int $owner_guid
 * @param int $access_id
 * @param int $container_guid
 */
function update_entity($guid, $owner_guid, $access_id, $container_guid = null) {
	global $CONFIG, $ENTITY_CACHE;

	$guid = (int)$guid;
	$owner_guid = (int)$owner_guid;
	$access_id = (int)$access_id;
	$container_guid = (int) $container_guid;
	if (is_null($container_guid)) {
		$container_guid = $owner_guid;
	}
	$time = time();

	$entity = get_entity($guid);

	if (!$entity) {
		return FALSE;
	}

	if ($entity->canEdit()) {
		if (trigger_elgg_event('update',$entity->type,$entity)) {
			$ret = update_data("UPDATE {$CONFIG->dbprefix}entities set owner_guid='$owner_guid', access_id='$access_id', container_guid='$container_guid', time_updated='$time' WHERE guid=$guid");

			if ($entity instanceof ElggObject) {
				update_river_access_by_object($guid,$access_id);
			}

			// If memcache is available then delete this entry from the cache
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}
			if ($newentity_cache) {
				$new_entity = $newentity_cache->delete($guid);
			}

			// Handle cases where there was no error BUT no rows were updated!
			if ($ret===false) {
				return false;
			}

			return true;
		}
	}
}

/**
 * Determine whether a given user is able to write to a given container.
 *
 * @param int $user_guid The user guid, or 0 for get_loggedin_userid()
 * @param int $container_guid The container, or 0 for the current page owner.
 */
function can_write_to_container($user_guid = 0, $container_guid = 0, $entity_type = 'all') {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	$container_guid = (int)$container_guid;
	if (!$container_guid) {
		$container_guid = page_owner();
	}

	if (!$container_guid) {
		$return = TRUE;
	}

	$container = get_entity($container_guid);

	if ($container) {
		// If the user can edit the container, they can also write to it
		if ($container->canEdit($user_guid)) {
			$return = TRUE;
		}

		// Basics, see if the user is a member of the group.
		if ($user && $container instanceof ElggGroup) {
			if (!$container->isMember($user)) {
				$return = FALSE;
			} else {
				$return = TRUE;
			}
		}
	}

	// See if anyone else has anything to say
	return trigger_plugin_hook('container_permissions_check', $entity_type,
		array('container' => $container, 'user' => $user), $return);
}

/**
 * Create a new entity of a given type.
 *
 * @param string $type The type of the entity (site, user, object).
 * @param string $subtype The subtype of the entity.
 * @param int $owner_guid The GUID of the object's owner.
 * @param int $access_id The access control group to create the entity with.
 * @param int $site_guid The site to add this entity to. Leave as 0 (default) for the current site.
 * @return mixed The new entity's GUID, or false on failure
 */
function create_entity($type, $subtype, $owner_guid, $access_id, $site_guid = 0, $container_guid = 0) {
	global $CONFIG;

	$type = sanitise_string($type);
	$subtype = add_subtype($type, $subtype);
	$owner_guid = (int)$owner_guid;
	$access_id = (int)$access_id;
	$time = time();
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}
	$site_guid = (int) $site_guid;
	if ($container_guid == 0) {
		$container_guid = $owner_guid;
	}

	$user = get_loggedin_user();
	if (!can_write_to_container($user->guid, $owner_guid, $type)) {
		return false;
	}
	if ($owner_guid != $container_guid) {
		if (!can_write_to_container($user->guid, $container_guid, $type)) {
			return false;
		}
	}
	if ($type=="") {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:EntityTypeNotSet'));
	}

	return insert_data("INSERT into {$CONFIG->dbprefix}entities
		(type, subtype, owner_guid, site_guid, container_guid, access_id, time_created, time_updated, last_action) values
		('$type',$subtype, $owner_guid, $site_guid, $container_guid, $access_id, $time, $time, $time)");
}

/**
 * Retrieve the entity details for a specific GUID, returning it as a stdClass db row.
 *
 * You will only get an object if a) it exists, b) you have access to it.
 *
 * @param int $guid The GUID of the object to extract
 */
function get_entity_as_row($guid) {
	global $CONFIG;

	if (!$guid) {
		return false;
	}

	$guid = (int) $guid;
	$access = get_access_sql_suffix();

	return get_data_row("SELECT * from {$CONFIG->dbprefix}entities where guid=$guid and $access");
}

/**
 * Create an Elgg* object from a given entity row.
 */
function entity_row_to_elggstar($row) {
	if (!($row instanceof stdClass)) {
		return $row;
	}

	if ((!isset($row->guid)) || (!isset($row->subtype))) {
		return $row;
	}

	$new_entity = false;

	// Create a memcache cache if we can
	static $newentity_cache;
	if ((!$newentity_cache) && (is_memcache_available())) {
		$newentity_cache = new ElggMemcache('new_entity_cache');
	}
	if ($newentity_cache) {
		$new_entity = $newentity_cache->load($row->guid);
	}
	if ($new_entity) {
		return $new_entity;
	}

	// load class for entity if one is registered
	$classname = get_subtype_class_from_id($row->subtype);
	if ($classname!="") {
		if (class_exists($classname)) {
			$new_entity = new $classname($row);

			if (!($new_entity instanceof ElggEntity)) {
				throw new ClassException(sprintf(elgg_echo('ClassException:ClassnameNotClass'), $classname, 'ElggEntity'));
			}
		} else {
			error_log(sprintf(elgg_echo('ClassNotFoundException:MissingClass'), $classname));
		}
	}

	if (!$new_entity) {
		switch ($row->type) {
			case 'object' :
				$new_entity = new ElggObject($row);
				break;
			case 'user' :
				$new_entity = new ElggUser($row);
				break;
			case 'group' :
				$new_entity = new ElggGroup($row);
				break;
			case 'site' :
				$new_entity = new ElggSite($row);
				break;
			default:
				throw new InstallationException(sprintf(elgg_echo('InstallationException:TypeNotSupported'), $row->type));
		}
	}

	// Cache entity if we have a cache available
	if (($newentity_cache) && ($new_entity)) {
		$newentity_cache->save($new_entity->guid, $new_entity);
	}

	return $new_entity;
}

/**
 * Return the entity for a given guid as the correct object.
 * @param int $guid The GUID of the entity
 * @return a child of ElggEntity appropriate for the type.
 */
function get_entity($guid) {
	static $newentity_cache;
	$new_entity = false;

	if (!is_numeric($guid)) {
		return FALSE;
	}

	if ((!$newentity_cache) && (is_memcache_available())) {
		$newentity_cache = new ElggMemcache('new_entity_cache');
	}

	if ($newentity_cache) {
		$new_entity = $newentity_cache->load($guid);
	}

	if ($new_entity) {
		return $new_entity;
	}

	return entity_row_to_elggstar(get_entity_as_row($guid));
}


/**
 * Get all entities.  NB: Plural arguments can be written as
 * singular if only specifying a single element.  (e.g., 'type' => 'object'
 * vs 'types' => array('object')).
 *
 * @param array $options Array in format:
 *
 * 	types => NULL|STR entity type (SQL: type IN ('type1', 'type2') Joined with subtypes by AND...see below)
 *
 * 	subtypes => NULL|STR entity subtype (SQL: subtype IN ('subtype1', 'subtype2))
 *
 * 	type_subtype_pairs => NULL|ARR (array('type' => 'subtype')) (SQL: type = '$type' AND subtype = '$subtype') pairs
 *
 * 	owner_guids => NULL|INT entity guid
 *
 * 	container_guids => NULL|INT container_guid
 *
 * 	site_guids => NULL (current_site)|INT site_guid
 *
 * 	order_by => NULL (time_created desc)|STR SQL order by clause
 *
 * 	limit => NULL (10)|INT SQL limit clause
 *
 * 	offset => NULL (0)|INT SQL offset clause
 *
 * 	created_time_lower => NULL|INT Created time lower boundary in epoch time
 *
 * 	created_time_upper => NULL|INT Created time upper boundary in epoch time
 *
 * 	modified_time_lower => NULL|INT Modified time lower boundary in epoch time
 *
 * 	modified_time_upper => NULL|INT Modified time upper boundary in epoch time
 *
 * 	count => TRUE|FALSE return a count instead of entities
 *
 * 	wheres => array() Additional where clauses to AND together
 *
 * 	joins => array() Additional joins
 *	
 *	callback => string A callback function to pass each row through 
 *
 * @return 	if count, int
 * 			if not count, array or false if no entities
 * @since 1.7.0
 */
function elgg_get_entities(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'types'					=>	ELGG_ENTITIES_ANY_VALUE,
		'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,

		'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
		'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
		'site_guids'			=>	$CONFIG->site_guid,

		'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,

		'order_by' 				=>	'e.time_created desc',
		'group_by'				=>	ELGG_ENTITIES_ANY_VALUE,
		'limit'					=>	10,
		'offset'				=>	0,
		'count'					=>	FALSE,
		'selects'				=>	array(),
		'wheres'				=>	array(),
		'joins'					=>	array(),
		'callback'				=>	'entity_row_to_elggstar',
	);

	$options = array_merge($defaults, $options);

	// can't use helper function with type_subtype_pair because it's already an array...just need to merge it
	if (isset($options['type_subtype_pair'])) {
		if (isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = array_merge($options['type_subtype_pairs'], $options['type_subtype_pair']);
		} else {
			$options['type_subtype_pairs'] = $options['type_subtype_pair'];
		}
	}

	$singulars = array('type', 'subtype', 'owner_guid', 'container_guid', 'site_guid');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	// evaluate where clauses
	if (!is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	}

	$wheres = $options['wheres'];

	$wheres[] = elgg_get_entity_type_subtype_where_sql('e', $options['types'], $options['subtypes'], $options['type_subtype_pairs']);
	$wheres[] = elgg_get_entity_site_where_sql('e', $options['site_guids']);
	$wheres[] = elgg_get_entity_owner_where_sql('e', $options['owner_guids']);
	$wheres[] = elgg_get_entity_container_where_sql('e', $options['container_guids']);
	$wheres[] = elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === FALSE) {
			return FALSE;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// evaluate join clauses
	if (!is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	}

	// remove identical join clauses
	$joins = array_unique($options['joins']);

	foreach ($joins as $i => $join) {
		if ($join === FALSE) {
			return FALSE;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}

	// evalutate selects
	if ($options['selects']) {
		$selects = '';
		foreach ($options['selects'] as $select) {
			$selects = ", $select";
		}
	} else {
		$selects = '';
	}

	if (!$options['count']) {
		$query = "SELECT DISTINCT e.*{$selects} FROM {$CONFIG->dbprefix}entities e ";
	} else {
		$query = "SELECT count(DISTINCT e.guid) as total FROM {$CONFIG->dbprefix}entities e ";
	}

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$options['count']) {
		if ($options['group_by'] = sanitise_string($options['group_by'])) {
			$query .= " GROUP BY {$options['group_by']}";
		}

		if ($options['order_by'] = sanitise_string($options['order_by'])) {
			$query .= " ORDER BY {$options['order_by']}";
		}

		if ($options['limit']) {
			$limit = sanitise_int($options['limit']);
			$offset = sanitise_int($options['offset']);
			$query .= " LIMIT $offset, $limit";
		}

		$dt = get_data($query, $options['callback']);

		//@todo normalize this to array()
		return $dt;
	} else {
		$total = get_data_row($query);
		return (int)$total->total;
	}
}

/**
 * @deprecated 1.7.  Use elgg_get_entities().
 * @param $type
 * @param $subtype
 * @param $owner_guid
 * @param $order_by
 * @param $limit
 * @param $offset
 * @param $count
 * @param $site_guid
 * @param $container_guid
 * @param $timelower
 * @param $timeupper
 * @return unknown_type
 */
function get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0,
$count = false, $site_guid = 0, $container_guid = null, $timelower = 0, $timeupper = 0) {
	elgg_deprecated_notice('get_entities() was deprecated by elgg_get_entities().', 1.7);

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
			$owner_guid = NULL;
		}
	}

	$options = array();
	if ($type) {
		if (is_array($type)) {
			$options['types'] = $type;
		} else {
			$options['type'] = $type;
		}
	}

	if ($subtype) {
		if (is_array($subtype)) {
			$options['subtypes'] = $subtype;
		} else {
			$options['subtype'] = $subtype;
		}
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($order_by) {
		$options['order_by'] = $order_by;
	}

	// need to pass 0 for all option
	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($count) {
		$options['count'] = $count;
	}

	if ($site_guid) {
		$options['site_guids'] = $site_guid;
	}

	if ($container_guid) {
		$options['container_guids'] = $container_guid;
	}

	if ($timeupper) {
		$options['created_time_upper'] = $timeupper;
	}

	if ($timelower) {
		$options['created_time_lower'] = $timelower;
	}

	$r = elgg_get_entities($options);
	return $r;
}

/**
 * Returns type and subtype SQL appropriate for inclusion in an IN clause.
 *
 * @param string $table entity table prefix.
 * @param NULL|$types
 * @param NULL|array $subtypes
 * @param NULL|array $pairs
 * @return FALSE|string
 * @since 1.7.0
 */
function elgg_get_entity_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// subtype depends upon type.
	if ($subtypes && !$types) {
		elgg_log("Cannot set subtypes without type.", 'WARNING');
		return FALSE;
	}

	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	// these are the only valid types for entities in elgg as defined in the DB.
	$valid_types = array('object', 'user', 'group', 'site');

	// pairs override
	$wheres = array();
	if (!is_array($pairs)) {
		if (!is_array($types)) {
			$types = array($types);
		}

		if ($subtypes && !is_array($subtypes)) {
			$subtypes = array($subtypes);
		}

		// decrementer for valid types.  Return FALSE if no valid types
		$valid_types_count = count($types);
		$valid_subtypes_count = 0;
		// remove invalid types to get an accurate count of
		// valid types for the invalid subtype detection to use
		// below.
		// also grab the count of ALL subtypes on valid types to decrement later on
		// and check against.
		//
		// yes this is duplicating a foreach on $types.
		foreach ($types as $type) {
			if (!in_array($type, $valid_types)) {
				$valid_types_count--;
				unset ($types[array_search($type, $types)]);
			} else {
				// do the checking (and decrementing) in the subtype section.
				$valid_subtypes_count += count($subtypes);
			}
		}

		// return false if nothing is valid.
		if (!$valid_types_count) {
			return FALSE;
		}

		// subtypes are based upon types, so we need to look at each
		// type individually to get the right subtype id.
		foreach ($types as $type) {
			$subtype_ids = array();
			if ($subtypes) {
				foreach ($subtypes as $subtype) {
					// check that the subtype is valid (with ELGG_ENTITIES_NO_VALUE being a valid subtype)
					if (ELGG_ENTITIES_NO_VALUE === $subtype || $subtype_id = get_subtype_id($type, $subtype)) {
						$subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $subtype) ? ELGG_ENTITIES_NO_VALUE : $subtype_id;
					} else {
						$valid_subtypes_count--;
						elgg_log("Type-subtype $type:$subtype' does not exist!", 'WARNING');
						continue;
					}
				}

				// return false if we're all invalid subtypes in the only valid type
				if ($valid_subtypes_count <= 0) {
					return FALSE;
				}
			}

			if (is_array($subtype_ids) && count($subtype_ids)) {
				$subtype_ids_str = implode(',', $subtype_ids);
				$wheres[] = "({$table}.type = '$type' AND {$table}.subtype IN ($subtype_ids_str))";
			} else {
				$wheres[] = "({$table}.type = '$type')";
			}
		}
	} else {
		// using type/subtype pairs
		$valid_pairs_count = count($pairs);
		$valid_pairs_subtypes_count = 0;

		// same deal as above--we need to know how many valid types
		// and subtypes we have before hitting the subtype section.
		// also normalize the subtypes into arrays here.
		foreach ($pairs as $paired_type => $paired_subtypes) {
			if (!in_array($paired_type, $valid_types)) {
				$valid_pairs_count--;
				unset ($pairs[array_search($paired_type, $pairs)]);
			} else {
				if ($paired_subtypes && !is_array($paired_subtypes)) {
					$pairs[$paired_type] = array($paired_subtypes);
				}
				$valid_pairs_subtypes_count += count($paired_subtypes);
			}
		}

		if ($valid_pairs_count <= 0) {
			return FALSE;
		}
		foreach ($pairs as $paired_type => $paired_subtypes) {
			// this will always be an array because of line 2027, right?
			// no...some overly clever person can say pair => array('object' => null)
			if (is_array($paired_subtypes)) {
				$paired_subtype_ids = array();
				foreach ($paired_subtypes as $paired_subtype) {
					if (ELGG_ENTITIES_NO_VALUE === $paired_subtype || ($paired_subtype_id = get_subtype_id($paired_type, $paired_subtype))) {
						$paired_subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $paired_subtype) ? ELGG_ENTITIES_NO_VALUE : $paired_subtype_id;
					} else {
						$valid_pairs_subtypes_count--;
						elgg_log("Type-subtype $paired_type:$paired_subtype' does not exist!", 'WARNING');
						// return false if we're all invalid subtypes in the only valid type
						continue;
					}
				}

				// return false if there are no valid subtypes.
				if ($valid_pairs_subtypes_count <= 0) {
					return FALSE;
				}


				if ($paired_subtype_ids_str = implode(',', $paired_subtype_ids)) {
					$wheres[] = "({$table}.type = '$paired_type' AND {$table}.subtype IN ($paired_subtype_ids_str))";
				}
			} else {
				$wheres[] = "({$table}.type = '$paired_type')";
			}
		}
	}

	// pairs override the above.  return false if they don't exist.
	if (is_array($wheres) && count($wheres)) {
		$where = implode(' OR ', $wheres);
		return "($where)";
	}

	return '';
}



/**
 * Returns SQL for owner and containers.
 *
 * @todo Probably DRY up once things are settled.
 * @param str $table
 * @param NULL|array $owner_guids
 * @return FALSE|str
 * @since 1.7.0
 */
function elgg_get_entity_owner_where_sql($table, $owner_guids) {
	// short circuit if nothing requested
	// 0 is a valid owner_guid.
	if (!$owner_guids && $owner_guids !== 0) {
		return '';
	}

	// normalize and sanitise owners
	if (!is_array($owner_guids)) {
		$owner_guids = array($owner_guids);
	}

	$owner_guids_sanitised = array();
	foreach ($owner_guids as $owner_guid) {
		if (($owner_guid != sanitise_int($owner_guid))) {
			return FALSE;
		}
		$owner_guids_sanitised[] = $owner_guid;
	}

	$where = '';

	// implode(',', 0) returns 0.
	if (($owner_str = implode(',', $owner_guids_sanitised)) && ($owner_str !== FALSE) && ($owner_str !== '')) {
		$where = "({$table}.owner_guid IN ($owner_str))";
	}

	return $where;
}

/**
 * Returns SQL for containers.
 *
 * @param string $table entity table prefix
 * @param NULL|array $container_guids
 * @return FALSE|string
 * @since 1.7.0
 */
function elgg_get_entity_container_where_sql($table, $container_guids) {
	// short circuit if nothing is requested.
	// 0 is a valid container_guid.
	if (!$container_guids && $container_guids !== 0) {
		return '';
	}

	// normalize and sanitise containers
	if (!is_array($container_guids)) {
		$container_guids = array($container_guids);
	}

	$container_guids_sanitised = array();
	foreach ($container_guids as $container_guid) {
		if (($container_guid != sanitise_int($container_guid))) {
			return FALSE;
		}
		$container_guids_sanitised[] = $container_guid;
	}

	$where = '';

	// implode(',', 0) returns 0.
	if (FALSE !== $container_str = implode(',', $container_guids_sanitised)) {
		$where = "({$table}.container_guid IN ($container_str))";
	}

	return $where;
}

/**
 * Returns SQL where clause for entity time limits.
 *
 * @param string $table Prefix for entity table name.
 * @param NULL|int $time_created_upper
 * @param NULL|int $time_created_lower
 * @param NULL|int $time_updated_upper
 * @param NULL|int $time_updated_lower
 *
 * @return FALSE|str FALSE on fail, string on success.
 * @since 1.7.0
 */
function elgg_get_entity_time_where_sql($table, $time_created_upper = NULL, $time_created_lower = NULL,
	$time_updated_upper = NULL, $time_updated_lower = NULL) {

	$wheres = array();

	// exploit PHP's loose typing (quack) to check that they are INTs and not str cast to 0
	if ($time_created_upper && $time_created_upper == sanitise_int($time_created_upper)) {
		$wheres[] = "{$table}.time_created <= $time_created_upper";
	}

	if ($time_created_lower && $time_created_lower == sanitise_int($time_created_lower)) {
		$wheres[] = "{$table}.time_created >= $time_created_lower";
	}

	if ($time_updated_upper && $time_updated_upper == sanitise_int($time_updated_upper)) {
		$wheres[] = "{$table}.time_updated <= $time_updated_upper";
	}

	if ($time_updated_lower && $time_updated_lower == sanitise_int($time_updated_lower)) {
		$wheres[] = "{$table}.time_updated >= $time_updated_lower";
	}

	if (is_array($wheres) && count($wheres) > 0) {
		$where_str = implode(' AND ', $wheres);
		return "($where_str)";
	}

	return '';
}

/**
 * Gets SQL for site entities
 *
 * @param string $table entity table name
 * @param NULL|array $site_guids
 * @return FALSE|string
 * @since 1.7.0
 */
function elgg_get_entity_site_where_sql($table, $site_guids) {
	// short circuit if nothing requested
	if (!$site_guids) {
		return '';
	}

	if (!is_array($site_guids)) {
		$site_guids = array($site_guids);
	}

	$site_guids_sanitised = array();
	foreach ($site_guids as $site_guid) {
		if (!$site_guid || ($site_guid != sanitise_int($site_guid))) {
			return FALSE;
		}
		$site_guids_sanitised[] = $site_guid;
	}

	if ($site_guids_str = implode(',', $site_guids_sanitised)) {
		return "({$table}.site_guid IN ($site_guids_str))";
	}

	return '';
}

/**
 * Returns a viewable list of entities
 *
 * @see elgg_view_entity_list
 *
 * @param array $options Any elgg_get_entity() options plus:
 *
 * 	full_view => BOOL Display full view entities
 *
 * 	view_type_toggle => BOOL Display gallery / list switch
 *
 * 	pagination => BOOL Display pagination links
 *
 * @return str
 * @since 1.7.0
 */
function elgg_list_entities($options) {
	$defaults = array(
		'offset' => (int) max(get_input('offset', 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'full_view' => TRUE,
		'view_type_toggle' => FALSE,
		'pagination' => TRUE
	);
	$options = array_merge($defaults, $options);

	$count = elgg_get_entities(array_merge(array('count' => TRUE), $options));
	$entities = elgg_get_entities($options);

	return elgg_view_entity_list($entities, $count, $options['offset'],
		$options['limit'], $options['full_view'], $options['view_type_toggle'], $options['pagination']);
}

/**
 * @deprecated 1.7.  Use elgg_list_entities().
 * @param $type
 * @param $subtype
 * @param $owner_guid
 * @param $limit
 * @param $fullview
 * @param $viewtypetoggle
 * @param $pagination
 * @return unknown_type
 */
function list_entities($type= "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $pagination = true) {
	elgg_deprecated_notice('list_entities() was deprecated by elgg_list_entities()!', 1.7);

	$options = array();

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid) {
		$options['container_guids'] = $owner_guid;
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset = sanitise_int(get_input('offset', null))) {
		$options['offset'] = $offset;
	}

	$options['full_view'] = $fullview;
	$options['view_type_toggle'] = $viewtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities($options);
}

/**
 * Returns a viewable list of entities contained in a number of groups.
 *
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 * @param int $container_guid The GUID of the containing group
 * @param int $limit The number of entities to display per page (default: 10)
 * @param true|false $fullview Whether or not to display the full view (default: true)
 * @param true|false $viewtypetoggle Whether or not to allow gallery view (default: true)
 * @param true|false $pagination Whether to display pagination (default: true)
 * @return string A viewable list of entities
 */
function list_entities_groups($subtype = "", $owner_guid = 0, $container_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = true, $pagination = true) {
	$offset = (int) get_input('offset');
	$count = get_objects_in_group($container_guid, $subtype, $owner_guid, 0, "", $limit, $offset, true);
	$entities = get_objects_in_group($container_guid, $subtype, $owner_guid, 0, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $viewtypetoggle, $pagination);
}

/**
 * Returns a list of months containing content specified by the parameters
 *
 * @param string $type The type of entity
 * @param string $subtype The subtype of entity
 * @param int $container_guid The container GUID that the entinties belong to
 * @param int $site_guid The site GUID
 * @param str order_by SQL order by clause
 * @return array|false Either an array of timestamps, or false on failure
 */
function get_entity_dates($type = '', $subtype = '', $container_guid = 0, $site_guid = 0, $order_by = 'time_created') {
	global $CONFIG;

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}
	$where = array();

	if ($type != "") {
		$type = sanitise_string($type);
		$where[] = "type='$type'";
	}

	if (is_array($subtype)) {
		$tempwhere = "";
		if (sizeof($subtype)) {
			foreach($subtype as $typekey => $subtypearray) {
				foreach($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						if (!$subtypeval = (int) get_subtype_id($typekey, $subtypeval))
							return false;
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) $tempwhere .= " or ";
					$tempwhere .= "(type = '{$typekey}' and subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}
	} else {
		if ($subtype) {
			if (!$subtype_id = get_subtype_id($type, $subtype)) {
				return FALSE;
			} else {
				$where[] = "subtype=$subtype_id";
			}
		}
	}

	if ($container_guid !== 0) {
		if (is_array($container_guid)) {
			foreach($container_guid as $key => $val) {
				$container_guid[$key] = (int) $val;
			}
			$where[] = "container_guid in (" . implode(",",$container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "container_guid = {$container_guid}";
		}
	}

	if ($site_guid > 0) {
		$where[] = "site_guid = {$site_guid}";
	}

	$where[] = get_access_sql_suffix();

	$sql = "SELECT DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(time_created)) AS yearmonth
		FROM {$CONFIG->dbprefix}entities where ";

	foreach ($where as $w) {
		$sql .= " $w and ";
	}

	$sql .= "1=1 ORDER BY $order_by";
	if ($result = get_data($sql)) {
		$endresult = array();
		foreach($result as $res) {
			$endresult[] = $res->yearmonth;
		}
		return $endresult;
	}
	return false;
}

/**
 * Disable an entity but not delete it.
 *
 * @param int $guid The guid
 * @param string $reason Optional reason
 */
function disable_entity($guid, $reason = "", $recursive = true) {
	global $CONFIG;

	$guid = (int)$guid;
	$reason = sanitise_string($reason);

	if ($entity = get_entity($guid)) {
		if (trigger_elgg_event('disable',$entity->type,$entity)) {
			if ($entity->canEdit()) {
				if ($reason) {
					create_metadata($guid, 'disable_reason', $reason, '', 0, ACCESS_PUBLIC);
				}

				if ($recursive) {
					// Temporary token overriding access controls
					// @todo Do this better.
					static $__RECURSIVE_DELETE_TOKEN;
					// Make it slightly harder to guess
					$__RECURSIVE_DELETE_TOKEN = md5(get_loggedin_userid());

					$sub_entities = get_data("SELECT * from {$CONFIG->dbprefix}entities
						WHERE container_guid=$guid
						or owner_guid=$guid
						or site_guid=$guid", 'entity_row_to_elggstar');

					if ($sub_entities) {
						foreach ($sub_entities as $e) {
							$e->disable($reason);
						}
					}

					$__RECURSIVE_DELETE_TOKEN = null;
				}

				$res = update_data("UPDATE {$CONFIG->dbprefix}entities
					set enabled='no'
					where guid={$guid}");

				return $res;
			}
		}
	}
	return false;
}

/**
 * Enable an entity again.
 *
 * @param int $guid
 */
function enable_entity($guid) {
	global $CONFIG;

	$guid = (int)$guid;

	// Override access only visible entities
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	if ($entity = get_entity($guid)) {
		if (trigger_elgg_event('enable',$entity->type,$entity)) {
			if ($entity->canEdit()) {

				access_show_hidden_entities($access_status);

				$result = update_data("UPDATE {$CONFIG->dbprefix}entities
					set enabled='yes'
					where guid={$guid}");
				$entity->clearMetaData('disable_reason');

				return $result;
			}
		}
	}

	access_show_hidden_entities($access_status);
	return false;
}

/**
 * Delete a given entity.
 *
 * @param int $guid
 * @param bool $recursive If true (default) then all entities which are owned or contained by $guid will also be deleted.
 * Note: this bypasses ownership of sub items.
 */
function delete_entity($guid, $recursive = true) {
	global $CONFIG, $ENTITY_CACHE;

	$guid = (int)$guid;
	if ($entity = get_entity($guid)) {
		if (trigger_elgg_event('delete', $entity->type, $entity)) {
			if ($entity->canEdit()) {

				// delete cache
				if (isset($ENTITY_CACHE[$guid])) {
					invalidate_cache_for_entity($guid);
				}

				// Delete contained owned and otherwise releated objects (depth first)
				if ($recursive) {
					// Temporary token overriding access controls
					// @todo Do this better.
					static $__RECURSIVE_DELETE_TOKEN;
					// Make it slightly harder to guess
					$__RECURSIVE_DELETE_TOKEN = md5(get_loggedin_userid());

					$entity_disable_override = access_get_show_hidden_status();
					access_show_hidden_entities(true);
					$sub_entities = get_data("SELECT * from {$CONFIG->dbprefix}entities
						WHERE container_guid=$guid
							or owner_guid=$guid
							or site_guid=$guid", 'entity_row_to_elggstar');
					if ($sub_entities) {
						foreach ($sub_entities as $e) {
							$e->delete();
						}
					}

					access_show_hidden_entities($entity_disable_override);
					$__RECURSIVE_DELETE_TOKEN = null;
				}

				// Now delete the entity itself
				$entity->clearMetadata();
				$entity->clearAnnotations();
				$entity->clearRelationships();
				remove_from_river_by_subject($guid);
				remove_from_river_by_object($guid);
				remove_all_private_settings($guid);
				$res = delete_data("DELETE from {$CONFIG->dbprefix}entities where guid={$guid}");
				if ($res) {
					$sub_table = "";

					// Where appropriate delete the sub table
					switch ($entity->type) {
						case 'object' :
							$sub_table = $CONFIG->dbprefix . 'objects_entity';
							break;
						case 'user' :
							$sub_table = $CONFIG->dbprefix . 'users_entity';
							break;
						case 'group' :
							$sub_table = $CONFIG->dbprefix . 'groups_entity';
							break;
						case 'site' :
							$sub_table = $CONFIG->dbprefix . 'sites_entity';
							break;
					}

					if ($sub_table) {
						delete_data("DELETE from $sub_table where guid={$guid}");
					}
				}

				return $res;
			}
		}
	}
	return false;

}

/**
 * Delete multiple entities that match a given query.
 * This function itterates through and calls delete_entity on each one, this is somewhat inefficient but lets
 * the 'delete' even be called for each entity.
 *
 * @deprecated 1.7. This is a dangerous function as it defaults to deleting everything.
 * @param string $type The type of entity (eg "user", "object" etc)
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 */
function delete_entities($type = "", $subtype = "", $owner_guid = 0) {
	elgg_deprecated_notice('delete_entities() was deprecated because no one should use it.', 1.7);
	return false;
}

/**
 * A plugin hook to get certain volitile (generated on the fly) attributes about an entity in order to export them.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params The parameters, passed 'guid' and 'varname'
 * @return unknown
 */
function volatile_data_export_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$guid = (int)$params['guid'];
	$variable_name = sanitise_string($params['varname']);

	if (($hook == 'volatile') && ($entity_type == 'metadata')) {
		if (($guid) && ($variable_name)) {
			switch ($variable_name) {
				case 'renderedentity' :
					elgg_set_viewtype('default');
					$view = elgg_view_entity(get_entity($guid));
					elgg_set_viewtype();

					$tmp = new ElggMetadata();
					$tmp->type = 'volatile';
					$tmp->name = 'renderedentity';
					$tmp->value = $view;
					$tmp->entity_guid = $guid;

					return $tmp;

				break;
			}
		}
	}
}

/**
 * Handler called by trigger_plugin_hook on the "export" event.
 */
function export_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$guid = (int)$params['guid'];

	// Get the entity
	$entity = get_entity($guid);
	if (!($entity instanceof ElggEntity)) {
		throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
	}

	$export = $entity->export();

	if (is_array($export)) {
		foreach ($export as $e) {
			$returnvalue[] = $e;
		}
	} else {
		$returnvalue[] = $export;
	}

	return $returnvalue;
}

/**
 * Utility function used by import_entity_plugin_hook() to process an ODDEntity into an unsaved ElggEntity.
 *
 * @param ODDEntity $element The OpenDD element
 * @return ElggEntity the unsaved entity which should be populated by items.
 */
function oddentity_to_elggentity(ODDEntity $element) {
	$class = $element->getAttribute('class');
	$subclass = $element->getAttribute('subclass');

	// See if we already have imported this uuid
	$tmp = get_entity_from_uuid($element->getAttribute('uuid'));

	if (!$tmp) {
		// Construct new class with owner from session
		$classname = get_subtype_class($class, $subclass);
		if ($classname!="") {
			if (class_exists($classname)) {
				$tmp = new $classname();

				if (!($tmp instanceof ElggEntity)) {
					throw new ClassException(sprintf(elgg_echo('ClassException:ClassnameNotClass', $classname, get_class())));
				}
			}
			else
				error_log(sprintf(elgg_echo('ClassNotFoundException:MissingClass'), $classname));
		}
		else {
			switch ($class) {
				case 'object' :
					$tmp = new ElggObject($row);
					break;
				case 'user' :
					$tmp = new ElggUser($row);
					break;
				case 'group' :
					$tmp = new ElggGroup($row);
					break;
				case 'site' :
					$tmp = new ElggSite($row);
					break;
				default:
					throw new InstallationException(sprintf(elgg_echo('InstallationException:TypeNotSupported'), $class));
			}
		}
	}

	if ($tmp) {
		if (!$tmp->import($element)) {
			throw new ImportException(sprintf(elgg_echo('ImportException:ImportFailed'), $element->getAttribute('uuid')));
		}

		return $tmp;
	}

	return NULL;
}

/**
 * Import an entity.
 * This function checks the passed XML doc (as array) to see if it is a user, if so it constructs a new
 * elgg user and returns "true" to inform the importer that it's been handled.
 */
function import_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDEntity) {
		$tmp = oddentity_to_elggentity($element);

		if ($tmp) {
			// Make sure its saved
			if (!$tmp->save()) {
				throw new ImportException(sprintf(elgg_echo('ImportException:ProblemSaving'), $element->getAttribute('uuid')));
			}

			// Belts and braces
			if (!$tmp->guid) {
				throw new ImportException(elgg_echo('ImportException:NoGUID'));
			}

			// We have saved, so now tag
			add_uuid_to_guid($tmp->guid, $element->getAttribute('uuid'));

			return $tmp;
		}
	}
}

/**
 * Determines whether or not the specified user can edit the specified entity.
 *
 * This is extendible by registering a plugin hook taking in the parameters 'entity' and 'user',
 * which are the entity and user entities respectively
 *
 * @see register_plugin_hook
 *
 * @param int $entity_guid The GUID of the entity
 * @param int $user_guid The GUID of the user
 * @return true|false Whether the specified user can edit the specified entity.
 */
function can_edit_entity($entity_guid, $user_guid = 0) {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if ($entity = get_entity($entity_guid)) {
		$return = false;

		// Test user if possible - should default to false unless a plugin hook says otherwise
		if ($user) {
			if ($entity->getOwner() == $user->getGUID()) {
				$return = true;
			}
			if ($entity->container_guid == $user->getGUID()) {
				$return = true;
			}
			if ($entity->type == "user" && $entity->getGUID() == $user->getGUID()) {
				$return = true;
			}
			if ($container_entity = get_entity($entity->container_guid)) {
				if ($container_entity->canEdit($user->getGUID())) {
					$return = true;
				}
			}
		}

		return trigger_plugin_hook('permissions_check', $entity->type,
			array('entity' => $entity, 'user' => $user), $return);

	} else {
		return false;
	}
}

/**
 * Determines whether or not the specified user can edit metadata on the specified entity.
 *
 * This is extendible by registering a plugin hook taking in the parameters 'entity' and 'user',
 * which are the entity and user entities respectively
 *
 * @see register_plugin_hook
 *
 * @param int $entity_guid The GUID of the entity
 * @param int $user_guid The GUID of the user
 * @param ElggMetadata $metadata The metadata to specifically check (if any; default null)
 * @return true|false Whether the specified user can edit the specified entity.
 */
function can_edit_entity_metadata($entity_guid, $user_guid = 0, $metadata = null) {
	if ($entity = get_entity($entity_guid)) {

		$return = null;

		if ($metadata->owner_guid == 0) {
			$return = true;
		}
		if (is_null($return)) {
			$return = can_edit_entity($entity_guid, $user_guid);
		}

		$user = get_entity($user_guid);
		$return = trigger_plugin_hook('permissions_check:metadata',$entity->type,array('entity' => $entity, 'user' => $user, 'metadata' => $metadata),$return);
		return $return;
	} else {
		return false;
	}
}


/**
 * Get the icon for an entity
 *
 * @param ElggEntity $entity The entity (passed an entity rather than a guid to handle non-created entities)
 * @param string $size
 */
function get_entity_icon_url(ElggEntity $entity, $size = 'medium') {
	global $CONFIG;

	$size = sanitise_string($size);
	switch (strtolower($size)) {
		case 'master':
			$size = 'master';
			break;

		case 'large' :
			$size = 'large';
			break;

		case 'topbar' :
			$size = 'topbar';
			break;

		case 'tiny' :
			$size = 'tiny';
			break;

		case 'small' :
			$size = 'small';
			break;

		case 'medium' :
		default:
			$size = 'medium';
	}

	$url = false;

	$viewtype = elgg_get_viewtype();

	// Step one, see if anyone knows how to render this in the current view
	$url = trigger_plugin_hook('entity:icon:url', $entity->getType(), array('entity' => $entity, 'viewtype' => $viewtype, 'size' => $size), $url);

	// Fail, so use default
	if (!$url) {
		$type = $entity->getType();
		$subtype = $entity->getSubtype();

		if (!empty($subtype)) {
			$overrideurl = elgg_view("icon/{$type}/{$subtype}/{$size}",array('entity' => $entity));
			if (!empty($overrideurl)) {
				return $overrideurl;
			}
		}

		$overrideurl = elgg_view("icon/{$type}/default/{$size}",array('entity' => $entity));
		if (!empty($overrideurl)) {
			return $overrideurl;
		}

		$url = $CONFIG->url . "_graphics/icons/default/$size.png";
	}

	return $url;
}

/**
 * Gets the URL for an entity, given a particular GUID
 *
 * @param int $entity_guid The GUID of the entity
 * @return string The URL of the entity
 */
function get_entity_url($entity_guid) {
	global $CONFIG;

	if ($entity = get_entity($entity_guid)) {
		$url = "";

		if (isset($CONFIG->entity_url_handler[$entity->getType()][$entity->getSubType()])) {
			$function =  $CONFIG->entity_url_handler[$entity->getType()][$entity->getSubType()];
			if (is_callable($function)) {
				$url = $function($entity);
			}
		} elseif (isset($CONFIG->entity_url_handler[$entity->getType()]['all'])) {
			$function =  $CONFIG->entity_url_handler[$entity->getType()]['all'];
			if (is_callable($function)) {
				$url = $function($entity);
			}
		} elseif (isset($CONFIG->entity_url_handler['all']['all'])) {
			$function =  $CONFIG->entity_url_handler['all']['all'];
			if (is_callable($function)) {
				$url = $function($entity);
			}
		}

		if ($url == "") {
			$url = $CONFIG->url . "pg/view/" . $entity_guid;
		}
		return $url;

	}

	return false;
}

/**
 * Sets the URL handler for a particular entity type and subtype
 *
 * @param string $function_name The function to register
 * @param string $entity_type The entity type
 * @param string $entity_subtype The entity subtype
 * @return true|false Depending on success
 */
function register_entity_url_handler($function_name, $entity_type = "all", $entity_subtype = "all") {
	global $CONFIG;

	if (!is_callable($function_name)) {
		return false;
	}

	if (!isset($CONFIG->entity_url_handler)) {
		$CONFIG->entity_url_handler = array();
	}

	if (!isset($CONFIG->entity_url_handler[$entity_type])) {
		$CONFIG->entity_url_handler[$entity_type] = array();
	}

	$CONFIG->entity_url_handler[$entity_type][$entity_subtype] = $function_name;

	return true;
}

/**
 * Default Icon URL handler for entities.
 * This will attempt to find a default entity for the current view and return a url. This is registered at
 * a low priority so that other handlers will pick it up first.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function default_entity_icon_hook($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;

	if ((!$returnvalue) && ($hook == 'entity:icon:url')) {
		$entity = $params['entity'];
		$type = $entity->type;
		$subtype = get_subtype_from_id($entity->subtype);
		$viewtype = $params['viewtype'];
		$size = $params['size'];

		$url = "views/$viewtype/graphics/icons/$type/$subtype/$size.png";

		if (!@file_exists($CONFIG->path . $url)) {
			$url = "views/$viewtype/graphics/icons/$type/default/$size.png";
		}

		if(!@file_exists($CONFIG->path . $url)) {
			$url = "views/$viewtype/graphics/icons/default/$size.png";
		}

		if (@file_exists($CONFIG->path . $url)) {
			return $CONFIG->url . $url;
		}
	}
}

/**
 * Registers and entity type and subtype to return in search and other places.
 * A description in the elgg_echo languages file of the form item:type:subtype
 * is also expected.
 *
 * @param string $type The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 * @return true|false Depending on success
 */
function register_entity_type($type, $subtype=null) {
	global $CONFIG;

	$type = strtolower($type);
	if (!in_array($type, array('object','site','group','user'))) {
		return false;
	}

	if (!isset($CONFIG->registered_entities)) {
		$CONFIG->registered_entities = array();
	}

	if (!isset($CONFIG->registered_entities[$type])) {
		$CONFIG->registered_entities[$type] = array();
	}

	if ($subtype) {
		$CONFIG->registered_entities[$type][] = $subtype;
	}

	return true;
}

/**
 * Returns registered entity types and subtypes
 *
 * @see register_entity_type
 *
 * @param string $type The type of entity (object, site, user, group) or blank for all
 * @return array|false Depending on whether entities have been registered
 */
function get_registered_entity_types($type = null) {
	global $CONFIG;

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}
	if ($type) {
		$type = strtolower($type);
	}
	if (!empty($type) && empty($CONFIG->registered_entities[$type])) {
		return false;
	}

	if (empty($type)) {
		return $CONFIG->registered_entities;
	}

	return $CONFIG->registered_entities[$type];
}

/**
 * Determines whether or not the specified entity type and subtype have been registered in the system
 *
 * @param string $type The type of entity (object, site, user, group)
 * @param string $subtype The subtype (may be blank)
 * @return true|false Depending on whether or not the type has been registered
 */
function is_registered_entity_type($type, $subtype=null) {
	global $CONFIG;

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}

	$type = strtolower($type);

	// @todo registering a subtype implicitly registers the type.
	// see #2684
	if (!isset($CONFIG->registered_entities[$type])) {
		return false;
	}

	if ($subtype && !in_array($subtype, $CONFIG->registered_entities[$type])) {
		return false;
	}

	return true;
}

/**
 * Page handler for generic entities view system
 *
 * @param array $page Page elements from pain page handler
 */
function entities_page_handler($page) {
	if (isset($page[0])) {
		global $CONFIG;
		set_input('guid',$page[0]);
		include($CONFIG->path . "entities/index.php");
	}
}

/**
 * @deprecated 1.7.  Use elgg_list_registered_entities().
 * @param $owner_guid
 * @param $limit
 * @param $fullview
 * @param $viewtypetoggle
 * @param $allowedtypes
 * @return unknown_type
 */
function list_registered_entities($owner_guid = 0, $limit = 10, $fullview = true, $viewtypetoggle = false, $allowedtypes = true) {
	elgg_deprecated_notice('list_registered_entities() was deprecated by elgg_list_registered_entities().', 1.7);

	$options = array();

	// don't want to send anything if not being used.
	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($allowedtypes) {
		$options['allowed_types'] = $allowedtypes;
	}

	// need to send because might be BOOL
	$options['full_view'] = $fullview;
	$options['view_type_toggle'] = $viewtypetoggle;

	$options['offset'] = get_input('offset', 0);

	return elgg_list_registered_entities($options);
}

/**
 * Returns a viewable list of entities based on the registered types.
 *
 * @see elgg_view_entity_list
 *
 * @param array $options Any elgg_get_entity() options plus:
 *
 * 	full_view => BOOL Display full view entities
 *
 * 	view_type_toggle => BOOL Display gallery / list switch
 *
 * 	allowed_types => TRUE|ARRAY True to show all types or an array of valid types.
 *
 * 	pagination => BOOL Display pagination links
 *
 * @return string A viewable list of entities
 * @since 1.7.0
 */
function elgg_list_registered_entities($options) {
	$defaults = array(
		'full_view' => TRUE,
		'allowed_types' => TRUE,
		'view_type_toggle' => FALSE,
		'pagination' => TRUE,
		'offset' => 0
	);

	$options = array_merge($defaults, $options);
	$typearray = array();

	if ($object_types = get_registered_entity_types()) {
		foreach($object_types as $object_type => $subtype_array) {
			if (in_array($object_type, $options['allowed_types']) || $options['allowed_types'] === TRUE) {
				$typearray[$object_type] = array();

				if (is_array($subtype_array) && count($subtype_array)) {
					foreach ($subtype_array as $subtype) {
						$typearray[$object_type][] = $subtype;
					}
				}
			}
		}
	}

	$options['type_subtype_pairs'] = $typearray;

	$count = elgg_get_entities(array_merge(array('count' => TRUE), $options));
	$entities = elgg_get_entities($options);

	return elgg_view_entity_list($entities, $count, $options['offset'],
		$options['limit'], $options['full_view'], $options['view_type_toggle'], $options['pagination']);
}

/**
 * Get entities based on their private data, in a similar way to metadata.
 *
 * @param string $name The name of the setting
 * @param string $value The value of the setting
 * @param string $type The type of entity (eg "user", "object" etc)
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 * @param string $order_by The field to order by; by default, time_created desc
 * @param int $limit The number of entities to return; 10 by default
 * @param int $offset The indexing offset, 0 by default
 * @param boolean $count Set to true to get a count rather than the entities themselves (limits and offsets don't apply in this context). Defaults to false.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param int|array $container_guid The container or containers to get entities from (default: all containers).
 * @return array A list of entities.
 */
function get_entities_from_private_setting($name = "", $value = "", $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null) {
	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	$name = sanitise_string($name);
	$value = sanitise_string($value);

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$where = array();

	if (is_array($type)) {
		$tempwhere = "";
		if (sizeof($type)) {
			foreach($type as $typekey => $subtypearray) {
				foreach($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						if (!$subtypeval = (int) get_subtype_id($typekey, $subtypeval)) {
							return false;
						}
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) $tempwhere .= " or ";
					$tempwhere .= "(e.type = '{$typekey}' and e.subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}
	} else {
		$type = sanitise_string($type);
		if ($subtype AND !$subtype = get_subtype_id($type, $subtype)) {
			return false;
		}

		if ($type != "") {
			$where[] = "e.type='$type'";
		}
		if ($subtype!=="") {
			$where[] = "e.subtype=$subtype";
		}
	}

	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_array = array($owner_guid);
			$owner_guid = (int) $owner_guid;
		//	$where[] = "owner_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
			// Cast every element to the owner_guid array to int
		//	$owner_guid = array_map("sanitise_int", $owner_guid);
		//	$owner_guid = implode(",",$owner_guid);
		//	$where[] = "owner_guid in ({$owner_guid})";
		}
		if (is_null($container_guid)) {
			$container_guid = $owner_array;
		}
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if (!is_null($container_guid)) {
		if (is_array($container_guid)) {
			foreach($container_guid as $key => $val) $container_guid[$key] = (int) $val;
			$where[] = "e.container_guid in (" . implode(",",$container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "e.container_guid = {$container_guid}";
		}
	}

	if ($name!="") {
		$where[] = "s.name = '$name'";
	}

	if ($value!="") {
		$where[] = "s.value='$value'";
	}

	if (!$count) {
		$query = "SELECT distinct e.*
			from {$CONFIG->dbprefix}entities e
			JOIN {$CONFIG->dbprefix}private_settings s ON e.guid=s.entity_guid where ";
	} else {
		$query = "SELECT count(distinct e.guid) as total
			from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}private_settings s
			ON e.guid=s.entity_guid where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}
	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$count) {
		$query .= " order by $order_by";
		if ($limit) {
			// Add order and limit
			$query .= " limit $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");
		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * Get entities based on their private data by multiple keys, in a similar way to metadata.
 *
 * @param string $name The name of the setting
 * @param string $value The value of the setting
 * @param string|array $type The type of entity (eg "user", "object" etc) or array(type1 => array('subtype1', ...'subtypeN'), ...)
 * @param string $subtype The arbitrary subtype of the entity
 * @param int $owner_guid The GUID of the owning user
 * @param string $order_by The field to order by; by default, time_created desc
 * @param int $limit The number of entities to return; 10 by default
 * @param int $offset The indexing offset, 0 by default
 * @param boolean $count Set to true to get a count rather than the entities themselves (limits and offsets don't apply in this context). Defaults to false.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param int|array $container_guid The container or containers to get entities from (default: all containers).
 * @return array A list of entities.
 */
function get_entities_from_private_setting_multi(array $name, $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null) {
	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$where = array();

	if (is_array($type)) {
		$tempwhere = "";
		if (sizeof($type)) {
			foreach($type as $typekey => $subtypearray) {
				foreach($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						if (!$subtypeval = (int) get_subtype_id($typekey, $subtypeval)) {
							return false;
						}
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) $tempwhere .= " or ";
					$tempwhere .= "(e.type = '{$typekey}' and e.subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}

	} else {
		$type = sanitise_string($type);
		if ($subtype AND !$subtype = get_subtype_id($type, $subtype)) {
			return false;
		}

		if ($type != "") {
			$where[] = "e.type='$type'";
		}

		if ($subtype!=="") {
			$where[] = "e.subtype=$subtype";
		}
	}

	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_array = array($owner_guid);
			$owner_guid = (int) $owner_guid;
		//	$where[] = "owner_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
			// Cast every element to the owner_guid array to int
		//	$owner_guid = array_map("sanitise_int", $owner_guid);
		//	$owner_guid = implode(",",$owner_guid);
		//	$where[] = "owner_guid in ({$owner_guid})";
		}
		if (is_null($container_guid)) {
			$container_guid = $owner_array;
		}
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if (!is_null($container_guid)) {
		if (is_array($container_guid)) {
			foreach($container_guid as $key => $val) $container_guid[$key] = (int) $val;
			$where[] = "e.container_guid in (" . implode(",",$container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "e.container_guid = {$container_guid}";
		}
	}

	if ($name) {
		$s_join = "";
		$i = 1;
		foreach ($name as $k => $n) {
			$k = sanitise_string($k);
			$n = sanitise_string($n);
			$s_join .= " JOIN {$CONFIG->dbprefix}private_settings s$i ON e.guid=s$i.entity_guid";
			$where[] = "s$i.name = '$k'";
			$where[] = "s$i.value = '$n'";
			$i++;
		}
	}

	if (!$count) {
		$query = "SELECT distinct e.* from {$CONFIG->dbprefix}entities e $s_join where ";
	} else {
		$query = "SELECT count(distinct e.guid) as total
		from {$CONFIG->dbprefix}entities e $s_join where ";
	}

	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');

	if (!$count) {
		$query .= " order by $order_by";
		// Add order and limit
		if ($limit) {
			$query .= " limit $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");
		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * Gets a private setting for an entity.
 *
 * @param int $entity_guid The entity GUID
 * @param string $name The name of the setting
 * @return mixed The setting value, or false on failure
 */
function get_private_setting($entity_guid, $name) {
	global $CONFIG;
	$entity_guid = (int) $entity_guid;
	$name = sanitise_string($name);

	if ($setting = get_data_row("SELECT value from {$CONFIG->dbprefix}private_settings where name = '{$name}' and entity_guid = {$entity_guid}")) {
		return $setting->value;
	}
	return false;
}

/**
 * Return an array of all private settings for a given
 *
 * @param int $entity_guid The entity GUID
 */
function get_all_private_settings($entity_guid) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;

	$result = get_data("SELECT * from {$CONFIG->dbprefix}private_settings where entity_guid = {$entity_guid}");
	if ($result) {
		$return = array();
		foreach ($result as $r) {
			$return[$r->name] = $r->value;
		}

		return $return;
	}

	return false;
}

/**
 * Sets a private setting for an entity.
 *
 * @param int $entity_guid The entity GUID
 * @param string $name The name of the setting
 * @param string $value The value of the setting
 * @return mixed The setting ID, or false on failure
 */
function set_private_setting($entity_guid, $name, $value) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;
	$name = sanitise_string($name);
	$value = sanitise_string($value);

	$result = insert_data("INSERT into {$CONFIG->dbprefix}private_settings
		(entity_guid, name, value) VALUES
		($entity_guid, '{$name}', '{$value}')
		ON DUPLICATE KEY UPDATE value='$value'");
	if ($result === 0) {
		return true;
	}
	return $result;
}

/**
 * Deletes a private setting for an entity.
 *
 * @param int $entity_guid The Entity GUID
 * @param string $name The name of the setting
 * @return true|false depending on success
 *
 */
function remove_private_setting($entity_guid, $name) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;
	$name = sanitise_string($name);

	return delete_data("DELETE from {$CONFIG->dbprefix}private_settings
		where name = '{$name}'
		and entity_guid = {$entity_guid}");
}

/**
 * Deletes all private settings for an entity.
 *
 * @param int $entity_guid The Entity GUID
 * @return true|false depending on success
 *
 */
function remove_all_private_settings($entity_guid) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;
	return delete_data("DELETE from {$CONFIG->dbprefix}private_settings
		where entity_guid = {$entity_guid}");
}

/*
 * Check the recurisve delete permissions token.
 *
 * @return bool
 */
function recursive_delete_permissions_check($hook, $entity_type, $returnvalue, $params) {
	static $__RECURSIVE_DELETE_TOKEN;

	$entity = $params['entity'];

	if ((isloggedin()) && ($__RECURSIVE_DELETE_TOKEN) && (strcmp($__RECURSIVE_DELETE_TOKEN, md5(get_loggedin_userid())))) {
		return true;
	}

	// consult next function
	return NULL;
}

/**
 * Garbage collect stub and fragments from any broken delete/create calls
 *
 * @param unknown_type $hook
 * @param unknown_type $user
 * @param unknown_type $returnvalue
 * @param unknown_type $tag
 */
function entities_gc($hook, $user, $returnvalue, $tag) {
	global $CONFIG;

	$tables = array ('sites_entity', 'objects_entity', 'groups_entity', 'users_entity');

	foreach ($tables as $table) {
		delete_data("DELETE from {$CONFIG->dbprefix}{$table}
			where guid NOT IN (SELECT guid from {$CONFIG->dbprefix}entities)");
	}
}

/**
 * Runs unit tests for the entities object.
 */
function entities_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/objects/entities.php';
	return $value;
}

/**
 * Entities init function; establishes the page handler
 *
 */
function entities_init() {
	register_page_handler('view','entities_page_handler');

	register_plugin_hook('unit_test', 'system', 'entities_test');

	// Allow a permission override for recursive entity deletion
	// @todo Can this be done better?
	register_plugin_hook('permissions_check','all','recursive_delete_permissions_check');
	register_plugin_hook('permissions_check:metadata','all','recursive_delete_permissions_check');

	register_plugin_hook('gc','system','entities_gc');
}

/** Register the import hook */
register_plugin_hook("import", "all", "import_entity_plugin_hook", 0);

/** Register the hook, ensuring entities are serialised first */
register_plugin_hook("export", "all", "export_entity_plugin_hook", 0);

/** Hook to get certain named bits of volatile data about an entity */
register_plugin_hook('volatile', 'metadata', 'volatile_data_export_plugin_hook');

/** Hook for rendering a default icon for entities */
register_plugin_hook('entity:icon:url', 'all', 'default_entity_icon_hook', 1000);

/** Register init system event **/
register_elgg_event_handler('init','system','entities_init');
