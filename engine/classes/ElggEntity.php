<?php

use Elgg\EntityIcon;
use Elgg\Database\QueryBuilder;

/**
 * The parent class for all Elgg Entities.
 *
 * An \ElggEntity is one of the basic data models in Elgg.
 * It is the primary means of storing and retrieving data from the database.
 * An \ElggEntity represents one row of the entities table.
 *
 * The \ElggEntity class handles CRUD operations for the entities table.
 * \ElggEntity should always be extended by another class to handle CRUD
 * operations on the type-specific table.
 *
 * \ElggEntity uses magic methods for get and set, so any property that isn't
 * declared will be assumed to be metadata and written to the database
 * as metadata on the object.  All children classes must declare which
 * properties are columns of the type table or they will be assumed
 * to be metadata.  See \ElggObject::initializeAttributes() for examples.
 *
 * Core supports 4 types of entities: \ElggObject, \ElggUser, \ElggGroup, and \ElggSite.
 *
 * @tip Plugin authors will want to extend the \ElggObject class, not this class.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Entities
 *
 * @property       string $type           object, user, group, or site (read-only after save)
 * @property       string $subtype        Further clarifies the nature of the entity
 * @property-read  int    $guid           The unique identifier for this entity (read only)
 * @property       int    $owner_guid     The GUID of the owner of this entity (usually the creator)
 * @property       int    $container_guid The GUID of the entity containing this entity
 * @property       int    $access_id      Specifies the visibility level of this entity
 * @property       int    $time_created   A UNIX timestamp of when the entity was created
 * @property-read  int    $time_updated   A UNIX timestamp of when the entity was last updated (automatically updated on save)
 * @property-read  int    $last_action    A UNIX timestamp of when the entity was last acted upon
 * @property       string $enabled        Is this entity enabled ('yes' or 'no')
 *
 * Metadata (the above are attributes)
 * @property       string $location       A location of the entity
 */
abstract class ElggEntity extends \ElggData implements
	Locatable, // Geocoding interface
	EntityIcon // Icon interface
{

	public static $primary_attr_names = [
		'guid',
		'type',
		'subtype',
		'owner_guid',
		'container_guid',
		'access_id',
		'time_created',
		'time_updated',
		'last_action',
		'enabled',
	];

	protected static $integer_attr_names = [
		'guid',
		'owner_guid',
		'container_guid',
		'access_id',
		'time_created',
		'time_updated',
		'last_action',
	];

	/**
	 * Holds metadata until entity is saved.  Once the entity is saved,
	 * metadata are written immediately to the database.
	 * @var array
	 */
	protected $temp_metadata = [];

	/**
	 * Holds annotations until entity is saved.  Once the entity is saved,
	 * annotations are written immediately to the database.
	 * @var array
	 */
	protected $temp_annotations = [];

	/**
	 * Holds private settings until entity is saved. Once the entity is saved,
	 * private settings are written immediately to the database.
	 * @var array
	 */
	protected $temp_private_settings = [];

	/**
	 * Volatile data structure for this object, allows for storage of data
	 * in-memory that isn't sync'd back to the metadata table.
	 * @var array
	 */
	protected $volatile = [];

	/**
	 * Holds the original (persisted) attribute values that have been changed but not yet saved.
	 * @var array
	 */
	protected $orig_attributes = [];

	/**
	 * @var bool
	 */
	protected $_is_cacheable = true;

	/**
	 * Holds metadata key/value pairs acquired from the metadata cache
	 * Entity metadata may have mutated since last call to __get,
	 * do not rely on this value for any business logic
	 * This storage is intended to help with debugging objects during dump,
	 * because otherwise it's hard to tell what the object is from it's attributes
	 *
	 * @var array
	 * @internal
	 */
	protected $_cached_metadata;

	/**
	 * Create a new entity.
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * If no arguments are passed, it creates a new entity.
	 * If a database result is passed as a \stdClass instance, it instantiates
	 * that entity.
	 *
	 * @param stdClass $row Database row result. Default is null to create a new object.
	 *
	 * @throws IOException If cannot load remaining data from db
	 */
	public function __construct(stdClass $row = null) {
		$this->initializeAttributes();

		if ($row && !$this->load($row)) {
			$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
			throw new \IOException($msg);
		}
	}

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

		$this->attributes['owner_guid'] = _elgg_services()->session->getLoggedInUserGuid();
		$this->attributes['container_guid'] = _elgg_services()->session->getLoggedInUserGuid();

		$this->attributes['access_id'] = ACCESS_PRIVATE;
		$this->attributes['time_updated'] = null;
		$this->attributes['last_action'] = null;
		$this->attributes['enabled'] = "yes";

		$this->attributes['type'] = $this->getType();
	}

	/**
	 * Clone an entity
	 *
	 * Resets the guid so that the entity can be saved as a distinct entity from
	 * the original. Creation time will be set when this new entity is saved.
	 * The owner and container guids come from the original entity. The clone
	 * method copies metadata but does not copy annotations or private settings.
	 *
	 * @return void
	 */
	public function __clone() {
		$orig_entity = get_entity($this->guid);
		if (!$orig_entity) {
			_elgg_services()->logger->error("Failed to clone entity with GUID $this->guid");
			return;
		}

		$metadata_array = elgg_get_metadata([
			'guid' => $this->guid,
			'limit' => 0
		]);

		$this->attributes['guid'] = null;
		$this->attributes['time_created'] = null;
		$this->attributes['time_updated'] = null;
		$this->attributes['last_action'] = null;

		$this->attributes['subtype'] = $orig_entity->getSubtype();

		// copy metadata over to new entity - slightly convoluted due to
		// handling of metadata arrays
		if (is_array($metadata_array)) {
			// create list of metadata names
			$metadata_names = [];
			foreach ($metadata_array as $metadata) {
				$metadata_names[] = $metadata->name;
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
	 * Be advised that metadata values are cast to integer or string.
	 * You can save booleans, but they will be stored and returned as integers.
	 *
	 * @param string $name  Name of the attribute or metadata
	 * @param mixed  $value The value to be set
	 * @return void
	 * @see \ElggEntity::setMetadata()
	 */
	public function __set($name, $value) {
		if ($this->$name === $value) {
			// quick return if value is not changing
			return;
		}

		if (array_key_exists($name, $this->attributes)) {
			// if an attribute is 1 (integer) and it's set to "1" (string), don't consider that a change.
			if (is_int($this->attributes[$name])
					&& is_string($value)
					&& ((string) $this->attributes[$name] === $value)) {
				return;
			}

			// keep original values
			if ($this->guid && !array_key_exists($name, $this->orig_attributes)) {
				$this->orig_attributes[$name] = $this->attributes[$name];
			}

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
						$this->attributes[$name] = (int) $value;
					} else {
						$this->attributes[$name] = null;
					}
					break;
				default:
					$this->attributes[$name] = $value;
					break;
			}
			return;
		}

		$this->setMetadata($name, $value);
	}

	/**
	 * Get the original values of attribute(s) that have been modified since the entity was persisted.
	 *
	 * @return array
	 */
	public function getOriginalAttributes() {
		return $this->orig_attributes;
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
			return $this->attributes[$name];
		}

		return $this->getMetadata($name);
	}

	/**
	 * Get the entity's display name
	 *
	 * @return string The title or name of this entity.
	 */
	public function getDisplayName() {
		return $this->name;
	}

	/**
	 * Sets the title or name of this entity.
	 *
	 * @param string $display_name The title or name of this entity.
	 * @return void
	 */
	public function setDisplayName($display_name) {
		$this->name = $display_name;
	}

	/**
	 * Return the value of a piece of metadata.
	 *
	 * @param string $name Name
	 *
	 * @return mixed The value, or null if not found.
	 */
	public function getMetadata($name) {
		$metadata = $this->getAllMetadata();
		return elgg_extract($name, $metadata);
	}

	/**
	 * Get all entity metadata
	 *
	 * @return array
	 */
	public function getAllMetadata() {
		if (!$this->guid) {
			return array_map(function($values) {
				return count($values) > 1 ? $values : $values[0];
			}, $this->temp_metadata);
		}

		$this->_cached_metadata = _elgg_services()->metadataCache->getAll($this->guid);

		return $this->_cached_metadata;
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
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function setMetadata($name, $value, $value_type = '', $multiple = false) {

		// normalize value to an array that we will loop over
		// remove indexes if value already an array.
		if (is_array($value)) {
			$value = array_values($value);
		} else {
			$value = [$value];
		}

		// strip null values from array
		$value = array_filter($value, function($var) {
			return !is_null($var);
		});

		if (empty($this->guid)) {
			// unsaved entity. store in temp array
			return $this->setTempMetadata($name, $value, $multiple);
		}

		// saved entity. persist md to db.
		if (!$multiple) {
			$current_metadata = $this->getMetadata($name);

			if ((is_array($current_metadata) || count($value) > 1 || $value === []) && isset($current_metadata)) {
				// remove current metadata if needed
				// need to remove access restrictions right now to delete
				// because this is the expected behavior
				$delete_result = elgg_call(ELGG_IGNORE_ACCESS, function() use ($name) {
					return elgg_delete_metadata([
						'guid' => $this->guid,
						'metadata_name' => $name,
						'limit' => false,
					]);
				});

				if (false === $delete_result) {
					return false;
				}
			}

			if (count($value) > 1) {
				// new value is a multiple valued metadata
				$multiple = true;
			}
		}

		// create new metadata
		foreach ($value as $value_tmp) {
			$metadata = new ElggMetadata();
			$metadata->entity_guid = $this->guid;
			$metadata->name = $name;
			$metadata->value_type = $value_type;
			$metadata->value = $value_tmp;
			$md_id = _elgg_services()->metadataTable->create($metadata, $multiple);
			if (!$md_id) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Set temp metadata on this entity.
	 *
	 * @param string $name     Name of the metadata
	 * @param mixed  $value    Value of the metadata (doesn't support assoc arrays)
	 * @param bool   $multiple Allow multiple values for a single name.
	 *                         Does not support associative arrays.
	 *
	 * @return bool
	 */
	protected function setTempMetadata($name, $value, $multiple = false) {
		// if overwrite, delete first
		if (!$multiple) {
			unset($this->temp_metadata[$name]);
			if (count($value)) {
				// only save if value array contains data
				$this->temp_metadata[$name] = $value;
			}
			return true;
		}

		if (!isset($this->temp_metadata[$name])) {
			$this->temp_metadata[$name] = [];
		}

		$this->temp_metadata[$name] = array_merge($this->temp_metadata[$name], $value);

		return true;
	}



	/**
	 * Deletes all metadata on this object (metadata.entity_guid = $this->guid).
	 * If you pass a name, only metadata matching that name will be deleted.
	 *
	 * @warning Calling this with no $name will clear all metadata on the entity.
	 *
	 * @param null|string $name The name of the metadata to remove.
	 * @return bool|null
	 * @since 1.8
	 */
	public function deleteMetadata($name = null) {

		if (!$this->guid) {
			// remove from temp_metadata
			if ($name) {
				if (!isset($this->temp_metadata[$name])) {
					return null;
				} else {
					unset($this->temp_metadata[$name]);
					return true;
				}
			} else {
				$this->temp_metadata = [];
				return true;
			}
		}

		$options = [
			'guid' => $this->guid,
			'limit' => 0
		];
		if ($name) {
			$options['metadata_name'] = $name;
		}

		return elgg_delete_metadata($options);
	}

	/**
	 * Get a piece of volatile (non-persisted) data on this entity.
	 *
	 * @param string $name The name of the volatile data
	 *
	 * @return mixed The value or null if not found.
	 */
	public function getVolatileData($name) {
		return array_key_exists($name, $this->volatile) ? $this->volatile[$name] : null;
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
	 * @see \ElggEntity::addRelationship()
	 * @see \ElggEntity::removeRelationship()
	 */
	public function deleteRelationships($relationship = null) {
		$relationship = (string) $relationship;
		$result = remove_entity_relationships($this->getGUID(), $relationship);
		return $result && remove_entity_relationships($this->getGUID(), $relationship, true);
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
	 * @see \ElggEntity::removeRelationship()
	 * @see \ElggEntity::deleteRelationships()
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
	 * @see \ElggEntity::addRelationship()
	 * @see \ElggEntity::deleteRelationships()
	 */
	public function removeRelationship($guid_two, $relationship) {
		return remove_entity_relationship($this->getGUID(), $relationship, $guid_two);
	}

	/**
	 * Adds a private setting to this entity.
	 *
	 * @warning Private settings are stored as strings
	 *          Unlike metadata and annotations there is no reverse casting when you retrieve the setting
	 *          When saving integers, they will be cast to strings
	 *          Booleans will be cast to '0' and '1'
	 *
	 * @param string $name  Name of private setting
	 * @param mixed  $value Value of private setting
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function setPrivateSetting($name, $value) {
		if (is_bool($value)) {
			$value = (int) $value;
		}

		if (!$this->guid) {
			$this->temp_private_settings[$name] = $value;

			return true;
		}

		return _elgg_services()->privateSettings->set($this, $name, $value);
	}

	/**
	 * Returns a private setting value
	 *
	 * @warning Private settings are always returned as strings
	 *          Make sure you can your values back to expected type
	 *
	 * @param string $name Name of the private setting
	 *
	 * @return string|null
	 * @throws DatabaseException
	 */
	public function getPrivateSetting($name) {
		if (!$this->guid) {
			return elgg_extract($name, $this->temp_private_settings);
		}

		return _elgg_services()->privateSettings->get($this, $name);
	}

	/**
	 * Returns all private settings
	 *
	 * @return array
	 * @throws DatabaseException
	 */
	public function getAllPrivateSettings() {
		if (!$this->guid) {
			return $this->temp_private_settings;
		}

		return _elgg_services()->privateSettings->getAllForEntity($this);
	}

	/**
	 * Removes private setting
	 *
	 * @param string $name Name of the private setting
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function removePrivateSetting($name) {
		if (!$this->guid) {
			unset($this->temp_private_settings[$name]);
			return true;
		}

		return _elgg_services()->privateSettings->remove($this, $name);
	}

	/**
	 * Removes all private settings
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function removeAllPrivateSettings() {
		if (!$this->guid) {
			$this->temp_private_settings = [];
			return true;
		}

		return _elgg_services()->privateSettings->removeAllForEntity($this);
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
		$options = [
			'guid' => $this->guid,
			'limit' => 0
		];
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
		// no longer have access to an entity they created annotations on

		$flags = ELGG_IGNORE_ACCESS;
		$callback = function() use ($name) {
			return elgg_delete_annotations([
				'annotation_owner_guid' => $this->guid,
				'limit' => 0,
				'annotation_name' => $name,
			]);
		};

		return elgg_call($flags, $callback);
	}

	/**
	 * Disables annotations for this entity, optionally based on name.
	 *
	 * @param string $name An options name of annotations to disable.
	 * @return bool
	 * @since 1.8
	 */
	public function disableAnnotations($name = '') {
		$options = [
			'guid' => $this->guid,
			'limit' => 0
		];
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
		$options = [
			'guid' => $this->guid,
			'limit' => 0
		];
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
		$options = [
			'guid' => $this->getGUID(),
			'distinct' => false,
			'annotation_name' => $name,
			'annotation_calculation' => $calculation
		];

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
	 * @todo Update temp_annotations to store an instance of ElggAnnotation and simply call ElggAnnotation::save(),
	 *       after entity is saved
	 *
	 * @param string $name       Annotation name
	 * @param mixed  $value      Annotation value
	 * @param int    $access_id  Access ID
	 * @param int    $owner_guid GUID of the annotation owner
	 * @param string $value_type The type of annotation value
	 *
	 * @return bool|int Returns int if an annotation is saved
	 */
	public function annotate($name, $value, $access_id = ACCESS_PRIVATE, $owner_guid = 0, $value_type = "") {
		if ($this->guid) {
			if (!$owner_guid) {
				$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
			}
			$annotation = new ElggAnnotation();
			$annotation->entity_guid = $this->guid;
			$annotation->name = $name;
			$annotation->value_type = $value_type;
			$annotation->value = $value;
			$annotation->owner_guid = $owner_guid;
			$annotation->access_id = $access_id;
			return $annotation->save();
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
	 * @param array $options Array of options for elgg_get_annotations() except guid.
	 *
	 * @return array
	 * @see elgg_get_annotations()
	 */
	public function getAnnotations(array $options = []) {
		if ($this->guid) {
			$options['guid'] = $this->guid;

			return elgg_get_annotations($options);
		} else {
			$name = elgg_extract('annotation_name', $options, '');

			if (isset($this->temp_annotations[$name])) {
				return [$this->temp_annotations[$name]];
			}
		}

		return [];
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
		$params = ['entity' => $this];
		$num = _elgg_services()->hooks->trigger('comments:count', $this->getType(), $params);

		if (is_int($num)) {
			return $num;
		}

		return elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $this->getGUID(),
			'count' => true,
			'distinct' => false,
		]);
	}

	/**
	 * Returns the ACLs owned by the entity
	 *
	 * @param array $options additional options to get the access collections with
	 *
	 * @return \ElggAccessCollection[]
	 *
	 * @see elgg_get_access_collections()
	 * @since 3.0
	 */
	public function getOwnedAccessCollections($options = []) {
		$options['owner_guid'] = $this->guid;
		return _elgg_services()->accessCollections->getEntityCollections($options);
	}
	
	/**
	 * Returns the first ACL owned by the entity with a given subtype
	 *
	 * @param string $subtype subtype of the ACL
	 *
	 * @return \ElggAccessCollection|false
	 *
	 * @since 3.0
	 */
	public function getOwnedAccessCollection($subtype) {
		if (!is_string($subtype) || $subtype === '') {
			return false;
		}
		
		$acls = $this->getOwnedAccessCollections([
			'subtype' => $subtype,
		]);
		
		return elgg_extract(0, $acls, false);
	}

	/**
	 * Gets an array of entities with a relationship to this entity.
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity.
	 *
	 * @return array|false An array of entities or false on failure
	 * @see elgg_get_entities()
	 */
	public function getEntitiesFromRelationship(array $options = []) {
		$options['relationship_guid'] = $this->guid;
		return elgg_get_entities($options);
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
		return elgg_get_entities([
			'relationship' => $relationship,
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => $inverse_relationship,
			'count' => true
		]);
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
		return _elgg_services()->userCapabilities->canEdit($this, $user_guid);
	}

	/**
	 * Can a user delete this entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:delete plugin hook.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is deletable by the given user.
	 * @since 1.11
	 * @see elgg_set_ignore_access()
	 */
	public function canDelete($user_guid = 0) {
		return _elgg_services()->userCapabilities->canDelete($this, $user_guid);
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
	 * @param \ElggMetadata $metadata  The piece of metadata to specifically check or null for any metadata
	 * @param int           $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canEditMetadata($metadata = null, $user_guid = 0) {
		return _elgg_services()->userCapabilities->canEditMetadata($this, $user_guid, $metadata);
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
		return _elgg_services()->userCapabilities->canWriteToContainer($this, $user_guid, $type, $subtype);
	}

	/**
	 * Can a user comment on an entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:comment,
	 * <entity type> plugin hook.
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 * @return bool
	 */
	public function canComment($user_guid = 0, $default = null) {
		return _elgg_services()->userCapabilities->canComment($this, $user_guid, $default);
	}

	/**
	 * Can a user annotate an entity?
	 *
	 * @tip Can be overridden by registering for the plugin hook [permissions_check:annotate:<name>,
	 * <entity type>] or [permissions_check:annotate, <entity type>]. The hooks are called in that order.
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
		return _elgg_services()->userCapabilities->canAnnotate($this, $user_guid, $annotation_name);
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
		// this is just for the PHPUnit mocking framework
		return $this->type;
	}

	/**
	 * Get the entity subtype
	 *
	 * @return string The entity subtype
	 */
	public function getSubtype() {
		return $this->attributes['subtype'];
	}

	/**
	 * Get the guid of the entity's owner.
	 *
	 * @return int The owner GUID
	 */
	public function getOwnerGUID() {
		return (int) $this->owner_guid;
	}

	/**
	 * Gets the \ElggEntity that owns this entity.
	 *
	 * @return \ElggEntity The owning entity
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
		return $this->container_guid = (int) $container_guid;
	}

	/**
	 * Gets the container GUID for this entity.
	 *
	 * @return int
	 */
	public function getContainerGUID() {
		return (int) $this->container_guid;
	}

	/**
	 * Get the container entity for this object.
	 *
	 * @return \ElggEntity
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
		$url = elgg_generate_entity_url($this, 'view');

		$url = _elgg_services()->hooks->trigger('entity:url', $this->getType(), ['entity' => $this], $url);

		if (empty($url)) {
			return '';
		}

		return elgg_normalize_url($url);
	}

	/**
	 * Saves icons using an uploaded file as the source.
	 *
	 * @param string $input_name Form input name
	 * @param string $type       The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords     An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromUploadedFile($input_name, $type = 'icon', array $coords = []) {
		return _elgg_services()->iconService->saveIconFromUploadedFile($this, $input_name, $type, $coords);
	}

	/**
	 * Saves icons using a local file as the source.
	 *
	 * @param string $filename The full path to the local file
	 * @param string $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords   An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromLocalFile($filename, $type = 'icon', array $coords = []) {
		return _elgg_services()->iconService->saveIconFromLocalFile($this, $filename, $type, $coords);
	}

	/**
	 * Saves icons using a file located in the data store as the source.
	 *
	 * @param string $file   An ElggFile instance
	 * @param string $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromElggFile(\ElggFile $file, $type = 'icon', array $coords = []) {
		return _elgg_services()->iconService->saveIconFromElggFile($this, $file, $type, $coords);
	}

	/**
	 * Returns entity icon as an ElggIcon object
	 * The icon file may or may not exist on filestore
	 *
	 * @param string $size Size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return \ElggIcon
	 */
	public function getIcon($size, $type = 'icon') {
		return _elgg_services()->iconService->getIcon($this, $size, $type);
	}

	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function deleteIcon($type = 'icon') {
		return _elgg_services()->iconService->deleteIcon($this, $type);
	}

	/**
	 * Returns the timestamp of when the icon was changed.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return int|null A unix timestamp of when the icon was last changed, or null if not set.
	 */
	public function getIconLastChange($size, $type = 'icon') {
		return _elgg_services()->iconService->getIconLastChange($this, $size, $type);
	}

	/**
	 * Returns if the entity has an icon of the passed type.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function hasIcon($size, $type = 'icon') {
		return _elgg_services()->iconService->hasIcon($this, $size, $type);
	}

	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', <type> plugin hook
	 * to customize the icon for an entity.
	 *
	 * @param mixed $params A string defining the size of the icon (e.g. tiny, small, medium, large)
	 *                      or an array of parameters including 'size'
	 * @return string The URL
	 * @since 1.8.0
	 */
	public function getIconURL($params = []) {
		return _elgg_services()->iconService->getIconURL($this, $params);
	}

	/**
	 * Save an entity.
	 *
	 * @return bool|int
	 */
	public function save() {
		$guid = $this->guid;
		if ($guid > 0) {
			$guid = $this->update();
		} else {
			$guid = $this->create();
			if ($guid && !_elgg_services()->events->trigger('create', $this->type, $this)) {
				// plugins that return false to event don't need to override the access system
				elgg_call(ELGG_IGNORE_ACCESS, function() {
					return $this->delete();
				});
				return false;
			}
		}

		if ($guid) {
			$this->cache();
		}

		return $guid;
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

		$type = $this->attributes['type'];
		if (!in_array($type, \Elgg\Config::getEntityTypes())) {
			throw new \InvalidParameterException('Entity type must be one of the allowed types: '
					. implode(', ', \Elgg\Config::getEntityTypes()));
		}

		$subtype = $this->attributes['subtype'];
		if (!$subtype) {
			throw new \InvalidParameterException("All entities must have a subtype");
		}

		$owner_guid = (int) $this->attributes['owner_guid'];
		$access_id = (int) $this->attributes['access_id'];
		$now = $this->getCurrentTime()->getTimestamp();
		$time_created = isset($this->attributes['time_created']) ? (int) $this->attributes['time_created'] : $now;

		$container_guid = $this->attributes['container_guid'];
		if ($container_guid == 0) {
			$container_guid = $owner_guid;
			$this->attributes['container_guid'] = $container_guid;
		}
		$container_guid = (int) $container_guid;

		if ($access_id == ACCESS_DEFAULT) {
			throw new \InvalidParameterException('ACCESS_DEFAULT is not a valid access level. See its documentation in constants.php');
		}
		
		if ($access_id == ACCESS_FRIENDS) {
			throw new \InvalidParameterException('ACCESS_FRIENDS is not a valid access level. See its documentation in constants.php');
		}

		$user_guid = _elgg_services()->session->getLoggedInUserGuid();

		// If given an owner, verify it can be loaded
		if ($owner_guid) {
			$owner = $this->getOwnerEntity();
			if (!$owner) {
				_elgg_services()->logger->error("User $user_guid tried to create a ($type, $subtype), but the given"
					. " owner $owner_guid could not be loaded.");
				return false;
			}

			// If different owner than logged in, verify can write to container.

			if ($user_guid != $owner_guid && !$owner->canEdit() && !$owner->canWriteToContainer($user_guid, $type, $subtype)) {
				_elgg_services()->logger->error("User $user_guid tried to create a ($type, $subtype) with owner"
					. " $owner_guid, but the user wasn't permitted to write to the owner's container.");
				return false;
			}
		}

		// If given a container, verify it can be loaded and that the current user can write to it
		if ($container_guid) {
			$container = $this->getContainerEntity();
			if (!$container) {
				_elgg_services()->logger->error("User $user_guid tried to create a ($type, $subtype), but the given"
					. " container $container_guid could not be loaded.");
				return false;
			}

			if (!$container->canWriteToContainer($user_guid, $type, $subtype)) {
				_elgg_services()->logger->error("User $user_guid tried to create a ($type, $subtype), but was not"
					. " permitted to write to container $container_guid.");
				return false;
			}
		}

		// Create primary table row
		$guid = _elgg_services()->entityTable->insertRow((object) [
			'type' => $type,
			'subtype' => $subtype,
			'owner_guid' => $owner_guid,
			'container_guid' => $container_guid,
			'access_id' => $access_id,
			'time_created' => $time_created,
			'time_updated' => $now,
			'last_action' => $now,
		], $this->attributes);

		if (!$guid) {
			throw new \IOException("Unable to save new object's base entity information!");
		}

		$this->attributes['subtype'] = $subtype;
		$this->attributes['guid'] = (int) $guid;
		$this->attributes['time_created'] = (int) $time_created;
		$this->attributes['time_updated'] = (int) $now;
		$this->attributes['last_action'] = (int) $now;
		$this->attributes['container_guid'] = (int) $container_guid;

		// We are writing this new entity to cache to make sure subsequent calls
		// to get_entity() load the entity from cache and not from the DB. This
		// MUST come before the metadata and annotation writes below!
		$this->cache();

		// Save any unsaved metadata
		if (sizeof($this->temp_metadata) > 0) {
			foreach ($this->temp_metadata as $name => $value) {
				if (count($value) == 1) {
					// temp metadata is always an array, but if there is only one value return just the value
					$this->$name = $value[0];
				} else {
					$this->$name = $value;
				}
			}

			$this->temp_metadata = [];
		}

		// Save any unsaved annotations.
		if (sizeof($this->temp_annotations) > 0) {
			foreach ($this->temp_annotations as $name => $value) {
				$this->annotate($name, $value);
			}

			$this->temp_annotations = [];
		}

		// Save any unsaved private settings.
		if (sizeof($this->temp_private_settings) > 0) {
			foreach ($this->temp_private_settings as $name => $value) {
				$this->setPrivateSetting($name, $value);
			}

			$this->temp_private_settings = [];
		}

		return $guid;
	}

	/**
	 * Update the entity in the database.
	 *
	 * @return bool Whether the update was successful.
	 *
	 * @throws InvalidParameterException
	 */
	protected function update() {

		if (!$this->canEdit()) {
			return false;
		}

		// give old update event a chance to stop the update
		if (!_elgg_services()->events->trigger('update', $this->type, $this)) {
			return false;
		}

		$this->invalidateCache();

		// See #6225. We copy these after the update event in case a handler changed one of them.
		$guid = (int) $this->guid;
		$owner_guid = (int) $this->owner_guid;
		$access_id = (int) $this->access_id;
		$container_guid = (int) $this->container_guid;
		$time_created = (int) $this->time_created;
		$time = $this->getCurrentTime()->getTimestamp();

		if ($access_id == ACCESS_DEFAULT) {
			throw new \InvalidParameterException('ACCESS_DEFAULT is not a valid access level. See its documentation in constants.php');
		}
	
		if ($access_id == ACCESS_FRIENDS) {
			throw new \InvalidParameterException('ACCESS_FRIENDS is not a valid access level. See its documentation in constants.php');
		}

		// Update primary table
		$ret = _elgg_services()->entityTable->updateRow($guid, (object) [
			'owner_guid' => $owner_guid,
			'container_guid' => $container_guid,
			'access_id' => $access_id,
			'time_created' => $time_created,
			'time_updated' => $time,
			'guid' => $guid,
		]);
		if ($ret === false) {
			return false;
		}

		$this->attributes['time_updated'] = $time;

		_elgg_services()->events->triggerAfter('update', $this->type, $this);

		$this->orig_attributes = [];

		$this->cache();

		// Handle cases where there was no error BUT no rows were updated!
		return true;
	}

	/**
	 * Loads attributes from the entities table into the object.
	 *
	 * @param stdClass $row Object of properties from database row(s)
	 *
	 * @return bool
	 */
	protected function load(stdClass $row) {
		$attributes = array_merge($this->attributes, (array) $row);

		if (array_diff(self::$primary_attr_names, array_keys($attributes)) !== []) {
			// Some primary attributes are missing
			return false;
		}

		foreach ($attributes as $name => $value) {
			if (!in_array($name, self::$primary_attr_names)) {
				$this->setVolatileData("select:$name", $value);
				unset($attributes[$name]);
				continue;
			}

			if (in_array($name, self::$integer_attr_names)) {
				$attributes[$name] = (int) $value;
			}
		}

		$this->attributes = $attributes;

		$this->cache();

		return true;
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
	 * To enable an entity, use {@link \ElggEntity::enable()}.
	 *
	 * Recursively disabling an entity will disable all entities
	 * owned or contained by the parent entity.
	 *
	 * You can ignore the disabled field by using {@link access_show_hidden_entities()}.
	 *
	 * @note Internal: Disabling an entity sets the 'enabled' column to 'no'.
	 *
	 * @param string $reason    Optional reason
	 * @param bool   $recursive Recursively disable all contained entities?
	 *
	 * @return bool
	 * @see \ElggEntity::enable()
	 */
	public function disable($reason = "", $recursive = true) {
		if (!$this->guid) {
			return false;
		}

		if (!_elgg_services()->events->trigger('disable', $this->type, $this)) {
			return false;
		}

		if (!$this->canEdit()) {
			return false;
		}

		if ($this instanceof ElggUser && !$this->isBanned()) {
			// temporarily ban to prevent using the site during disable
			$this->ban();
			$unban_after = true;
		} else {
			$unban_after = false;
		}

		if ($reason) {
			$this->disable_reason = $reason;
		}

		$dbprefix = _elgg_config()->dbprefix;

		$guid = (int) $this->guid;

		if ($recursive) {
			$flags = ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES;
			$callback = function () use ($guid, $reason) {
				$base_options = [
					'wheres' => [
						function(QueryBuilder $qb, $main_alias) use ($guid) {
							return $qb->compare("{$main_alias}.guid", '!=', $guid, ELGG_VALUE_GUID);
						},
					],
					'limit' => false,
				];

				foreach (['owner_guid', 'container_guid'] as $db_column) {
					$options = $base_options;
					$options[$db_column] = $guid;

					$subentities = new \ElggBatch('elgg_get_entities', $options);
					$subentities->setIncrementOffset(false);

					foreach ($subentities as $subentity) {
						/* @var $subentity \ElggEntity */
						if (!$subentity->isEnabled()) {
							continue;
						}
						add_entity_relationship($subentity->guid, 'disabled_with', $guid);
						$subentity->disable($reason);
					}
				}
			};

			elgg_call($flags, $callback);
		}

		$this->disableAnnotations();

		$sql = "
			UPDATE {$dbprefix}entities
			SET enabled = 'no'
			WHERE guid = :guid
		";
		$params = [
			':guid' => $guid,
		];
		$disabled = $this->getDatabase()->updateData($sql, false, $params);

		if ($unban_after) {
			$this->unban();
		}

		if ($disabled) {
			$this->invalidateCache();

			$this->attributes['enabled'] = 'no';
			_elgg_services()->events->triggerAfter('disable', $this->type, $this);
		}

		return (bool) $disabled;
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
		$guid = (int) $this->guid;
		if (!$guid) {
			return false;
		}

		if (!_elgg_services()->events->trigger('enable', $this->type, $this)) {
			return false;
		}

		if (!$this->canEdit()) {
			return false;
		}

		$flags = ELGG_SHOW_DISABLED_ENTITIES;
		$callback = function() use ($guid, $recursive) {
			$db = $this->getDatabase();
			$result = $db->updateData("
				UPDATE {$db->prefix}entities
				SET enabled = 'yes'
				WHERE guid = $guid
			");

			$this->deleteMetadata('disable_reason');
			$this->enableAnnotations();

			if ($recursive) {
				$disabled_with_it = elgg_get_entities([
					'relationship' => 'disabled_with',
					'relationship_guid' => $guid,
					'inverse_relationship' => true,
					'limit' => 0,
				]);

				foreach ($disabled_with_it as $e) {
					$e->enable();
					remove_entity_relationship($e->guid, 'disabled_with', $guid);
				}
			}

			return $result;
		};

		$result = elgg_call($flags, $callback);

		if ($result) {
			$this->attributes['enabled'] = 'yes';
			_elgg_services()->events->triggerAfter('enable', $this->type, $this);
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
		// first check if we can delete this entity
		// NOTE: in Elgg <= 1.10.3 this was after the delete event,
		// which could potentially remove some content if the user didn't have access
		if (!$this->canDelete()) {
			return false;
		}

		try {
			return _elgg_services()->entityTable->delete($this, $recursive);
		} catch (DatabaseException $ex) {
			elgg_log($ex, 'ERROR');
			return false;
		}
	}

	/**
	 * Export an entity
	 *
	 * @param array $params Params to pass to the hook
	 * @return \Elgg\Export\Entity
	 */
	public function toObject(array $params = []) {
		$object = $this->prepareObject(new \Elgg\Export\Entity());

		$params['entity'] = $this;

		return _elgg_services()->hooks->trigger('to:object', 'entity', $params, $object);
	}

	/**
	 * Prepare an object copy for toObject()
	 *
	 * @param \Elgg\Export\Entity $object Object representation of the entity
	 * @return \Elgg\Export\Entity
	 */
	protected function prepareObject(\Elgg\Export\Entity $object) {
		$object->guid = $this->guid;
		$object->type = $this->getType();
		$object->subtype = $this->getSubtype();
		$object->owner_guid = $this->getOwnerGUID();
		$object->container_guid = $this->getContainerGUID();
		$object->time_created = date('c', $this->getTimeCreated());
		$object->time_updated = date('c', $this->getTimeUpdated());
		$object->url = $this->getURL();
		$object->read_access = (int) $this->access_id;
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
		$this->{"geo:lat"} = $lat;
		$this->{"geo:long"} = $long;
	}

	/**
	 * Return the entity's latitude.
	 *
	 * @return float
	 * @todo Unimplemented
	 */
	public function getLatitude() {
		return (float) $this->{"geo:lat"};
	}

	/**
	 * Return the entity's longitude
	 *
	 * @return float
	 * @todo Unimplemented
	 */
	public function getLongitude() {
		return (float) $this->{"geo:long"};
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
			$tag_names = [$tag_names];
		}

		$valid_tags = elgg_get_registered_tag_metadata_names();
		$entity_tags = [];

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

	/**
	 * Remove the membership of all access collections for this entity (if the entity is a user)
	 *
	 * @return bool
	 * @since 1.11
	 */
	public function deleteAccessCollectionMemberships() {

		if (!$this->guid) {
			return false;
		}

		if ($this->type !== 'user') {
			return true;
		}

		$ac = _elgg_services()->accessCollections;

		$collections = $ac->getCollectionsByMember($this->guid);
		if (empty($collections)) {
			return true;
		}

		$result = true;
		foreach ($collections as $collection) {
			$result = $result & $ac->removeUser($this->guid, $collection->id);
		}

		return $result;
	}

	/**
	 * Remove all access collections owned by this entity
	 *
	 * @return bool
	 * @since 1.11
	 */
	public function deleteOwnedAccessCollections() {

		if (!$this->guid) {
			return false;
		}

		$collections = $this->getOwnedAccessCollections();
		if (empty($collections)) {
			return true;
		}

		$result = true;
		foreach ($collections as $collection) {
			$result = $result & $collection->delete();
		}

		return $result;
	}

	/**
	 * Update the last_action column in the entities table.
	 *
	 * @warning This is different to time_updated.  Time_updated is automatically set,
	 * while last_action is only set when explicitly called.
	 *
	 * @param int $posted Timestamp of last action
	 * @return int|false
	 * @access private
	 */
	public function updateLastAction($posted = null) {
		$posted = _elgg_services()->entityTable->updateLastAction($this, $posted);
		if ($posted) {
			$this->attributes['last_action'] = $posted;
			$this->cache();
		}
		return $posted;
	}

	/**
	 * Disable runtime caching for entity
	 *
	 * @return void
	 * @internal
	 */
	public function disableCaching() {
		$this->_is_cacheable = false;
		if ($this->guid) {
			_elgg_services()->entityCache->delete($this->guid);
		}
	}

	/**
	 * Enable runtime caching for entity
	 *
	 * @return void
	 * @internal
	 */
	public function enableCaching() {
		$this->_is_cacheable = true;
	}

	/**
	 * Is entity cacheable in the runtime cache
	 *
	 * @return bool
	 * @internal
	 */
	public function isCacheable() {
		if (!$this->guid) {
			return false;
		}
		
		if (_elgg_services()->session->getIgnoreAccess()) {
			return false;
		}
		return $this->_is_cacheable;
	}

	/**
	 * Cache the entity in a session and persisted caches
	 *
	 * @param bool $persist Store in persistent cache
	 *
	 * @return void
	 * @internal
	 */
	public function cache($persist = true) {
		if (!$this->isCacheable()) {
			return;
		}

		_elgg_services()->entityCache->save($this);

		if ($persist) {
			$tmp = $this->volatile;

			// don't store volatile data
			$this->volatile = [];

			_elgg_services()->dataCache->entities->save($this->guid, $this);

			$this->volatile = $tmp;
		}
	}

	/**
	 * Invalidate cache for entity
	 *
	 * @return void
	 * @internal
	 */
	public function invalidateCache() {
		if (!$this->guid) {
			return;
		}

		_elgg_services()->entityCache->delete($this->guid);

		$namespaces = [
			'entities',
			'metadata',
			'private_settings',
		];

		foreach ($namespaces as $namespace) {
			_elgg_services()->dataCache->get($namespace)->delete($this->guid);
		}
	}
}
