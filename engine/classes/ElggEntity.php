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
 * to be metadata.  See ElggObject::initialise_entities() for examples.
 *
 * Core supports 4 types of entities: ElggObject, ElggUser, ElggGroup, and
 * ElggSite.
 *
 * @tip Most plugin authors will want to extend the ElggObject class
 * instead of this class.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Entities
 * @link       http://docs.elgg.org/DataModel/ElggEntity
 * 
 * @property string $type           object, user, group, or site (read-only after save)
 * @property string $subtype        Further clarifies the nature of the entity (read-only after save)
 * @property int    $guid           The unique identifier for this entity (read only)
 * @property int    $owner_guid     The GUID of the creator of this entity
 * @property int    $container_guid The GUID of the entity containing this entity
 * @property int    $site_guid      The GUID of the website this entity is associated with
 * @property int    $access_id      Specifies the visibility level of this entity
 * @property int    $time_created   A UNIX timestamp of when the entity was created (read-only, set on first save)
 * @property int    $time_updated   A UNIX timestamp of when the entity was last updated (automatically updated on save)
 */
abstract class ElggEntity extends ElggData implements
	Notable,    // Calendar interface
	Locatable,  // Geocoding interface
	Importable // Allow import of data
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
	 * Initialize the attributes array.
	 *
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['guid'] = NULL;
		$this->attributes['type'] = NULL;
		$this->attributes['subtype'] = NULL;

		$this->attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$this->attributes['container_guid'] = elgg_get_logged_in_user_guid();

		$this->attributes['site_guid'] = NULL;
		$this->attributes['access_id'] = ACCESS_PRIVATE;
		$this->attributes['time_created'] = NULL;
		$this->attributes['time_updated'] = NULL;
		$this->attributes['last_action'] = NULL;
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
		$this->attributes['tables_split'] = 1;
		$this->attributes['tables_loaded'] = 0;
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
				$this->set($name, $orig_entity->$name);
			}
		}
	}

	/**
	 * Return the value of a property.
	 *
	 * If $name is defined in $this->attributes that value is returned, otherwise it will
	 * pull from the entity's metadata.
	 *
	 * Q: Why are we not using __get overload here?
	 * A: Because overload operators cause problems during subclassing, so we put the code here and
	 * create overloads in subclasses.
	 *
	 * @todo What problems are these?
	 *
	 * @warning Subtype is returned as an id rather than the subtype string. Use getSubtype()
	 * to get the subtype string.
	 *
	 * @param string $name Name
	 *
	 * @return mixed Returns the value of a given value, or null.
	 */
	public function get($name) {
		// See if its in our base attributes
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}

		// No, so see if its in the meta data for this entity
		$meta = $this->getMetaData($name);

		// getMetaData returns NULL if $name is not found
		return $meta;
	}

	/**
	 * Sets the value of a property.
	 *
	 * If $name is defined in $this->attributes that value is set, otherwise it will
	 * set the appropriate item of metadata.
	 *
	 * @warning It is important that your class populates $this->attributes with keys
	 * for all base attributes, anything not in their gets set as METADATA.
	 *
	 * Q: Why are we not using __set overload here?
	 * A: Because overload operators cause problems during subclassing, so we put the code here and
	 * create overloads in subclasses.
	 *
	 * @todo What problems?
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return bool
	 */
	public function set($name, $value) {
		if (array_key_exists($name, $this->attributes)) {
			// Certain properties should not be manually changed!
			switch ($name) {
				case 'guid':
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
	 * Return the value of a piece of metadata.
	 *
	 * @param string $name Name
	 *
	 * @return mixed The value, or NULL if not found.
	 */
	public function getMetaData($name) {
		if ((int) ($this->guid) == 0) {
			if (isset($this->temp_metadata[$name])) {
				return $this->temp_metadata[$name];
			} else {
				return null;
			}
		}

		$md = elgg_get_metadata(array(
			'guid' => $this->getGUID(),
			'metadata_name' => $name,
			'limit' => 0,
		));

		if ($md && !is_array($md)) {
			return $md->value;
		} elseif (count($md) == 1) {
			return $md[0]->value;
		} else if ($md && is_array($md)) {
			return metadata_array_to_values($md);
		}

		return null;
	}

	/**
	 * Unset a property from metadata or attribute.
	 *
	 * @warning If you use this to unset an attribute, you must save the object!
	 *
	 * @param string $name The name of the attribute or metadata.
	 *
	 * @return void
	 */
	function __unset($name) {
		if (array_key_exists($name, $this->attributes)) {
			$this->attributes[$name] = "";
		} else {
			$this->deleteMetadata($name);
		}
	}

	/**
	 * Set a piece of metadata.
	 *
	 * @tip Plugin authors should use the magic methods.
	 *
	 * @access private
	 *
	 * @param string $name       Name of the metadata
	 * @param mixed  $value      Value of the metadata
	 * @param string $value_type Types supported: integer and string. Will auto-identify if not set
	 * @param bool   $multiple   Allow multiple values for a single name (doesn't support assoc arrays)
	 *
	 * @return bool
	 */
	public function setMetaData($name, $value, $value_type = "", $multiple = false) {
		$delete_first = false;
		// if multiple is set that always means don't delete.
		// if multiple isn't set it means override. set it to true on arrays for the foreach.
		if (!$multiple) {
			$delete_first = true;
			$multiple = is_array($value);
		}

		if (!$this->guid) {
			// real metadata only returns as an array if there are multiple elements
			if (is_array($value) && count($value) == 1) {
				$value = $value[0];
			}

			$value_is_array = is_array($value);

			if (!isset($this->temp_metadata[$name]) || $delete_first) {
				// need to remove the indexes because real metadata doesn't have them.
				if ($value_is_array) {
					$this->temp_metadata[$name] = array_values($value);
				} else {
					$this->temp_metadata[$name] = $value;
				}
			} else {
				// multiple is always true at this point.
				// if we're setting multiple and temp isn't array, it needs to be.
				if (!is_array($this->temp_metadata[$name])) {
					$this->temp_metadata[$name] = array($this->temp_metadata[$name]);
				}

				if ($value_is_array) {
					$this->temp_metadata[$name] = array_merge($this->temp_metadata[$name], array_values($value));
				} else {
					$this->temp_metadata[$name][] = $value;
				}
			}
		} else {
			if ($delete_first) {
				$options = array(
					'guid' => $this->getGUID(),
					'metadata_name' => $name,
					'limit' => 0
				);
				// @todo this doesn't check if it exists so we can't handle failed deletes
				// is it worth the overhead of more SQL calls to check?
				elgg_delete_metadata($options);
			}
			// save into real metadata
			if (!is_array($value)) {
				$value = array($value);
			}
			foreach ($value as $v) {
				$result = create_metadata($this->getGUID(), $name, $v, $value_type,
					$this->getOwnerGUID(), $this->getAccessId(), $multiple);

				if (!$result) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Deletes all metadata on this object (metadata.entity_guid = $this->guid).
	 * If you pass a name, only metadata matching that name will be deleted.
	 *
	 * @warning Calling this with no or empty arguments will clear all metadata on the entity.
	 *
	 * @param null|string $name The metadata name to remove.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteMetadata($name = null) {
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
	public function clearMetaData($name = '') {
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
	 * @return mixed The value or NULL if not found.
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
	 *
	 * @return true
	 * @todo This should actually return if it worked.
	 * @see ElggEntity::addRelationship()
	 * @see ElggEntity::removeRelationship()
	 */
	public function deleteRelationships() {
		remove_entity_relationships($this->getGUID());
		remove_entity_relationships($this->getGUID(), "", true);
		return true;
	}

	/**
	 * Remove all relationships to and from this entity.
	 *
	 * @return bool
	 * @see ElggEntity::addRelationship()
	 * @see ElggEntity::removeRelationship()
	 * @deprecated 1.8 Use ->deleteRelationship()
	 */
	public function clearRelationships() {
		elgg_deprecated_notice('ElggEntity->clearRelationships() is deprecated by ->deleteRelationships()', 1.8);
		return $this->deleteRelationships();
	}

	/**
	 * Add a relationship between this an another entity.
	 *
	 * @tip Read the relationship like "$guid is a $relationship of this entity."
	 *
	 * @param int    $guid         Entity to link to.
	 * @param string $relationship The type of relationship.
	 *
	 * @return bool
	 * @see ElggEntity::removeRelationship()
	 * @see ElggEntity::clearRelationships()
	 */
	public function addRelationship($guid, $relationship) {
		return add_entity_relationship($this->getGUID(), $relationship, $guid);
	}

	/**
	 * Remove a relationship
	 *
	 * @param int $guid         GUID of the entity to make a relationship with
	 * @param str $relationship Name of relationship
	 *
	 * @return bool
	 * @see ElggEntity::addRelationship()
	 * @see ElggEntity::clearRelationships()
	 */
	public function removeRelationship($guid, $relationship) {
		return remove_entity_relationship($this->getGUID(), $relationship, $guid);
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
	 * @link http://docs.elgg.org/DataModel/Entities/PrivateSettings
	 */
	function setPrivateSetting($name, $value) {
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
	 * @return mixed
	 */
	function getPrivateSetting($name) {
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
	function removePrivateSetting($name) {
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
	 * @param string $name      Annotation name
	 * @param mixed  $value     Annotation value
	 * @param int    $access_id Access ID
	 * @param int    $owner_id  GUID of the annotation owner
	 * @param string $vartype   The type of annotation value
	 *
	 * @return bool
	 *
	 * @link http://docs.elgg.org/DataModel/Annotations
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
	 * Returns an array of annotations.
	 *
	 * @param string $name   Annotation name
	 * @param int    $limit  Limit
	 * @param int    $offset Offset
	 * @param string $order  Order by time: asc or desc
	 *
	 * @return array
	 */
	function getAnnotations($name, $limit = 50, $offset = 0, $order = "asc") {
		if ((int) ($this->guid) > 0) {

			$options = array(
				'guid' => $this->guid,
				'annotation_name' => $name,
				'limit' => $limit,
				'offset' => $offset,
			);

			if ($order != 'asc') {
				$options['reverse_order_by'] = true;
			}

			return elgg_get_annotations($options);
		} else if (isset($this->temp_annotations[$name])) {
			return array($this->temp_annotations[$name]);
		} else {
			return array();
		}
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
	function clearAnnotations($name = "") {
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
	function countAnnotations($name = "") {
		return $this->getAnnotationCalculation($name, 'count');
	}

	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	function getAnnotationsAvg($name) {
		return $this->getAnnotationCalculation($name, 'avg');
	}

	/**
	 * Get the sum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	function getAnnotationsSum($name) {
		return $this->getAnnotationCalculation($name, 'sum');
	}

	/**
	 * Get the minimum of integer type annotations of given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	function getAnnotationsMin($name) {
		return $this->getAnnotationCalculation($name, 'min');
	}

	/**
	 * Get the maximum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	function getAnnotationsMax($name) {
		return $this->getAnnotationCalculation($name, 'max');
	}

	/**
	 * Count the number of comments attached to this entity.
	 *
	 * @return int Number of comments
	 * @since 1.8.0
	 */
	function countComments() {
		$params = array('entity' => $this);
		$num = elgg_trigger_plugin_hook('comments:count', $this->getType(), $params);

		if (is_int($num)) {
			return $num;
		} else {
			return $this->getAnnotationCalculation('generic_comment', 'count');
		}
	}

	/**
	 * Gets an array of entities with a relationship to this entity.
	 *
	 * @param string $relationship Relationship type (eg "friends")
	 * @param bool   $inverse      Is this an inverse relationship?
	 * @param int    $limit        Number of elements to return
	 * @param int    $offset       Indexing offset
	 *
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
	 * @param string $relationship         Relationship type (eg "friends")
	 * @param bool   $inverse_relationship Invert relationship
	 *
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
	 * Can a user edit this entity.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool
	 */
	function canEdit($user_guid = 0) {
		return can_edit_entity($this->getGUID(), $user_guid);
	}

	/**
	 * Can a user edit metadata on this entity
	 *
	 * @param ElggMetadata $metadata  The piece of metadata to specifically check
	 * @param int          $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return true|false
	 */
	function canEditMetadata($metadata = null, $user_guid = 0) {
		return can_edit_entity_metadata($this->getGUID(), $user_guid, $metadata);
	}

	/**
	 * Can a user add an entity to this container
	 *
	 * @param int    $user_guid The user.
	 * @param string $type      The type of entity we're looking to write
	 * @param string $subtype   The subtype of the entity we're looking to write
	 *
	 * @return bool
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
		return $this->get('access_id');
	}

	/**
	 * Returns the guid.
	 *
	 * @return int GUID
	 */
	public function getGUID() {
		return $this->get('guid');
	}

	/**
	 * Returns the entity type
	 *
	 * @return string Entity type
	 */
	public function getType() {
		return $this->get('type');
	}

	/**
	 * Returns the entity subtype string
	 *
	 * @note This returns a string.  If you want the id, use ElggEntity::subtype.
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
	 * Get the guid of the entity's owner.
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
		$container_guid = (int)$container_guid;

		return $this->set('container_guid', $container_guid);
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
		$container_guid = (int)$container_guid;

		return $this->set('container_guid', $container_guid);
	}

	/**
	 * Gets the container GUID for this entity.
	 *
	 * @return int
	 */
	public function getContainerGUID() {
		return $this->get('container_guid');
	}

	/**
	 * Gets the container GUID for this entity.
	 *
	 * @return int
	 * @deprecated 1.8 Use getContainerGUID()
	 */
	public function getContainer() {
		elgg_deprecated_notice("ElggObject::getContainer deprecated for ElggEntity::getContainerGUID", 1.8);
		return $this->get('container_guid');
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
		return $this->get('time_updated');
	}

	/**
	 * Returns the URL for this entity
	 *
	 * @return string The URL
	 * @see register_entity_url_handler()
	 * @see ElggEntity::setURL()
	 */
	public function getURL() {
		if (!empty($this->url_override)) {
			return $this->url_override;
		}
		return get_entity_url($this->getGUID());
	}

	/**
	 * Overrides the URL returned by getURL()
	 *
	 * @warning This override exists only for the life of the object.
	 *
	 * @param string $url The new item URL
	 *
	 * @return string The URL
	 */
	public function setURL($url) {
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

		$url = "_graphics/icons/default/$size.png";
		$url = elgg_normalize_url($url);

		$type = $this->getType();
		$params = array(
			'entity' => $this,
			'size' => $size,
		);

		$url = elgg_trigger_plugin_hook('entity:icon:url', $type, $params, $url);

		return elgg_normalize_url($url);
	}

	/**
	 * Returns a URL for the entity's icon.
	 *
	 * @param string $size Either 'large', 'medium', 'small' or 'tiny'
	 *
	 * @return string The url or false if no url could be worked out.
	 * @deprecated Use getIconURL()
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
	 * Tests to see whether the object has been fully loaded.
	 *
	 * @return bool
	 */
	public function isFullyLoaded() {
		return ! ($this->attributes['tables_loaded'] < $this->attributes['tables_split']);
	}

	/**
	 * Save an entity.
	 *
	 * @return bool/int
	 * @throws IOException
	 */
	public function save() {
		$guid = (int) $this->guid;
		if ($guid > 0) {
			cache_entity($this);

			return update_entity(
				$this->get('guid'),
				$this->get('owner_guid'),
				$this->get('access_id'),
				$this->get('container_guid'),
				$this->get('time_created')
			);
		} else {
			// Create a new entity (nb: using attribute array directly
			// 'cos set function does something special!)
			$this->attributes['guid'] = create_entity($this->attributes['type'],
				$this->attributes['subtype'], $this->attributes['owner_guid'],
				$this->attributes['access_id'], $this->attributes['site_guid'],
				$this->attributes['container_guid']);

			if (!$this->attributes['guid']) {
				throw new IOException(elgg_echo('IOException:BaseEntitySaveFailed'));
			}

			// Save any unsaved metadata
			// @todo How to capture extra information (access id etc)
			if (sizeof($this->temp_metadata) > 0) {
				foreach ($this->temp_metadata as $name => $value) {
					$this->$name = $value;
					unset($this->temp_metadata[$name]);
				}
			}

			// Save any unsaved annotations.
			if (sizeof($this->temp_annotations) > 0) {
				foreach ($this->temp_annotations as $name => $value) {
					$this->annotate($name, $value);
					unset($this->temp_annotations[$name]);
				}
			}

			// Save any unsaved private settings.
			if (sizeof($this->temp_private_settings) > 0) {
				foreach ($this->temp_private_settings as $name => $value) {
					$this->setPrivateSetting($name, $value);
					unset($this->temp_private_settings[$name]);
				}
			}

			// set the subtype to id now rather than a string
			$this->attributes['subtype'] = get_subtype_id($this->attributes['type'],
				$this->attributes['subtype']);

			// Cache object handle
			if ($this->attributes['guid']) {
				cache_entity($this);
			}

			return $this->attributes['guid'];
		}
	}

	/**
	 * Loads attributes from the entities table into the object.
	 *
	 * @param int $guid GUID of Entity
	 *
	 * @return bool
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
			foreach ($objarray as $key => $value) {
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
	 * Disabled entities are not returned by getter functions.
	 * To enable an entity, use {@link enable_entity()}.
	 *
	 * Recursively disabling an entity will disable all entities
	 * owned or contained by the parent entity.
	 *
	 * @internal Disabling an entity sets the 'enabled' column to 'no'.
	 *
	 * @param string $reason    Optional reason
	 * @param bool   $recursive Recursively disable all contained entities?
	 *
	 * @return bool
	 * @see enable_entity()
	 * @see ElggEntity::enable()
	 */
	public function disable($reason = "", $recursive = true) {
		if ($r = disable_entity($this->get('guid'), $reason, $recursive)) {
			$this->attributes['enabled'] = 'no';
		}

		return $r;
	}

	/**
	 * Enable an entity
	 *
	 * @warning Disabled entities can't be loaded unless
	 * {@link access_show_hidden_entities(true)} has been called.
	 *
	 * @see enable_entity()
	 * @see access_show_hiden_entities()
	 * @return bool
	 */
	public function enable() {
		if ($r = enable_entity($this->get('guid'))) {
			$this->attributes['enabled'] = 'yes';
		}

		return $r;
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
	 *
	 * @param bool $recursive Whether to delete all the entities contained by this entity
	 *
	 * @return bool
	 */
	public function delete($recursive = true) {
		return delete_entity($this->get('guid'), $recursive);
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
	 * @todo Unimplemented
	 *
	 * @param string $location String representation of the location
	 *
	 * @return true
	 */
	public function setLocation($location) {
		$location = sanitise_string($location);

		$this->location = $location;

		return true;
	}

	/**
	 * Set latitude and longitude metadata tags for a given entity.
	 *
	 * @param float $lat  Latitude
	 * @param float $long Longitude
	 *
	 * @return true
	 * @todo Unimplemented
	 */
	public function setLatLong($lat, $long) {
		$lat = sanitise_string($lat);
		$long = sanitise_string($long);

		$this->set('geo:lat', $lat);
		$this->set('geo:long', $long);

		return true;
	}

	/**
	 * Return the entity's latitude.
	 *
	 * @return int
	 * @todo Unimplemented
	 */
	public function getLatitude() {
		return $this->get('geo:lat');
	}

	/**
	 * Return the entity's longitude
	 *
	 * @return Int
	 */
	public function getLongitude() {
		return $this->get('geo:long');
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
	 * @todo Unimplemented
	 */
	public function setCalendarTimeAndDuration($hour = NULL, $minute = NULL, $second = NULL,
	$day = NULL, $month = NULL, $year = NULL, $duration = NULL) {

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
	 * @todo Unimplemented
	 */
	public function getCalendarStartTime() {
		return (int)$this->calendar_start;
	}

	/**
	 * Returns the end timestamp.
	 *
	 * @todo Unimplemented
	 *
	 * @return int
	 */
	public function getCalendarEndTime() {
		return (int)$this->calendar_end;
	}

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Returns an array of fields which can be exported.
	 *
	 * @return array
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
	 * Override if you wish to return more information than can be found in
	 * $this->attributes (shouldn't happen)
	 *
	 * @return array
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
		$view = elgg_view_entity($this, true);
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
	 * @param array $data XML data
	 *
	 * @return true
	 */
	public function import(ODD $data) {
		if (!($data instanceof ODDEntity)) {
			throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnexpectedODDClass'));
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
	 * This is used by the river functionality primarily.
	 *
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id GUID.
	 *
	 * @todo How is this any different or more useful than get_entity($guid)
	 * or new ElggEntity($guid)?
	 *
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
}
