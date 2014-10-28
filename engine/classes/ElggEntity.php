<?php
/**
 * The parent class for all Elgg Entities.
 *
 * An ElggEntity is one of the basic data models in Elgg.  It is the primary
 * means of storing and retrieving data from the database.  An ElggEntity
 * represents one row of the entities table.
 *
 * The ElggEntity class handles CRUD operations for the entities table.
 * ElggEntity should always be extended by another class to handle CRUD
 * operations on the type-specific table.
 *
 * ElggEntity uses magic methods for get and set, so any property that isn't
 * declared will be assumed to be metadata and written to the database
 * as metadata on the object.  All children classes must declare which
 * properties are columns of the type table or they will be assumed
 * to be metadata.  See ElggObject::initializeAttributes() for examples.
 *
 * Core supports 4 types of entities: ElggObject, ElggUser, ElggGroup, and
 * ElggSite.
 *
 * @tip Plugin authors will want to extend the ElggObject class, not this class.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Entities
 *
 * @property       string $type           object, user, group, or site (read-only after save)
 * @property-write string $subtype        Further clarifies the nature of the entity (this should not be read)
 * @property       int    $guid           The unique identifier for this entity (read only)
 * @property       int    $owner_guid     The GUID of the owner of this entity (usually the creator)
 * @property       int    $container_guid The GUID of the entity containing this entity
 * @property       int    $site_guid      The GUID of the website this entity is associated with
 * @property       int    $access_id      Specifies the visibility level of this entity
 * @property       int    $time_created   A UNIX timestamp of when the entity was created
 * @property       int    $time_updated   A UNIX timestamp of when the entity was last updated (automatically updated on save)
 * @property       string $enabled        Is this entity enabled ('yes' or 'no')
 *
 * Metadata (the above are attributes)
 * @property       string $location       A location of the entity
 */
abstract class ElggEntity extends ElggData implements
	Notable,   // Calendar interface (deprecated 1.9)
	Locatable, // Geocoding interface
	Importable // Allow import of data (deprecated 1.9)
{

	/**
	 * If set, overrides the value of getURL()
	 */
	protected $url_override;

	/**
	 * Icon override, overrides the value of getIcon().
	 */
	protected $icon_override;

	/**
	 * Holds metadata until entity is saved.  Once the entity is saved,
	 * metadata are written immediately to the database.
	 */
	protected $temp_metadata = array();

	/**
	 * Holds annotations until entity is saved.  Once the entity is saved,
	 * annotations are written immediately to the database.
	 */
	protected $temp_annotations = array();

	/**
	 * Holds private settings until entity is saved. Once the entity is saved,
	 * private settings are written immediately to the database.
	 */
	protected $temp_private_settings = array();

	/**
	 * Volatile data structure for this object, allows for storage of data
	 * in-memory that isn't sync'd back to the metadata table.
	 */
	protected $volatile = array();

	/**
	 * Tells how many tables are going to need to be searched in order to fully populate this object
	 *
	 * @var int
	 */
	protected $tables_split;

	/**
	 * Tells how many tables describing object have been loaded thus far
	 *
	 * @var int
	 */
	protected $tables_loaded;

	/**
	 * Initialize the attributes array.
	 *
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['guid'] = null;
		$this->attributes['type'] = null;
		$this->attributes['subtype'] = null;

		$this->attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$this->attributes['container_guid'] = elgg_get_logged_in_user_guid();

		$this->attributes['site_guid'] = null;
		$this->attributes['access_id'] = ACCESS_PRIVATE;
		$this->attributes['time_updated'] = null;
		$this->attributes['last_action'] = null;
		$this->attributes['enabled'] = "yes";

		// There now follows a bit of a hack
		/* Problem: To speed things up, some objects are split over several tables,
		 * this means that it requires n number of database reads to fully populate
		 * an entity. This causes problems for caching and create events
		 * since it is not possible to tell whether a subclassed entity is complete.
		 *
		 * Solution: We have two counters, one 'tables_split' which tells whatever is
		 * interested how many tables are going to need to be searched in order to fully
		 * populate this object, and 'tables_loaded' which is how many have been
		 * loaded thus far.
		 *
		 * If the two are the same then this object is complete.
		 *
		 * Use: isFullyLoaded() to check
		 */
		$this->tables_split = 1;
		$this->tables_loaded = 0;
	}

	/**
	 * Clone an entity
	 *
	 * Resets the guid so that the entity can be saved as a distinct entity from
	 * the original. Creation time will be set when this new entity is saved.
	 * The owner and container guids come from the original entity. The clone
	 * method copies metadata but does not copy annotations or private settings.
	 *
	 * @note metadata will have its owner and access id set when the entity is saved
	 * and it will be the same as that of the entity.
	 *
	 * @return void
	 */
	public function __clone() {
		$orig_entity = get_entity($this->guid);
		if (!$orig_entity) {
			elgg_log("Failed to clone entity with GUID $this->guid", "ERROR");
			return;
		}

		$metadata_array = elgg_get_metadata(array(
			'guid' => $this->guid,
			'limit' => 0
		));

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
				$this->__set($name, $orig_entity->$name);
			}
		}
	}

	/**
	 * Set an attribute or metadata value for this entity
	 *
	 * Anything that is not an attribute is saved as metadata.
	 *
	 * @warning Metadata set this way will inherit the entity's owner and
	 * access ID. If you want more control over metadata, use ElggEntity::setMetadata()
	 *
	 * @param string $name  Name of the attribute or metadata
	 * @param mixed  $value The value to be set
	 * @return void
	 * @see ElggEntity::setMetadata()
	 */
	public function __set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Certain properties should not be manually changed!
			switch ($name) {
				case 'guid':
				case 'time_updated':
				case 'last_action':
					return;
					break;
				case 'access_id':
				case 'owner_guid':
				case 'container_guid':
					if ($value !== null) {
						$this->attributes[$name] = (int)$value;
					} else {
						$this->attributes[$name] = null;
					}
					break;
				default:
					$this->attributes[$name] = $value;
					break;
			}
		} else {
			$this->setMetadata($name, $value);
		}
	}

	/**
	 * Sets the value of an attribute or metadata
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return bool
	 * @deprecated 1.9
	 */
	public function set($name, $value) {
		elgg_deprecated_notice("Use -> instead of set()", 1.9);
		$this->__set($name, $value);

		return true;
	}

	/**
	 * Get an attribute or metadata value
	 *
	 * If the name matches an attribute, the attribute is returned. If metadata
	 * does not exist with that name, a null is returned.
	 *
	 * This only returns an array if there are multiple values for a particular
	 * $name key.
	 *
	 * @param string $name Name of the attribute or metadata
	 * @return mixed
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->attributes)) {
			if ($name === 'subtype' && $this->attributes['guid']) {
				// note: only show deprecation notice if user reads ->subtype after save/load
				elgg_deprecated_notice("Use getSubtype()", 1.9);
			}
			return $this->attributes[$name];
		}

		return $this->getMetadata($name);
	}

	/**
	 * Return the value of an attribute or metadata
	 *
	 * @param string $name Name
	 * @return mixed Returns the value of a given value, or null.
	 * @deprecated 1.9
	 */
	public function get($name) {
		elgg_deprecated_notice("Use -> instead of get()", 1.9);
		return $this->__get($name);
	}

	/**
	 * Get the entity's display name
	 *
	 * @return string The title or name of this entity.
	 */
	abstract public function getDisplayName();

	/**
	 * Sets the title or name of this entity.
	 *
	 * @param string $displayName The title or name of this entity.
	 * @return void
	 */
	abstract public function setDisplayName($displayName);

	/**
	 * Return the value of a piece of metadata.
	 *
	 * @param string $name Name
	 *
	 * @return mixed The value, or null if not found.
	 */
	public function getMetadata($name) {
		$guid = $this->getGUID();

		if (!$guid) {
			if (isset($this->temp_metadata[$name])) {
				// md is returned as an array only if more than 1 entry
				if (count($this->temp_metadata[$name]) == 1) {
					return $this->temp_metadata[$name][0];
				} else {
					return $this->temp_metadata[$name];
				}
			} else {
				return null;
			}
		}

		// upon first cache miss, just load/cache all the metadata and retry.
		// if this works, the rest of this function may not be needed!
		$cache = _elgg_get_metadata_cache();
		if ($cache->isKnown($guid, $name)) {
			return $cache->load($guid, $name);
		} else {
			$cache->populateFromEntities(array($guid));
			// in case ignore_access was on, we have to check again...
			if ($cache->isKnown($guid, $name)) {
				return $cache->load($guid, $name);
			}
		}

		$md = elgg_get_metadata(array(
			'guid' => $guid,
			'metadata_name' => $name,
			'limit' => 0,
		));

		$value = null;

		if ($md && !is_array($md)) {
			$value = $md->value;
		} elseif (count($md) == 1) {
			$value = $md[0]->value;
		} else if ($md && is_array($md)) {
			$value = metadata_array_to_values($md);
		}

		$cache->save($guid, $name, $value);

		return $value;
	}

	/**
	 * Unset a property from metadata or attribute.
	 *
	 * @warning If you use this to unset an attribute, you must save the object!
	 *
	 * @param string $name The name of the attribute or metadata.
	 *
	 * @return void
	 * @todo some attributes should be set to null or other default values
	 */
	public function __unset($name) {
		if (array_key_exists($name, $this->attributes)) {
			$this->attributes[$name] = "";
		} else {
			$this->deleteMetadata($name);
		}
	}

	/**
	 * Set metadata on this entity.
	 *
	 * Plugin developers usually want to use the magic set method ($entity->name = 'value').
	 * Use this method if you want to explicitly set the owner or access of the metadata.
	 * You cannot set the owner/access before the entity has been saved.
	 *
	 * @param string $name       Name of the metadata
	 * @param mixed  $value      Value of the metadata (doesn't support assoc arrays)
	 * @param string $value_type 'text', 'integer', or '' for automatic detection
	 * @param bool   $multiple   Allow multiple values for a single name.
	 *                           Does not support associative arrays.
	 * @param int    $owner_guid GUID of entity that owns the metadata.
	 *                           Default is owner of entity.
	 * @param int    $access_id  Who can read the metadata relative to the owner.
	 *                           Default is the access level of the entity.
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function setMetadata($name, $value, $value_type = '', $multiple = false, $owner_guid = 0, $access_id = null) {

		// normalize value to an array that we will loop over
		// remove indexes if value already an array.
		if (is_array($value)) {
			$value = array_values($value);
		} else {
			$value = array($value);
		}

		// saved entity. persist md to db.
		if ($this->guid) {
			// if overwriting, delete first.
			if (!$multiple) {
				$options = array(
					'guid' => $this->getGUID(),
					'metadata_name' => $name,
					'limit' => 0
				);
				// @todo in 1.9 make this return false if can't add metadata
				// https://github.com/elgg/elgg/issues/4520
				//
				// need to remove access restrictions right now to delete
				// because this is the expected behavior
				$ia = elgg_set_ignore_access(true);
				if (false === elgg_delete_metadata($options)) {
					return false;
				}
				elgg_set_ignore_access($ia);
			}

			$owner_guid = (int)$owner_guid;
			$access_id = ($access_id === null) ? $this->getAccessId() : (int)$access_id;
			$owner_guid = $owner_guid ? $owner_guid : $this->getOwnerGUID();

			// add new md
			$result = true;
			foreach ($value as $value_tmp) {
				// at this point $value is appended because it was cleared above if needed.
				$md_id = create_metadata($this->getGUID(), $name, $value_tmp, $value_type,
						$owner_guid, $access_id, true);
				if (!$md_id) {
					return false;
				}
			}

			return $result;
		} else {
			// unsaved entity. store in temp array

			// returning single entries instead of an array of 1 element is decided in
			// getMetaData(), just like pulling from the db.

			if ($owner_guid != 0 || $access_id !== null) {
				$msg = "owner guid and access id cannot be used in ElggEntity::setMetadata() until entity is saved.";
				throw new InvalidArgumentException($msg);
			}

			// if overwrite, delete first
			if (!$multiple || !isset($this->temp_metadata[$name])) {
				$this->temp_metadata[$name] = array();
			}

			// add new md
			$this->temp_metadata[$name] = array_merge($this->temp_metadata[$name], $value);
			return true;
		}
	}

	/**
	 * Deletes all metadata on this object (metadata.entity_guid = $this->guid).
	 * If you pass a name, only metadata matching that name will be deleted.
	 *
	 * @warning Calling this with no $name will clear all metadata on the entity.
	 *
	 * @param null|string $name The name of the metadata to remove.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteMetadata($name = null) {

		if (!$this->guid) {
			return false;
		}

		$options = array(
			'guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['metadata_name'] = $name;
		}

		return elgg_delete_metadata($options);
	}

	/**
	 * Deletes all metadata owned by this object (metadata.owner_guid = $this->guid).
	 * If you pass a name, only metadata matching that name will be deleted.
	 *
	 * @param null|string $name The name of metadata to delete.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteOwnedMetadata($name = null) {
		// access is turned off for this because they might
		// no longer have access to an entity they created metadata on.
		$ia = elgg_set_ignore_access(true);
		$options = array(
			'metadata_owner_guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['metadata_name'] = $name;
		}

		$r = elgg_delete_metadata($options);
		elgg_set_ignore_access($ia);
		return $r;
	}

	/**
	 * Remove metadata
	 *
	 * @warning Calling this with no or empty arguments will clear all metadata on the entity.
	 *
	 * @param string $name The name of the metadata to clear
	 * @return mixed bool
	 * @deprecated 1.8 Use deleteMetadata()
	 */
	public function clearMetadata($name = '') {
		elgg_deprecated_notice('ElggEntity->clearMetadata() is deprecated by ->deleteMetadata()', 1.8);
		return $this->deleteMetadata($name);
	}

	/**
	 * Disables metadata for this entity, optionally based on name.
	 *
	 * @param string $name An options name of metadata to disable.
	 * @return bool
	 * @since 1.8
	 */
	public function disableMetadata($name = '') {
		$options = array(
			'guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['metadata_name'] = $name;
		}

		return elgg_disable_metadata($options);
	}

	/**
	 * Enables metadata for this entity, optionally based on name.
	 *
	 * @warning Before calling this, you must use {@link access_show_hidden_entities()}
	 *
	 * @param string $name An options name of metadata to enable.
	 * @return bool
	 * @since 1.8
	 */
	public function enableMetadata($name = '') {
		$options = array(
			'guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['metadata_name'] = $name;
		}

		return elgg_enable_metadata($options);
	}

	/**
	 * Get a piece of volatile (non-persisted) data on this entity.
	 *
	 * @param string $name The name of the volatile data
	 *
	 * @return mixed The value or null if not found.
	 */
	public function getVolatileData($name) {
		if (!is_array($this->volatile)) {
			$this->volatile = array();
		}

		if (array_key_exists($name, $this->volatile)) {
			return $this->volatile[$name];
		} else {
			return null;
		}
	}

	/**
	 * Set a piece of volatile (non-persisted) data on this entity
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	public function setVolatileData($name, $value) {
		if (!is_array($this->volatile)) {
			$this->volatile = array();
		}

		$this->volatile[$name] = $value;
	}

	/**
	 * Remove all relationships to and from this entity.
	 * If you pass a relationship name, only relationships matching that name
	 * will be deleted.
	 *
	 * @warning Calling this with no $relationship will clear all relationships
	 * for this entity.
	 *
	 * @param null|string $relationship The name of the relationship to remove.
	 * @return bool
	 * @see ElggEntity::addRelationship()
	 * @see ElggEntity::removeRelationship()
	 */
	public function deleteRelationships($relationship = null) {
		$relationship = (string)$relationship;
		$result = remove_entity_relationships($this->getGUID(), $relationship);
		return $result && remove_entity_relationships($this->getGUID(), $relationship, true);
	}

	/**
	 * Remove all relationships to and from this entity.
	 *
	 * @return bool
	 * @see ElggEntity::addRelationship()
	 * @see ElggEntity::removeRelationship()
	 * @deprecated 1.8 Use ElggEntity::deleteRelationships()
	 */
	public function clearRelationships() {
		elgg_deprecated_notice('ElggEntity->clearRelationships() is deprecated by ->deleteRelationships()', 1.8);
		return $this->deleteRelationships();
	}

	/**
	 * Add a relationship between this an another entity.
	 *
	 * @tip Read the relationship like "This entity is a $relationship of $guid_two."
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship.
	 * @param string $relationship The type of relationship.
	 *
	 * @return bool
	 * @see ElggEntity::removeRelationship()
	 * @see ElggEntity::deleteRelationships()
	 */
	public function addRelationship($guid_two, $relationship) {
		return add_entity_relationship($this->getGUID(), $relationship, $guid_two);
	}

	/**
	 * Remove a relationship
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship.
	 * @param string $relationship The type of relationship.
	 *
	 * @return bool
	 * @see ElggEntity::addRelationship()
	 * @see ElggEntity::deleteRelationships()
	 */
	public function removeRelationship($guid_two, $relationship) {
		return remove_entity_relationship($this->getGUID(), $relationship, $guid_two);
	}

	/**
	 * Adds a private setting to this entity.
	 *
	 * Private settings are similar to metadata but will not
	 * be searched and there are fewer helper functions for them.
	 *
	 * @param string $name  Name of private setting
	 * @param mixed  $value Value of private setting
	 *
	 * @return bool
	 */
	public function setPrivateSetting($name, $value) {
		if ((int) $this->guid > 0) {
			return set_private_setting($this->getGUID(), $name, $value);
		} else {
			$this->temp_private_settings[$name] = $value;
			return true;
		}
	}

	/**
	 * Returns a private setting value
	 *
	 * @param string $name Name of the private setting
	 *
	 * @return mixed Null if the setting does not exist
	 */
	public function getPrivateSetting($name) {
		if ((int) ($this->guid) > 0) {
			return get_private_setting($this->getGUID(), $name);
		} else {
			if (isset($this->temp_private_settings[$name])) {
				return $this->temp_private_settings[$name];
			}
		}
		return null;
	}

	/**
	 * Removes private setting
	 *
	 * @param string $name Name of the private setting
	 *
	 * @return bool
	 */
	public function removePrivateSetting($name) {
		return remove_private_setting($this->getGUID(), $name);
	}

	/**
	 * Deletes all annotations on this object (annotations.entity_guid = $this->guid).
	 * If you pass a name, only annotations matching that name will be deleted.
	 *
	 * @warning Calling this with no or empty arguments will clear all annotations on the entity.
	 *
	 * @param null|string $name The annotations name to remove.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteAnnotations($name = null) {
		$options = array(
			'guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['annotation_name'] = $name;
		}

		return elgg_delete_annotations($options);
	}

	/**
	 * Deletes all annotations owned by this object (annotations.owner_guid = $this->guid).
	 * If you pass a name, only annotations matching that name will be deleted.
	 *
	 * @param null|string $name The name of annotations to delete.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteOwnedAnnotations($name = null) {
		// access is turned off for this because they might
		// no longer have access to an entity they created annotations on.
		$ia = elgg_set_ignore_access(true);
		$options = array(
			'annotation_owner_guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['annotation_name'] = $name;
		}

		$r = elgg_delete_annotations($options);
		elgg_set_ignore_access($ia);
		return $r;
	}

	/**
	 * Disables annotations for this entity, optionally based on name.
	 *
	 * @param string $name An options name of annotations to disable.
	 * @return bool
	 * @since 1.8
	 */
	public function disableAnnotations($name = '') {
		$options = array(
			'guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['annotation_name'] = $name;
		}

		return elgg_disable_annotations($options);
	}

	/**
	 * Enables annotations for this entity, optionally based on name.
	 *
	 * @warning Before calling this, you must use {@link access_show_hidden_entities()}
	 *
	 * @param string $name An options name of annotations to enable.
	 * @return bool
	 * @since 1.8
	 */
	public function enableAnnotations($name = '') {
		$options = array(
			'guid' => $this->guid,
			'limit' => 0
		);
		if ($name) {
			$options['annotation_name'] = $name;
		}

		return elgg_enable_annotations($options);
	}

	/**
	 * Helper function to return annotation calculation results
	 *
	 * @param string $name        The annotation name.
	 * @param string $calculation A valid MySQL function to run its values through
	 * @return mixed
	 */
	private function getAnnotationCalculation($name, $calculation) {
		$options = array(
			'guid' => $this->getGUID(),
			'annotation_name' => $name,
			'annotation_calculation' => $calculation
		);

		return elgg_get_annotations($options);
	}

	/**
	 * Adds an annotation to an entity.
	 *
	 * @warning By default, annotations are private.
	 *
	 * @warning Annotating an unsaved entity more than once with the same name
	 *          will only save the last annotation.
	 *
	 * @param string $name       Annotation name
	 * @param mixed  $value      Annotation value
	 * @param int    $access_id  Access ID
	 * @param int    $owner_guid GUID of the annotation owner
	 * @param string $vartype    The type of annotation value
	 *
	 * @return bool|int Returns int if an annotation is saved
	 */
	public function annotate($name, $value, $access_id = ACCESS_PRIVATE, $owner_guid = 0, $vartype = "") {
		if ((int) $this->guid > 0) {
			return create_annotation($this->getGUID(), $name, $value, $vartype, $owner_guid, $access_id);
		} else {
			$this->temp_annotations[$name] = $value;
		}
		return true;
	}

	/**
	 * Gets an array of annotations.
	 *
	 * To retrieve annotations on an unsaved entity, pass array('name' => [annotation name])
	 * as the options array.
	 *
	 * @param array  $options Array of options for elgg_get_annotations() except guid. This
	 *               may be passed a string annotation name, but this usage is deprecated.
	 * @param int    $limit   Limit (deprecated)
	 * @param int    $offset  Offset (deprecated)
	 * @param string $order   Order by time: asc or desc (deprecated)
	 *
	 * @return array
	 * @see elgg_get_annotations()
	 */
	public function getAnnotations($options = array(), $limit = 50, $offset = 0, $order = "asc") {
		if (!is_array($options)) {
			elgg_deprecated_notice("ElggEntity::getAnnotations() takes an array of options.", 1.9);
		}

		if ((int) ($this->guid) > 0) {
			if (!is_array($options)) {
				$options = array(
					'guid' => $this->guid,
					'annotation_name' => $options,
					'limit' => $limit,
					'offset' => $offset,
				);

				if ($order != 'asc') {
					$options['reverse_order_by'] = true;
				}
			} else {
				$options['guid'] = $this->guid;
			}

			return elgg_get_annotations($options);
		} else {
			if (!is_array($options)) {
				$name = $options;
			} else {
				$name = elgg_extract('annotation_name', $options, '');
			}

			if (isset($this->temp_annotations[$name])) {
				return array($this->temp_annotations[$name]);
			}
		}

		return array();
	}

	/**
	 * Remove an annotation or all annotations for this entity.
	 *
	 * @warning Calling this method with no or an empty argument will remove
	 * all annotations on the entity.
	 *
	 * @param string $name Annotation name
	 * @return bool
	 * @deprecated 1.8 Use ->deleteAnnotations()
	 */
	public function clearAnnotations($name = "") {
		elgg_deprecated_notice('ElggEntity->clearAnnotations() is deprecated by ->deleteAnnotations()', 1.8);
		return $this->deleteAnnotations($name);
	}

	/**
	 * Count annotations.
	 *
	 * @param string $name The type of annotation.
	 *
	 * @return int
	 */
	public function countAnnotations($name = "") {
		return $this->getAnnotationCalculation($name, 'count');
	}

	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsAvg($name) {
		return $this->getAnnotationCalculation($name, 'avg');
	}

	/**
	 * Get the sum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsSum($name) {
		return $this->getAnnotationCalculation($name, 'sum');
	}

	/**
	 * Get the minimum of integer type annotations of given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsMin($name) {
		return $this->getAnnotationCalculation($name, 'min');
	}

	/**
	 * Get the maximum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsMax($name) {
		return $this->getAnnotationCalculation($name, 'max');
	}

	/**
	 * Count the number of comments attached to this entity.
	 *
	 * @return int Number of comments
	 * @since 1.8.0
	 */
	public function countComments() {
		$params = array('entity' => $this);
		$num = elgg_trigger_plugin_hook('comments:count', $this->getType(), $params);

		if (is_int($num)) {
			return $num;
		} else {
			return elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'comment',
				'container_guid' => $this->getGUID(),
				'count' => true,
			));
		}
	}

	/**
	 * Gets an array of entities with a relationship to this entity.
	 *
	 * @param array $options Options array. See elgg_get_entities_from_relationship()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity.
	 * @param bool  $inverse Is this an inverse relationship? (deprecated)
	 * @param int   $limit   Number of elements to return (deprecated)
	 * @param int   $offset  Indexing offset (deprecated)
	 *
	 * @return array|false An array of entities or false on failure
	 * @see elgg_get_entities_from_relationship()
	 */
	public function getEntitiesFromRelationship($options = array(), $inverse = false, $limit = 50, $offset = 0) {
		if (is_array($options)) {
			$options['relationship_guid'] = $this->getGUID();
			return elgg_get_entities_from_relationship($options);
		} else {
			elgg_deprecated_notice("ElggEntity::getEntitiesFromRelationship takes an options array", 1.9);
			return elgg_get_entities_from_relationship(array(
				'relationship' => $options,
				'relationship_guid' => $this->getGUID(),
				'inverse_relationship' => $inverse,
				'limit' => $limit,
				'offset' => $offset
			));
		}
	}

	/**
	 * Gets the number of entities from a specific relationship type
	 *
	 * @param string $relationship         Relationship type (eg "friends")
	 * @param bool   $inverse_relationship Invert relationship
	 *
	 * @return int|false The number of entities or false on failure
	 */
	public function countEntitiesFromRelationship($relationship, $inverse_relationship = false) {
		return elgg_get_entities_from_relationship(array(
			'relationship' => $relationship,
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => $inverse_relationship,
			'count' => true
		));
	}

	/**
	 * Can a user edit this entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check plugin hook.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is editable by the given user.
	 * @see elgg_set_ignore_access()
	 */
	public function canEdit($user_guid = 0) {
		$user_guid = (int)$user_guid;
		$user = get_entity($user_guid);
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}

		$return = false;

		// Test user if possible - should default to false unless a plugin hook says otherwise
		if ($user) {
			if ($this->getOwnerGUID() == $user->getGUID()) {
				$return = true;
			}

			if ($this->getContainerGUID() == $user->getGUID()) {
				$return = true;
			}

			if ($this->getGUID() == $user->getGUID()) {
				$return = true;
			}

			$container = $this->getContainerEntity();
			if ($container && $container->canEdit($user->getGUID())) {
				$return = true;
			}
		}

		$params = array('entity' => $this, 'user' => $user);
		return elgg_trigger_plugin_hook('permissions_check', $this->type, $params, $return);
	}

	/**
	 * Can a user edit metadata on this entity?
	 *
	 * If no specific metadata is passed, it returns whether the user can
	 * edit any metadata on the entity.
	 *
	 * @tip Can be overridden by by registering for the permissions_check:metadata
	 * plugin hook.
	 *
	 * @param ElggMetadata $metadata  The piece of metadata to specifically check or null for any metadata
	 * @param int          $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canEditMetadata($metadata = null, $user_guid = 0) {
		if (!$this->guid) {
			// @todo cannot edit metadata on unsaved entity?
			return false;
		}

		if ($user_guid) {
			$user = get_user($user_guid);
			if (!$user) {
				return false;
			}
		} else {
			$user = elgg_get_logged_in_user_entity();
			$user_guid = $user->guid;
		}

		$return = null;

		// if metadata is not owned or owned by the user, then can edit
		if ($metadata && ($metadata->owner_guid == 0 || $metadata->owner_guid == $user_guid)) {
			$return = true;
		}

		if (is_null($return)) {
			$return = $this->canEdit($user_guid);
		}

		// metadata and user may be null
		$params = array('entity' => $this, 'user' => $user, 'metadata' => $metadata);
		return elgg_trigger_plugin_hook('permissions_check:metadata', $this->type, $params, $return);
	}

	/**
	 * Can a user add an entity to this container
	 *
	 * @param int    $user_guid The GUID of the user creating the entity (0 for logged in user).
	 * @param string $type      The type of entity we're looking to write
	 * @param string $subtype   The subtype of the entity we're looking to write
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canWriteToContainer($user_guid = 0, $type = 'all', $subtype = 'all') {
		return can_write_to_container($user_guid, $this->guid, $type, $subtype);
	}

	/**
	 * Can a user comment on an entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:comment,
	 * <entity type> plugin hook.
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 *
	 * @return bool
	 */
	public function canComment($user_guid = 0) {
		if ($user_guid == 0) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		$user = get_entity($user_guid);

		// By default, we don't take a position of whether commenting is allowed
		// because it is handled by the subclasses of ElggEntity
		$params = array('entity' => $this, 'user' => $user);
		return elgg_trigger_plugin_hook('permissions_check:comment', $this->type, $params, null);
	}

	/**
	 * Can a user annotate an entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:annotate,
	 * <entity type> plugin hook.
	 *
	 * @tip If you want logged out users to annotate an object, do not call
	 * canAnnotate(). It's easier than using the plugin hook.
	 *
	 * @param int    $user_guid       User guid (default is logged in user)
	 * @param string $annotation_name The name of the annotation (default is unspecified)
	 *
	 * @return bool
	 */
	public function canAnnotate($user_guid = 0, $annotation_name = '') {
		if ($user_guid == 0) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		$user = get_entity($user_guid);

		$return = true;
		if (!$user) {
			$return = false;
		}

		$params = array(
			'entity' => $this,
			'user' => $user,
			'annotation_name' => $annotation_name,
		);
		return elgg_trigger_plugin_hook('permissions_check:annotate', $this->type, $params, $return);
	}

	/**
	 * Returns the access_id.
	 *
	 * @return int The access ID
	 */
	public function getAccessID() {
		return $this->access_id;
	}

	/**
	 * Returns the guid.
	 *
	 * @return int|null GUID
	 */
	public function getGUID() {
		return $this->guid;
	}

	/**
	 * Returns the entity type
	 *
	 * @return string The entity type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the entity subtype
	 *
	 * @return string The entity subtype
	 */
	public function getSubtype() {
		// If this object hasn't been saved, then return the subtype string.
		if ($this->attributes['guid']) {
			return get_subtype_from_id($this->attributes['subtype']);
		}
		return $this->attributes['subtype'];
	}

	/**
	 * Get the guid of the entity's owner.
	 *
	 * @return int The owner GUID
	 */
	public function getOwnerGUID() {
		return (int)$this->owner_guid;
	}

	/**
	 * Return the guid of the entity's owner.
	 *
	 * @return int The owner GUID
	 * @deprecated 1.8 Use getOwnerGUID()
	 */
	public function getOwner() {
		elgg_deprecated_notice("ElggEntity::getOwner deprecated for ElggEntity::getOwnerGUID", 1.8);
		return $this->getOwnerGUID();
	}

	/**
	 * Gets the ElggEntity that owns this entity.
	 *
	 * @return ElggEntity The owning entity
	 */
	public function getOwnerEntity() {
		return get_entity($this->owner_guid);
	}

	/**
	 * Set the container for this object.
	 *
	 * @param int $container_guid The ID of the container.
	 *
	 * @return bool
	 */
	public function setContainerGUID($container_guid) {
		return $this->container_guid = (int)$container_guid;
	}

	/**
	 * Set the container for this object.
	 *
	 * @param int $container_guid The ID of the container.
	 *
	 * @return bool
	 * @deprecated 1.8 use setContainerGUID()
	 */
	public function setContainer($container_guid) {
		elgg_deprecated_notice("ElggObject::setContainer deprecated for ElggEntity::setContainerGUID", 1.8);
		return $this->setContainerGUID('container_guid', $container_guid);
	}

	/**
	 * Gets the container GUID for this entity.
	 *
	 * @return int
	 */
	public function getContainerGUID() {
		return (int)$this->container_guid;
	}

	/**
	 * Gets the container GUID for this entity.
	 *
	 * @return int
	 * @deprecated 1.8 Use getContainerGUID()
	 */
	public function getContainer() {
		elgg_deprecated_notice("ElggObject::getContainer deprecated for ElggEntity::getContainerGUID", 1.8);
		return $this->getContainerGUID();
	}

	/**
	 * Get the container entity for this object.
	 *
	 * @return ElggEntity
	 * @since 1.8.0
	 */
	public function getContainerEntity() {
		return get_entity($this->getContainerGUID());
	}

	/**
	 * Returns the UNIX epoch time that this entity was last updated
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeUpdated() {
		return $this->time_updated;
	}

	/**
	 * Gets the URL for this entity.
	 *
	 * Plugins can register for the 'entity:url', <type> plugin hook to
	 * customize the url for an entity.
	 *
	 * @return string The URL of the entity
	 */
	public function getURL() {

		$url = "";

		// @todo remove when elgg_register_entity_url_handler() has been removed
		if ($this->guid) {
			global $CONFIG;
			if (isset($CONFIG->entity_url_handler[$this->getType()][$this->getSubtype()])) {
				$function = $CONFIG->entity_url_handler[$this->getType()][$this->getSubtype()];
				if (is_callable($function)) {
					$url = call_user_func($function, $this);
				}
			} elseif (isset($CONFIG->entity_url_handler[$this->getType()]['all'])) {
				$function = $CONFIG->entity_url_handler[$this->getType()]['all'];
				if (is_callable($function)) {
					$url = call_user_func($function, $this);
				}
			} elseif (isset($CONFIG->entity_url_handler['all']['all'])) {
				$function = $CONFIG->entity_url_handler['all']['all'];
				if (is_callable($function)) {
					$url = call_user_func($function, $this);
				}
			}

			if ($url) {
				$url = elgg_normalize_url($url);
			}
		}

		$type = $this->getType();
		$params = array('entity' => $this);
		$url = elgg_trigger_plugin_hook('entity:url', $type, $params, $url);

		// @todo remove when ElggEntity::setURL() has been removed
		if (!empty($this->url_override)) {
			$url = $this->url_override;
		}

		return elgg_normalize_url($url);
	}

	/**
	 * Overrides the URL returned by getURL()
	 *
	 * @warning This override exists only for the life of the object.
	 *
	 * @param string $url The new item URL
	 *
	 * @return string The URL
	 * @deprecated 1.9.0 See ElggEntity::getURL() for details on the plugin hook
	 */
	public function setURL($url) {
		elgg_deprecated_notice('ElggEntity::setURL() has been replaced by the "entity:url" plugin hook', 1.9);
		$this->url_override = $url;
		return $url;
	}

	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', <type> plugin hook
	 * to customize the icon for an entity.
	 *
	 * @param string $size Size of the icon: tiny, small, medium, large
	 *
	 * @return string The URL
	 * @since 1.8.0
	 */
	public function getIconURL($size = 'medium') {
		$size = elgg_strtolower($size);

		if (isset($this->icon_override[$size])) {
			elgg_deprecated_notice("icon_override on an individual entity is deprecated", 1.8);
			return $this->icon_override[$size];
		}

		$type = $this->getType();
		$params = array(
			'entity' => $this,
			'size' => $size,
		);

		$url = elgg_trigger_plugin_hook('entity:icon:url', $type, $params, null);
		if ($url == null) {
			$url = "_graphics/icons/default/$size.png";
		}

		return elgg_normalize_url($url);
	}

	/**
	 * Returns a URL for the entity's icon.
	 *
	 * @param string $size Either 'large', 'medium', 'small' or 'tiny'
	 *
	 * @return string The url or false if no url could be worked out.
	 * @deprecated 1.8 Use getIconURL()
	 */
	public function getIcon($size = 'medium') {
		elgg_deprecated_notice("getIcon() deprecated by getIconURL()", 1.8);
		return $this->getIconURL($size);
	}

	/**
	 * Set an icon override for an icon and size.
	 *
	 * @warning This override exists only for the life of the object.
	 *
	 * @param string $url  The url of the icon.
	 * @param string $size The size its for.
	 *
	 * @return bool
	 * @deprecated 1.8 See getIconURL() for the plugin hook to use
	 */
	public function setIcon($url, $size = 'medium') {
		elgg_deprecated_notice("icon_override on an individual entity is deprecated", 1.8);

		$url = sanitise_string($url);
		$size = sanitise_string($size);

		if (!$this->icon_override) {
			$this->icon_override = array();
		}
		$this->icon_override[$size] = $url;

		return true;
	}

	/**
	 * Add this entity to a site
	 *
	 * This creates a 'member_of_site' relationship.
	 *
	 * @param ElggSite $site The site to add this entity to
	 *
	 * @return bool
	 * @todo add ElggSite type hint once we have removed addToSite() from ElggUser
	 * and ElggObject
	 */
	public function addToSite($site) {
		if (!elgg_instanceof($site, 'site')) {
			return false;
		}

		return $site->addEntity($this);
	}

	/**
	 * Remove this entity from a site
	 *
	 * This deletes the 'member_of_site' relationship.
	 *
	 * @param ElggSite $site The site to remove this entity from
	 *
	 * @return bool
	 * @todo add ElggSite type hint once we have removed addToSite() from ElggUser
	 */
	public function removeFromSite($site) {
		if (!elgg_instanceof($site, 'site')) {
			return false;
		}

		return $site->removeEntity($this);
	}

	/**
	 * Gets the sites this entity is a member of
	 *
	 * Site membership is determined by relationships and not site_guid.
	 *
	 * @param array $options Options array for elgg_get_entities_from_relationship()
	 *                       Parameters set automatically by this method:
	 *                       'relationship', 'relationship_guid', 'inverse_relationship'
	 *
	 * @return array
	 * @todo add type hint when ElggUser and ElggObject have been updates
	 */
	public function getSites($options = array()) {
		$options['relationship'] = 'member_of_site';
		$options['relationship_guid'] = $this->guid;
		$options['inverse_relationship'] = false;
		if (!isset($options['site_guid']) || !isset($options['site_guids'])) {
			$options['site_guids'] = ELGG_ENTITIES_ANY_VALUE;
		}

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * Tests to see whether the object has been fully loaded.
	 *
	 * @return bool
	 */
	public function isFullyLoaded() {
		return ! ($this->tables_loaded < $this->tables_split);
	}

	/**
	 * Save an entity.
	 *
	 * @return bool|int
	 * @throws InvalidParameterException
	 * @throws IOException
	 */
	public function save() {
		$guid = $this->getGUID();
		if ($guid > 0) {
			return $this->update();
		} else {
			$guid = $this->create();
			if ($guid) {
				if (elgg_trigger_event('create', $this->type, $this)) {
					return $guid;
				} else {
					// plugins that return false to event don't need to override the access system
					$ia = elgg_set_ignore_access(true);
					$this->delete();
					elgg_set_ignore_access($ia);
				}
			}
		}

		return false;
	}

	/**
	 * Create a new entry in the entities table.
	 *
	 * Saves the base information in the entities table for the entity.  Saving
	 * the type-specific information is handled in the calling class method.
	 *
	 * @warning Entities must have an entry in both the entities table and their type table
	 * or they will throw an exception when loaded.
	 *
	 * @return int The new entity's GUID
	 * @throws InvalidParameterException If the entity's type has not been set.
	 * @throws IOException If the new row fails to write to the DB.
	 */
	protected function create() {
		global $CONFIG;

		// Using attribute array directly; get function does something special!
		$type = $this->getDatabase()->sanitizeString($this->attributes['type']);
		if ($type == "") {
			throw new InvalidParameterException("Entity type must be set.");
		}

		$subtype = $this->attributes['subtype'];
		$subtype_id = add_subtype($type, $subtype);
		$owner_guid = (int)$this->attributes['owner_guid'];
		$access_id = (int)$this->attributes['access_id'];
		$now = (string)time();
		$time_created = isset($this->attributes['time_created']) ? (int)$this->attributes['time_created'] : $now;

		$site_guid = $this->attributes['site_guid'];
		if ($site_guid == 0) {
			$site_guid = $CONFIG->site_guid;
		}
		$site_guid = (int)$site_guid;

		$container_guid = $this->attributes['container_guid'];
		if ($container_guid == 0) {
			$container_guid = $owner_guid;
		}
		$container_guid = (int)$container_guid;

		if ($access_id == ACCESS_DEFAULT) {
			throw new InvalidParameterException('ACCESS_DEFAULT is not a valid access level. See its documentation in elgglib.h');
		}

		$owner = $this->getOwnerEntity();
		if ($owner && !$owner->canWriteToContainer(0, $type, $subtype)) {
			return false;
		}

		if ($owner_guid != $container_guid) {
			$container = $this->getContainerEntity();
			if ($container && !$container->canWriteToContainer(0, $type, $subtype)) {
				return false;
			}
		}

		$result = $this->getDatabase()->insertData("INSERT into {$CONFIG->dbprefix}entities
			(type, subtype, owner_guid, site_guid, container_guid,
				access_id, time_created, time_updated, last_action)
			values
			('$type', $subtype_id, $owner_guid, $site_guid, $container_guid,
				$access_id, $time_created, $now, $now)");

		if (!$result) {
			throw new IOException("Unable to save new object's base entity information!");
		}

		// for BC with 1.8, ->subtype always returns ID, ->getSubtype() the string
		$this->attributes['subtype'] = (int)$subtype_id;
		$this->attributes['guid'] = (int)$result;
		$this->attributes['time_created'] = (int)$time_created;
		$this->attributes['time_updated'] = (int)$now;
		$this->attributes['last_action'] = (int)$now;
		$this->attributes['site_guid'] = (int)$site_guid;
		$this->attributes['container_guid'] = (int)$container_guid;

		// Save any unsaved metadata
		if (sizeof($this->temp_metadata) > 0) {
			foreach ($this->temp_metadata as $name => $value) {
				$this->$name = $value;
			}

			$this->temp_metadata = array();
		}

		// Save any unsaved annotations.
		if (sizeof($this->temp_annotations) > 0) {
			foreach ($this->temp_annotations as $name => $value) {
				$this->annotate($name, $value);
			}

			$this->temp_annotations = array();
		}

		// Save any unsaved private settings.
		if (sizeof($this->temp_private_settings) > 0) {
			foreach ($this->temp_private_settings as $name => $value) {
				$this->setPrivateSetting($name, $value);
			}

			$this->temp_private_settings = array();
		}

		_elgg_cache_entity($this);

		return $result;
	}

	/**
	 * Update the entity in the database.
	 *
	 * @return bool Whether the update was successful.
	 *
	 * @throws InvalidParameterException
	 */
	protected function update() {
		global $CONFIG;

		// See #5600. This ensures canEdit() checks the BD persisted entity so it sees the
		// persisted owner_guid, container_guid, etc.
		_elgg_disable_caching_for_entity($this->guid);
		$persisted_entity = get_entity($this->guid);
		if (!$persisted_entity) {
			// Why worry about this case? If access control was off when the user fetched this object but
			// was turned back on again. Better to just bail than to turn access control off again.
			return false;
		}

		$allow_edit = $persisted_entity->canEdit();
		unset($persisted_entity);

		if ($allow_edit) {
			$allow_edit = elgg_trigger_event('update', $this->type, $this);
		}

		_elgg_enable_caching_for_entity($this->guid);

		if (!$allow_edit) {
			return false;
		}

		// See #6225. We copy these after the update event in case a handler changed one of them.
		$guid = (int)$this->guid;
		$owner_guid = (int)$this->owner_guid;
		$access_id = (int)$this->access_id;
		$container_guid = (int)$this->container_guid;
		$time_created = (int)$this->time_created;
		$time = time();

		if ($access_id == ACCESS_DEFAULT) {
			throw new InvalidParameterException('ACCESS_DEFAULT is not a valid access level. See its documentation in elgglib.php');
		}

		$ret = $this->getDatabase()->updateData("UPDATE {$CONFIG->dbprefix}entities
			set owner_guid='$owner_guid', access_id='$access_id',
			container_guid='$container_guid', time_created='$time_created',
			time_updated='$time' WHERE guid=$guid");

		// TODO(evan): Move this to ElggObject?
		if ($this instanceof ElggObject) {
			update_river_access_by_object($guid, $access_id);
		}

		// If memcache is available then delete this entry from the cache
		static $newentity_cache;
		if ((!$newentity_cache) && (is_memcache_available())) {
			$newentity_cache = new ElggMemcache('new_entity_cache');
		}
		if ($newentity_cache) {
			$newentity_cache->delete($guid);
		}

		if ($ret !== false) {
			$this->attributes['time_updated'] = $time;
		}

		_elgg_cache_entity($this);

		// Handle cases where there was no error BUT no rows were updated!
		return $ret !== false;
	}

	/**
	 * Loads attributes from the entities table into the object.
	 *
	 * @param mixed $guid GUID of entity or stdClass object from entities table
	 *
	 * @return bool
	 */
	protected function load($guid) {
		if ($guid instanceof stdClass) {
			$row = $guid;
		} else {
			$row = get_entity_as_row($guid);
		}

		if ($row) {
			// Create the array if necessary - all subclasses should test before creating
			if (!is_array($this->attributes)) {
				$this->attributes = array();
			}

			// Now put these into the attributes array as core values
			$objarray = (array) $row;
			foreach ($objarray as $key => $value) {
				$this->attributes[$key] = $value;
			}

			// Increment the portion counter
			if (!$this->isFullyLoaded()) {
				$this->tables_loaded++;
			}

			// guid needs to be an int  https://github.com/elgg/elgg/issues/4111
			$this->attributes['guid'] = (int)$this->attributes['guid'];

			// for BC with 1.8, ->subtype always returns ID, ->getSubtype() the string
			$this->attributes['subtype'] = (int)$this->attributes['subtype'];

			// Cache object handle
			if ($this->attributes['guid']) {
				_elgg_cache_entity($this);
			}

			return true;
		}

		return false;
	}

	/**
	 * Stores non-attributes from the loading of the entity as volatile data
	 *
	 * @param array $data Key value array
	 * @return void
	 */
	protected function loadAdditionalSelectValues(array $data) {
		foreach ($data as $name => $value) {
			$this->setVolatileData("select:$name", $value);
		}
	}

	/**
	 * Load new data from database into existing entity. Overwrites data but
	 * does not change values not included in the latest data.
	 *
	 * @internal This is used when the same entity is selected twice during a
	 * request in case different select clauses were used to load different data
	 * into volatile data.
	 *
	 * @param stdClass $row DB row with new entity data
	 * @return bool
	 * @access private
	 */
	public function refresh(stdClass $row) {
		if ($row instanceof stdClass) {
			return $this->load($row);
		}
		return false;
	}

	/**
	 * Disable this entity.
	 *
	 * Disabled entities are not returned by getter functions.
	 * To enable an entity, use {@link ElggEntity::enable()}.
	 *
	 * Recursively disabling an entity will disable all entities
	 * owned or contained by the parent entity.
	 *
	 * You can ignore the disabled field by using {@link access_show_hidden_entities()}.
	 *
	 * @internal Disabling an entity sets the 'enabled' column to 'no'.
	 *
	 * @param string $reason    Optional reason
	 * @param bool   $recursive Recursively disable all contained entities?
	 *
	 * @return bool
	 * @see ElggEntity::enable()
	 */
	public function disable($reason = "", $recursive = true) {
		if (!$this->guid) {
			return false;
		}

		if (!elgg_trigger_event('disable', $this->type, $this)) {
			return false;
		}

		if (!$this->canEdit()) {
			return false;
		}

		_elgg_invalidate_cache_for_entity($this->guid);

		if ($reason) {
			$this->disable_reason = $reason;
		}

		global $CONFIG;
		$guid = (int)$this->guid;

		if ($recursive) {
			$hidden = access_get_show_hidden_status();
			access_show_hidden_entities(true);
			$ia = elgg_set_ignore_access(true);

			$sub_entities = $this->getDatabase()->getData("SELECT * FROM {$CONFIG->dbprefix}entities
				WHERE (
				container_guid = $guid
				OR owner_guid = $guid
				OR site_guid = $guid
				) AND enabled='yes'", 'entity_row_to_elggstar');

			if ($sub_entities) {
				foreach ($sub_entities as $e) {
					add_entity_relationship($e->guid, 'disabled_with', $this->guid);
					$e->disable($reason);
				}
			}

			access_show_hidden_entities($hidden);
			elgg_set_ignore_access($ia);
		}

		$this->disableMetadata();
		$this->disableAnnotations();

		$res = $this->getDatabase()->updateData("UPDATE {$CONFIG->dbprefix}entities
			SET enabled = 'no'
			WHERE guid = $guid");

		if ($res) {
			$this->attributes['enabled'] = 'no';
			elgg_trigger_event('disable:after', $this->type, $this);
		}

		return $res;
	}

	/**
	 * Enable the entity
	 *
	 * @warning Disabled entities can't be loaded unless
	 * {@link access_show_hidden_entities(true)} has been called.
	 *
	 * @param bool $recursive Recursively enable all entities disabled with the entity?
	 * @see access_show_hiden_entities()
	 * @return bool
	 */
	public function enable($recursive = true) {
		$guid = (int)$this->guid;
		if (!$guid) {
			return false;
		}

		if (!elgg_trigger_event('enable', $this->type, $this)) {
			return false;
		}

		if (!$this->canEdit()) {
			return false;
		}

		global $CONFIG;

		// Override access only visible entities
		$old_access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$result = $this->getDatabase()->updateData("UPDATE {$CONFIG->dbprefix}entities
			SET enabled = 'yes'
			WHERE guid = $guid");

		$this->deleteMetadata('disable_reason');
		$this->enableMetadata();
		$this->enableAnnotations();

		if ($recursive) {
			$disabled_with_it = elgg_get_entities_from_relationship(array(
				'relationship' => 'disabled_with',
				'relationship_guid' => $guid,
				'inverse_relationship' => true,
				'limit' => 0,
			));

			foreach ($disabled_with_it as $e) {
				$e->enable();
				remove_entity_relationship($e->guid, 'disabled_with', $guid);
			}
		}

		access_show_hidden_entities($old_access_status);

		if ($result) {
			$this->attributes['enabled'] = 'yes';
			elgg_trigger_event('enable:after', $this->type, $this);
		}

		return $result;
	}

	/**
	 * Is this entity enabled?
	 *
	 * @return boolean Whether this entity is enabled.
	 */
	public function isEnabled() {
		return $this->enabled == 'yes';
	}

	/**
	 * Deletes the entity.
	 *
	 * Removes the entity and its metadata, annotations, relationships,
	 * river entries, and private data.
	 *
	 * Optionally can remove entities contained and owned by this entity.
	 *
	 * @warning If deleting recursively, this bypasses ownership of items contained by
	 * the entity.  That means that if the container_guid = $this->guid, the item will
	 * be deleted regardless of who owns it.
	 *
	 * @param bool $recursive If true (default) then all entities which are
	 *                        owned or contained by $this will also be deleted.
	 *
	 * @return bool
	 */
	public function delete($recursive = true) {
		global $CONFIG;

		$guid = $this->guid;
		if (!$guid) {
			return false;
		}

		if (!elgg_trigger_event('delete', $this->type, $this)) {
			return false;
		}

		if (!$this->canEdit()) {
			return false;
		}

		_elgg_invalidate_cache_for_entity($guid);

		// If memcache is available then delete this entry from the cache
		static $newentity_cache;
		if ((!$newentity_cache) && (is_memcache_available())) {
			$newentity_cache = new ElggMemcache('new_entity_cache');
		}
		if ($newentity_cache) {
			$newentity_cache->delete($guid);
		}

		// Delete contained owned and otherwise releated objects (depth first)
		if ($recursive) {
			// Temporary token overriding access controls
			// @todo Do this better.
			static $__RECURSIVE_DELETE_TOKEN;
			// Make it slightly harder to guess
			$__RECURSIVE_DELETE_TOKEN = md5(elgg_get_logged_in_user_guid());

			$entity_disable_override = access_get_show_hidden_status();
			access_show_hidden_entities(true);
			$ia = elgg_set_ignore_access(true);

			// @todo there was logic in the original code that ignored
			// entities with owner or container guids of themselves.
			// this should probably be prevented in ElggEntity instead of checked for here
			$options = array(
				'wheres' => array(
					"((container_guid = $guid OR owner_guid = $guid OR site_guid = $guid)"
					. " AND guid != $guid)"
					),
				'limit' => 0
			);

			$batch = new ElggBatch('elgg_get_entities', $options);
			$batch->setIncrementOffset(false);

			foreach ($batch as $e) {
				$e->delete(true);
			}

			access_show_hidden_entities($entity_disable_override);
			$__RECURSIVE_DELETE_TOKEN = null;
			elgg_set_ignore_access($ia);
		}

		$entity_disable_override = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$ia = elgg_set_ignore_access(true);

		// Now delete the entity itself
		$this->deleteMetadata();
		$this->deleteOwnedMetadata();
		$this->deleteAnnotations();
		$this->deleteOwnedAnnotations();
		$this->deleteRelationships();

		access_show_hidden_entities($entity_disable_override);
		elgg_set_ignore_access($ia);

		elgg_delete_river(array('subject_guid' => $guid));
		elgg_delete_river(array('object_guid' => $guid));
		elgg_delete_river(array('target_guid' => $guid));
		remove_all_private_settings($guid);

		$res = $this->getDatabase()->deleteData("DELETE FROM {$CONFIG->dbprefix}entities WHERE guid = $guid");
		if ($res) {
			$sub_table = "";

			// Where appropriate delete the sub table
			switch ($this->type) {
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
				$this->getDatabase()->deleteData("DELETE FROM $sub_table WHERE guid = $guid");
			}
		}

		return (bool)$res;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toObject() {
		$object = $this->prepareObject(new stdClass());
		$params = array('entity' => $this);
		$object = elgg_trigger_plugin_hook('to:object', 'entity', $params, $object);
		return $object;
	}

	/**
	 * Prepare an object copy for toObject()
	 *
	 * @param stdClass $object Object representation of the entity
	 * @return stdClass
	 */
	protected function prepareObject($object) {
		$object->guid = $this->guid;
		$object->type = $this->getType();
		$object->subtype = $this->getSubtype();
		$object->owner_guid = $this->getOwnerGUID();
		$object->container_guid = $this->getContainerGUID();
		$object->site_guid = (int)$this->site_guid;
		$object->time_created = date('c', $this->getTimeCreated());
		$object->time_updated = date('c', $this->getTimeUpdated());
		$object->url = $this->getURL();
		$object->read_access = (int)$this->access_id;
		return $object;
	}

	/*
	 * LOCATABLE INTERFACE
	 */

	/**
	 * Gets the 'location' metadata for the entity
	 *
	 * @return string The location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Sets the 'location' metadata for the entity
	 *
	 * @param string $location String representation of the location
	 *
	 * @return void
	 */
	public function setLocation($location) {
		$this->location = $location;
	}

	/**
	 * Set latitude and longitude metadata tags for a given entity.
	 *
	 * @param float $lat  Latitude
	 * @param float $long Longitude
	 *
	 * @return void
	 * @todo Unimplemented
	 */
	public function setLatLong($lat, $long) {
		$this->set('geo:lat', $lat);
		$this->set('geo:long', $long);
	}

	/**
	 * Return the entity's latitude.
	 *
	 * @return float
	 * @todo Unimplemented
	 */
	public function getLatitude() {
		return (float)$this->get('geo:lat');
	}

	/**
	 * Return the entity's longitude
	 *
	 * @return float
	 * @todo Unimplemented
	 */
	public function getLongitude() {
		return (float)$this->get('geo:long');
	}

	/*
	 * NOTABLE INTERFACE
	 */

	/**
	 * Set the time and duration of an object
	 *
	 * @param int $hour     If ommitted, now is assumed.
	 * @param int $minute   If ommitted, now is assumed.
	 * @param int $second   If ommitted, now is assumed.
	 * @param int $day      If ommitted, now is assumed.
	 * @param int $month    If ommitted, now is assumed.
	 * @param int $year     If ommitted, now is assumed.
	 * @param int $duration Duration of event, remainder of the day is assumed.
	 *
	 * @return true
	 * @deprecated 1.9
	 */
	public function setCalendarTimeAndDuration($hour = null, $minute = null, $second = null,
	$day = null, $month = null, $year = null, $duration = null) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);

		$start = mktime($hour, $minute, $second, $month, $day, $year);
		$end = $start + abs($duration);
		if (!$duration) {
			$end = get_day_end($day, $month, $year);
		}

		$this->calendar_start = $start;
		$this->calendar_end = $end;

		return true;
	}

	/**
	 * Returns the start timestamp.
	 *
	 * @return int
	 * @deprecated 1.9
	 */
	public function getCalendarStartTime() {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);
		return (int)$this->calendar_start;
	}

	/**
	 * Returns the end timestamp.
	 *
	 * @return int
	 * @deprecated 1.9
	 */
	public function getCalendarEndTime() {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);
		return (int)$this->calendar_end;
	}

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Returns an array of fields which can be exported.
	 *
	 * @return array
	 * @deprecated 1.9 Use toObject()
	 */
	public function getExportableValues() {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated by toObject()', 1.9);
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
	 * Override if you wish to return more information than can be found in
	 * $this->attributes (shouldn't happen)
	 *
	 * @return array
	 * @deprecated 1.9
	 */
	public function export() {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);
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
			$meta = null;

			if (in_array($k, $exportable_values)) {
				switch ($k) {
					case 'guid':			// Dont use guid in OpenDD
					case 'type':			// Type and subtype already taken care of
					case 'subtype':
						break;

					case 'time_created':	// Created = published
						$odd->setAttribute('published', date("r", $v));
						break;

					case 'site_guid':	// Container
						$k = 'site_uuid';
						$v = guid_to_uuid($v);
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
						break;

					case 'container_guid':	// Container
						$k = 'container_uuid';
						$v = guid_to_uuid($v);
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
						break;

					case 'owner_guid':			// Convert owner guid to uuid, this will be stored in metadata
						$k = 'owner_uuid';
						$v = guid_to_uuid($v);
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
						break;

					default:
						$meta = new ODDMetaData($uuid . "attr/$k/", $uuid, $k, $v);
				}

				// set the time of any metadata created
				if ($meta) {
					$meta->setAttribute('published', date("r", $this->time_created));
					$tmp[] = $meta;
				}
			}
		}

		// Now we do something a bit special.
		/*
		 * This provides a rendered view of the entity to foreign sites.
		 */

		elgg_set_viewtype('default');
		$view = elgg_view_entity($this, array('full_view' => true));
		elgg_set_viewtype();

		$tmp[] = new ODDMetaData($uuid . "volatile/renderedentity/", $uuid,
			'renderedentity', $view, 'volatile');

		return $tmp;
	}

	/*
	 * IMPORTABLE INTERFACE
	 */

	/**
	 * Import data from an parsed ODD xml data array.
	 *
	 * @param ODD $data XML data
	 *
	 * @return true
	 *
	 * @throws InvalidParameterException
	 * @deprecated 1.9 Use toObject()
	 */
	public function import(ODD $data) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated', 1.9);
		if (!($data instanceof ODDEntity)) {
			throw new InvalidParameterException("import() passed an unexpected ODD class");
		}

		// Set type and subtype
		$this->attributes['type'] = $data->getAttribute('class');
		$this->attributes['subtype'] = $data->getAttribute('subclass');

		// Set owner
		$this->attributes['owner_guid'] = elgg_get_logged_in_user_guid(); // Import as belonging to importer.

		// Set time
		$this->attributes['time_created'] = strtotime($data->getAttribute('published'));
		$this->attributes['time_updated'] = time();

		return true;
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
		return $this->getGUID();
	}

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the system log. It can be called on any Loggable object.
	 *
	 * @param int $id GUID.
	 * @return int GUID
	 */
	public function getObjectFromID($id) {
		return get_entity($id);
	}

	/**
	 * Returns tags for this entity.
	 *
	 * @warning Tags must be registered by {@link elgg_register_tag_metadata_name()}.
	 *
	 * @param array $tag_names Optionally restrict by tag metadata names.
	 *
	 * @return array
	 */
	public function getTags($tag_names = null) {
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
}
