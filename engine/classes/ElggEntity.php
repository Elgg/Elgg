<?php

use Elgg\EntityIcon;
use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\Filesystem\IOException;
use Elgg\Exceptions\DomainException as ElggDomainException;
use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Traits\Entity\Subscriptions;

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
 * @property-read  string $type           object, user, group, or site (read-only after save)
 * @property-read  string $subtype        Further clarifies the nature of the entity
 * @property-read  int    $guid           The unique identifier for this entity (read only)
 * @property       int    $owner_guid     The GUID of the owner of this entity (usually the creator)
 * @property       int    $container_guid The GUID of the entity containing this entity
 * @property       int    $access_id      Specifies the visibility level of this entity
 * @property       int    $time_created   A UNIX timestamp of when the entity was created
 * @property-read  int    $time_updated   A UNIX timestamp of when the entity was last updated (automatically updated on save)
 * @property-read  int    $last_action    A UNIX timestamp of when the entity was last acted upon
 * @property-read  int    $time_soft_deleted    A UNIX timestamp of when the entity was soft deleted
 * @property-read  string $enabled        Is this entity enabled ('yes' or 'no')
 *
 * Metadata (the above are attributes)
 * @property       string $location       A location of the entity
 */
abstract class ElggEntity extends \ElggData implements EntityIcon {

	use Subscriptions;
	
	public const PRIMARY_ATTR_NAMES = [
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

	/**
	 * @var string[] attributes that are integers
	 */
	protected const INTEGER_ATTR_NAMES = [
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

		if (!empty($row) && !$this->load($row)) {
			throw new IOException('Failed to load new ' . get_class() . " for GUID: {$row->guid}");
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
		$this->attributes['type'] = $this->getType();
		$this->attributes['subtype'] = null;

		$this->attributes['owner_guid'] = _elgg_services()->session_manager->getLoggedInUserGuid();
		$this->attributes['container_guid'] = _elgg_services()->session_manager->getLoggedInUserGuid();

		$this->attributes['access_id'] = ACCESS_PRIVATE;
		$this->attributes['time_updated'] = null;
		$this->attributes['last_action'] = null;
		$this->attributes['enabled'] = 'yes';
	}

	/**
	 * Clone an entity
	 *
	 * Resets the guid so that the entity can be saved as a distinct entity from
	 * the original. Creation time will be set when this new entity is saved.
	 * The owner and container guids come from the original entity. The clone
	 * method copies metadata but does not copy annotations.
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
			'limit' => false,
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
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\InvalidArgumentException
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
				case 'last_action':
				case 'time_updated':
				case 'type':
					return;
				case 'subtype':
					throw new ElggInvalidArgumentException(elgg_echo('ElggEntity:Error:SetSubtype', ['setSubtype()']));
				case 'enabled':
					throw new ElggInvalidArgumentException(elgg_echo('ElggEntity:Error:SetEnabled', ['enable() / disable()']));
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
	public function getOriginalAttributes(): array {
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
	public function getDisplayName(): string {
		return (string) $this->name;
	}

	/**
	 * Sets the title or name of this entity.
	 *
	 * @param string $display_name The title or name of this entity.
	 * @return void
	 */
	public function setDisplayName(string $display_name): void {
		$this->name = $display_name;
	}

	/**
	 * Return the value of a piece of metadata.
	 *
	 * @param string $name Name
	 *
	 * @return mixed The value, or null if not found.
	 */
	public function getMetadata(string $name) {
		$metadata = $this->getAllMetadata();
		return elgg_extract($name, $metadata);
	}

	/**
	 * Get all entity metadata
	 *
	 * @return array
	 */
	public function getAllMetadata(): array {
		if (!$this->guid) {
			return array_map(function($values) {
				return count($values) > 1 ? $values : $values[0];
			}, $this->temp_metadata);
		}

		$this->_cached_metadata = _elgg_services()->metadataCache->getAll($this->guid);

		return $this->_cached_metadata;
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
	 */
	public function setMetadata(string $name, $value, string $value_type = '', bool $multiple = false): bool {

		if ($value === null || $value === '') {
			return $this->deleteMetadata($name);
		}
		
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

				if ($delete_result === false) {
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
			$metadata->value = $value_tmp;
			
			if (!empty($value_type)) {
				$metadata->value_type = $value_type;
			}
			
			$md_id = _elgg_services()->metadataTable->create($metadata, $multiple);
			if ($md_id === false) {
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
	protected function setTempMetadata(string $name, $value, bool $multiple = false): bool {
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
	 *
	 * @return bool
	 * @since 1.8
	 */
	public function deleteMetadata(string $name = null): bool {

		if (!$this->guid) {
			// remove from temp_metadata
			if (isset($name)) {
				if (isset($this->temp_metadata[$name])) {
					unset($this->temp_metadata[$name]);
				}
			} else {
				$this->temp_metadata = [];
			}
			
			return true;
		}

		return elgg_delete_metadata([
			'guid' => $this->guid,
			'limit' => false,
			'metadata_name' => $name,
		]);
	}

	/**
	 * Get a piece of volatile (non-persisted) data on this entity.
	 *
	 * @param string $name The name of the volatile data
	 *
	 * @return mixed The value or null if not found.
	 */
	public function getVolatileData(string $name) {
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
	public function setVolatileData(string $name, $value): void {
		$this->volatile[$name] = $value;
	}

	/**
	 * Add a relationship between this an another entity.
	 *
	 * @tip Read the relationship like "This entity is a $relationship of $guid_two."
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return bool
	 * @throws \Elgg\Exceptions\LengthException
	 */
	public function addRelationship(int $guid_two, string $relationship): bool {
		return _elgg_services()->relationshipsTable->add($this->guid, (string) $relationship, (int) $guid_two);
	}
	
	/**
	 * Check if this entity has a relationship with another entity
	 *
	 * @tip Read the relationship like "This entity is a $relationship of $guid_two."
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function hasRelationship(int $guid_two, string $relationship): bool {
		return (bool) _elgg_services()->relationshipsTable->check($this->guid, $relationship, $guid_two);
	}
	
	/**
	 * Return the relationship if this entity has a relationship with another entity
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return \ElggRelationship|null
	 * @since 4.3
	 */
	public function getRelationship(int $guid_two, string $relationship): ?\ElggRelationship {
		return _elgg_services()->relationshipsTable->check($this->guid, $relationship, $guid_two) ?: null;
	}
	
	/**
	 * Gets an array of entities with a relationship to this entity.
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity
	 *
	 * @return \ElggEntity[]|int|mixed
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
	 * @return int
	 */
	public function countEntitiesFromRelationship(string $relationship, bool $inverse_relationship = false): int {
		return elgg_count_entities([
			'relationship' => $relationship,
			'relationship_guid' => $this->guid,
			'inverse_relationship' => $inverse_relationship,
		]);
	}

	/**
	 * Remove a relationship
	 *
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 * @param string $relationship The type of relationship
	 *
	 * @return bool
	 */
	public function removeRelationship(int $guid_two, string $relationship): bool {
		return _elgg_services()->relationshipsTable->remove($this->guid, (string) $relationship, (int) $guid_two);
	}
	
	/**
	 * Remove all relationships to or from this entity.
	 *
	 * If you pass a relationship name, only relationships matching that name will be deleted.
	 *
	 * @warning Calling this with no $relationship will clear all relationships with this entity.
	 *
	 * @param string|null $relationship         (optional) The name of the relationship to remove
	 * @param bool        $inverse_relationship (optional) Inverse the relationship
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function removeAllRelationships(string $relationship = '', bool $inverse_relationship = false): bool {
		return _elgg_services()->relationshipsTable->removeAll($this->guid, $relationship, $inverse_relationship);
	}

	/**
	 * Removes all river items related to this entity
	 *
	 * @return void
	 */
	public function removeAllRelatedRiverItems(): void {
		elgg_delete_river(['subject_guid' => $this->guid, 'limit' => false]);
		elgg_delete_river(['object_guid' => $this->guid, 'limit' => false]);
		elgg_delete_river(['target_guid' => $this->guid, 'limit' => false]);
	}

	/**
	 * Deletes all annotations on this object (annotations.entity_guid = $this->guid).
	 * If you pass a name, only annotations matching that name will be deleted.
	 *
	 * @warning Calling this with no or empty arguments will clear all annotations on the entity.
	 *
	 * @param string $name An optional name of annotations to remove.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteAnnotations(string $name = null): bool {
		if ($this->guid) {
			return elgg_delete_annotations([
				'guid' => $this->guid,
				'limit' => false,
				'annotation_name' => $name,
			]);
		}
		
		if ($name) {
			unset($this->temp_annotations[$name]);
		} else {
			$this->temp_annotations = [];
		}
		
		return true;
	}

	/**
	 * Deletes all annotations owned by this object (annotations.owner_guid = $this->guid).
	 * If you pass a name, only annotations matching that name will be deleted.
	 *
	 * @param string $name An optional name of annotations to delete.
	 * @return bool
	 * @since 1.8
	 */
	public function deleteOwnedAnnotations(string $name = null): bool {
		// access is turned off for this because they might
		// no longer have access to an entity they created annotations on
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($name) {
			return elgg_delete_annotations([
				'annotation_owner_guid' => $this->guid,
				'limit' => false,
				'annotation_name' => $name,
			]);
		});
	}

	/**
	 * Disables annotations for this entity, optionally based on name.
	 *
	 * @param string $name An optional name of annotations to disable.
	 * @return bool
	 * @since 1.8
	 */
	public function disableAnnotations(string $name = null): bool {
		return elgg_disable_annotations([
			'guid' => $this->guid,
			'limit' => false,
			'annotation_name' => $name,
		]);
	}

	/**
	 * Enables annotations for this entity, optionally based on name.
	 *
	 * @param string $name An optional name of annotations to enable.
	 * @return bool
	 * @since 1.8
	 */
	public function enableAnnotations(string $name = null) {
		return elgg_enable_annotations([
			'guid' => $this->guid,
			'limit' => false,
			'annotation_name' => $name,
		]);
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
	public function annotate($name, $value, $access_id = ACCESS_PRIVATE, $owner_guid = 0, $value_type = '') {
		if (!$this->guid) {
			$this->temp_annotations[$name] = $value;
			return true;
		}
		
		if (!$owner_guid) {
			$owner_guid = _elgg_services()->session_manager->getLoggedInUserGuid();
		}
		
		$annotation = new ElggAnnotation();
		$annotation->entity_guid = $this->guid;
		$annotation->name = $name;
		$annotation->value = $value;
		$annotation->owner_guid = $owner_guid;
		$annotation->access_id = $access_id;
		
		if (!empty($value_type)) {
			$annotation->value_type = $value_type;
		}
		
		if ($annotation->save()) {
			return $annotation->id;
		}
		
		return false;
	}

	/**
	 * Gets an array of annotations.
	 *
	 * To retrieve annotations on an unsaved entity, pass array('name' => [annotation name])
	 * as the options array.
	 *
	 * @param array $options Array of options for elgg_get_annotations() except guid.
	 *
	 * @return \ElggAnnotation[]|mixed
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
	public function countAnnotations(string $name = ''): int {
		return $this->getAnnotationCalculation($name, 'count');
	}

	/**
	 * Get the average of an integer type annotation.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsAvg(string $name) {
		return $this->getAnnotationCalculation($name, 'avg');
	}

	/**
	 * Get the sum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsSum(string $name) {
		return $this->getAnnotationCalculation($name, 'sum');
	}

	/**
	 * Get the minimum of integer type annotations of given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsMin(string $name) {
		return $this->getAnnotationCalculation($name, 'min');
	}

	/**
	 * Get the maximum of integer type annotations of a given name.
	 *
	 * @param string $name Annotation name
	 *
	 * @return int
	 */
	public function getAnnotationsMax(string $name) {
		return $this->getAnnotationCalculation($name, 'max');
	}

	/**
	 * Count the number of comments attached to this entity.
	 *
	 * @return int Number of comments
	 * @since 1.8.0
	 */
	public function countComments(): int {
		if (!$this->hasCapability('commentable')) {
			return 0;
		}
		
		$params = ['entity' => $this];
		$num = _elgg_services()->events->triggerResults('comments:count', $this->getType(), $params);

		if (is_int($num)) {
			return $num;
		}
		
		return \Elgg\Comments\DataService::instance()->getCommentsCount($this);
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
	public function getOwnedAccessCollections(array $options = []): array {
		$options['owner_guid'] = $this->guid;
		return _elgg_services()->accessCollections->getEntityCollections($options);
	}
	
	/**
	 * Returns the first ACL owned by the entity with a given subtype
	 *
	 * @param string $subtype subtype of the ACL
	 *
	 * @return \ElggAccessCollection|null
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 *
	 * @since 3.0
	 */
	public function getOwnedAccessCollection(string $subtype): ?\ElggAccessCollection {
		if ($subtype === '') {
			throw new ElggInvalidArgumentException(__METHOD__ . ' requires $subtype to be non empty');
		}
		
		$acls = $this->getOwnedAccessCollections([
			'subtype' => $subtype,
		]);
		
		return elgg_extract(0, $acls);
	}
	
	/**
	 * Check if the given user has access to this entity
	 *
	 * @param int $user_guid the GUID of the user to check access for (default: logged in user_guid)
	 *
	 * @return bool
	 * @since 4.3
	 */
	public function hasAccess(int $user_guid = 0): bool {
		return _elgg_services()->accessCollections->hasAccessToEntity($this, $user_guid);
	}

	/**
	 * Can a user edit this entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check event.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is editable by the given user.
	 */
	public function canEdit(int $user_guid = 0): bool {
		return _elgg_services()->userCapabilities->canEdit($this, $user_guid);
	}

	/**
	 * Can a user delete this entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:delete event.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is deletable by the given user.
	 * @since 1.11
	 */
	public function canDelete(int $user_guid = 0): bool {
		return _elgg_services()->userCapabilities->canDelete($this, $user_guid);
	}

	/**
	 * Can a user add an entity to this container
	 *
	 * @param int    $user_guid The GUID of the user creating the entity (0 for logged in user).
	 * @param string $type      The type of entity we're looking to write
	 * @param string $subtype   The subtype of the entity we're looking to write
	 *
	 * @return bool
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function canWriteToContainer(int $user_guid = 0, string $type = '', string $subtype = ''): bool {
		if (empty($type) || empty($subtype)) {
			throw new ElggInvalidArgumentException(__METHOD__ . ' requires $type and $subtype to be set');
		}
		
		return _elgg_services()->userCapabilities->canWriteToContainer($this, $type, $subtype, $user_guid);
	}

	/**
	 * Can a user comment on an entity?
	 *
	 * @tip Can be overridden by registering for the 'permissions_check:comment', '<entity type>' event.
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 *
	 * @return bool
	 */
	public function canComment(int $user_guid = 0): bool {
		return _elgg_services()->userCapabilities->canComment($this, $user_guid);
	}

	/**
	 * Can a user annotate an entity?
	 *
	 * @tip Can be overridden by registering for the event [permissions_check:annotate:<name>,
	 * <entity type>] or [permissions_check:annotate, <entity type>]. The events are called in that order.
	 *
	 * @tip If you want logged out users to annotate an object, do not call
	 * canAnnotate(). It's easier than using the event.
	 *
	 * @param int    $user_guid       User guid (default is logged in user)
	 * @param string $annotation_name The name of the annotation (default is unspecified)
	 *
	 * @return bool
	 */
	public function canAnnotate(int $user_guid = 0, string $annotation_name = ''): bool {
		return _elgg_services()->userCapabilities->canAnnotate($this, $user_guid, $annotation_name);
	}

	/**
	 * Returns the guid.
	 *
	 * @return int|null GUID
	 */
	public function getGUID(): ?int {
		return $this->guid;
	}

	/**
	 * Returns the entity type
	 *
	 * @return string The entity type
	 */
	public function getType(): string {
		// this is just for the PHPUnit mocking framework
		return (string) $this->type;
	}

	/**
	 * Set the subtype of the entity
	 *
	 * @param string $subtype the new type
	 *
	 * @return void
	 * @see self::initializeAttributes()
	 */
	public function setSubtype(string $subtype): void {
		// keep original values
		if ($this->guid && !array_key_exists('subtype', $this->orig_attributes)) {
			$this->orig_attributes['subtype'] = $this->attributes['subtype'];
		}
		
		$this->attributes['subtype'] = $subtype;
	}

	/**
	 * Get the entity subtype
	 *
	 * @return string The entity subtype
	 */
	public function getSubtype(): string {
		return (string) $this->attributes['subtype'];
	}

	/**
	 * Get the guid of the entity's owner.
	 *
	 * @return int The owner GUID
	 */
	public function getOwnerGUID(): int {
		return (int) $this->owner_guid;
	}

	/**
	 * Gets the \ElggEntity that owns this entity.
	 *
	 * @return \ElggEntity|null
	 */
	public function getOwnerEntity(): ?\ElggEntity {
		return $this->owner_guid ? get_entity($this->owner_guid) : null;
	}

	/**
	 * Set the container for this object.
	 *
	 * @param int $container_guid The ID of the container.
	 *
	 * @return void
	 */
	public function setContainerGUID(int $container_guid): void {
		$this->container_guid = $container_guid;
	}

	/**
	 * Gets the container GUID for this entity.
	 *
	 * @return int
	 */
	public function getContainerGUID(): int {
		return (int) $this->container_guid;
	}

	/**
	 * Get the container entity for this object.
	 *
	 * @return \ElggEntity|null
	 * @since 1.8.0
	 */
	public function getContainerEntity(): ?\ElggEntity {
		return $this->container_guid ? get_entity($this->getContainerGUID()) : null;
	}

	/**
	 * Returns the UNIX epoch time that this entity was last updated
	 *
	 * @return int UNIX epoch time
	 */
	public function getTimeUpdated(): int {
		return (int) $this->time_updated;
	}

	/**
	 * Gets the URL for this entity.
	 *
	 * Plugins can register for the 'entity:url', '<type>' event to
	 * customize the url for an entity.
	 *
	 * @return string The URL of the entity
	 */
	public function getURL(): string {
		$url = elgg_generate_entity_url($this, 'view');

		$url = _elgg_services()->events->triggerResults('entity:url', $this->getType(), ['entity' => $this], $url);

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
	public function saveIconFromUploadedFile(string $input_name, string $type = 'icon', array $coords = []): bool {
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
	public function saveIconFromLocalFile(string $filename, string $type = 'icon', array $coords = []): bool {
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
	public function saveIconFromElggFile(\ElggFile $file, string $type = 'icon', array $coords = []): bool {
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
	public function getIcon(string $size, string $type = 'icon'): \ElggIcon {
		return _elgg_services()->iconService->getIcon($this, $size, $type);
	}

	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function deleteIcon(string $type = 'icon'): bool {
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
	public function getIconLastChange(string $size, string $type = 'icon'): ?int {
		return _elgg_services()->iconService->getIconLastChange($this, $size, $type);
	}

	/**
	 * Returns if the entity has an icon of the passed type.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function hasIcon(string $size, string $type = 'icon'): bool {
		return _elgg_services()->iconService->hasIcon($this, $size, $type);
	}

	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', '<type>' event
	 * to customize the icon for an entity.
	 *
	 * @param mixed $params A string defining the size of the icon (e.g. tiny, small, medium, large)
	 *                      or an array of parameters including 'size'
	 * @return string The URL
	 * @since 1.8.0
	 */
	public function getIconURL(string|array $params = []): string {
		return _elgg_services()->iconService->getIconURL($this, $params);
	}

	/**
	 * {@inheritDoc}
	 */
	public function save(): bool {
		if ($this->guid > 0) {
			$result = $this->update();
		} else {
			$result = $this->create() !== false;
		}

		if ($result) {
			$this->cache();
		}

		return $result;
	}

	/**
	 * Create a new entry in the entities table.
	 *
	 * Saves the base information in the entities table for the entity.  Saving
	 * the type-specific information is handled in the calling class method.
	 *
	 * @return int|false The new entity's GUID or false on failure
	 *
	 * @throws \Elgg\Exceptions\DomainException If the entity's type has not been set.
	 * @throws \Elgg\Exceptions\InvalidArgumentException If the entity's subtype has not been set or access_id is invalid
	 * @throws \Elgg\Exceptions\Filesystem\IOException If the new row fails to write to the DB.
	 */
	protected function create() {

		$type = $this->attributes['type'];
		if (!in_array($type, \Elgg\Config::ENTITY_TYPES)) {
			throw new ElggDomainException('Entity type must be one of the allowed types: ' . implode(', ', \Elgg\Config::ENTITY_TYPES));
		}

		$subtype = $this->attributes['subtype'];
		if (!$subtype) {
			throw new ElggInvalidArgumentException('All entities must have a subtype');
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
			throw new ElggInvalidArgumentException('ACCESS_DEFAULT is not a valid access level. See its documentation in constants.php');
		}
		
		if ($access_id == ACCESS_FRIENDS) {
			throw new ElggInvalidArgumentException('ACCESS_FRIENDS is not a valid access level. See its documentation in constants.php');
		}

		$user_guid = _elgg_services()->session_manager->getLoggedInUserGuid();

		// If given an owner, verify it can be loaded
		if (!empty($owner_guid)) {
			$owner = $this->getOwnerEntity();
			if (!$owner instanceof \ElggEntity) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}),";
				$error .= " but the given owner {$owner_guid} could not be loaded.";
				_elgg_services()->logger->error($error);
				return false;
			}

			// If different owner than logged in, verify can write to container.
			if ($user_guid !== $owner_guid && !$owner->canEdit() && !$owner->canWriteToContainer($user_guid, $type, $subtype)) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}) with owner {$owner_guid},";
				$error .= " but the user wasn't permitted to write to the owner's container.";
				_elgg_services()->logger->error($error);
				return false;
			}
		}

		// If given a container, verify it can be loaded and that the current user can write to it
		if (!empty($container_guid)) {
			$container = $this->getContainerEntity();
			if (!$container instanceof \ElggEntity) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}),";
				$error .= " but the given container {$container_guid} could not be loaded.";
				_elgg_services()->logger->error($error);
				return false;
			}

			if (!$container->canWriteToContainer($user_guid, $type, $subtype)) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}),";
				$error .= " but was not permitted to write to container {$container_guid}.";
				_elgg_services()->logger->error($error);
				return false;
			}
		}
		
		if (!_elgg_services()->events->triggerBefore('create', $this->type, $this)) {
			return false;
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
			throw new IOException("Unable to save new object's base entity information!");
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
		if (count($this->temp_metadata) > 0) {
			foreach ($this->temp_metadata as $name => $value) {
				// temp metadata is always an array, but if there is only one value return just the value
				$this->setMetadata($name, $value, '', count($value) > 1);
			}

			$this->temp_metadata = [];
		}

		// Save any unsaved annotations.
		if (count($this->temp_annotations) > 0) {
			foreach ($this->temp_annotations as $name => $value) {
				$this->annotate($name, $value);
			}

			$this->temp_annotations = [];
		}
		
		if (isset($container) && !$container instanceof \ElggUser) {
			// users have their own logic for setting last action
			$container->updateLastAction();
		}
		
		// for BC reasons this event is still needed (for example for notifications)
		_elgg_services()->events->trigger('create', $this->type, $this);
		
		_elgg_services()->events->triggerAfter('create', $this->type, $this);

		return $guid;
	}

	/**
	 * Update the entity in the database.
	 *
	 * @return bool Whether the update was successful.
	 *
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	protected function update(): bool {

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
			throw new ElggInvalidArgumentException('ACCESS_DEFAULT is not a valid access level. See its documentation in constants.php');
		}
	
		if ($access_id == ACCESS_FRIENDS) {
			throw new ElggInvalidArgumentException('ACCESS_FRIENDS is not a valid access level. See its documentation in constants.php');
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
	protected function load(stdClass $row): bool {
		$attributes = array_merge($this->attributes, (array) $row);

		if (array_diff(self::PRIMARY_ATTR_NAMES, array_keys($attributes)) !== []) {
			// Some primary attributes are missing
			return false;
		}

		foreach ($attributes as $name => $value) {
			if (!in_array($name, self::PRIMARY_ATTR_NAMES)) {
				$this->setVolatileData("select:{$name}", $value);
				unset($attributes[$name]);
				continue;
			}

			if (in_array($name, static::INTEGER_ATTR_NAMES)) {
				$attributes[$name] = (int) $value;
			}
		}

		$this->attributes = $attributes;

		$this->cache();

		return true;
	}

    //**SOFT DELETE TESTING */

    public function softDelete(bool $recursive = true): bool {

        if (!$this->guid) {
            return false;
        }


        if (!_elgg_services()->events->trigger('softDelete', $this->type, $this)) {
            return false;
        }

        if (!$this->canDelete()) {
            return false;
        }

        if ($this instanceof ElggUser && !$this->isBanned()) {
            // temporarily ban to prevent using the site during disable
            $this->ban();
            $unban_after = true;
        } else {
            $unban_after = false;
        }


        $guid = (int) $this->guid;


        if ($recursive) {
            elgg_call(ELGG_IGNORE_ACCESS | ELGG_HIDE_DISABLED_ENTITIES, function () use ($guid) {
                $base_options = [
                    'wheres' => [
                        function(QueryBuilder $qb, $main_alias) use ($guid) {
                            return $qb->compare("{$main_alias}.guid", '!=', $guid, ELGG_VALUE_GUID);
                        },
                    ],
                    'limit' => false,
                    'batch' => true,
                    'batch_inc_offset' => false,
                ];

                foreach (['owner_guid', 'container_guid'] as $db_column) {
                    $options = $base_options;
                    $options[$db_column] = $guid;

                    $subentities = elgg_get_entities($options);
                    /* @var $subentity \ElggEntity */
                    foreach ($subentities as $subentity) {

                        $subentity->addRelationship($guid, 'softDeleted_with');
                        $subentity->softDelete(true);
                    }
                }
            });
        }

        $this->disableAnnotations();

        //TODO: Link to database team method to write to softDelete column
        $softDeleted = _elgg_services()->entityTable->softDelete($this);

        $time_soft_deleted = isset($this->attributes['time_soft_deleted']) ? (int) $this->attributes['time_soft_deleted'] : $now;

        // Call updateTimeSoftDeleted function to update the time_soft_deleted attribute
        $this->updateTimeSoftDeleted($time_soft_deleted);


        if ($unban_after) {
            $this->unban();
        }

        if ($softDeleted) {
            $this->invalidateCache();

            $this->attributes['softDeleted'] = 'yes';

            _elgg_services()->events->triggerAfter('softDelete', $this->type, $this);
        }

        return $softDeleted;
    }

    public function restore(bool $recursive = true): bool {
        if (empty($this->guid)) {
            return false;
        }

        if (!_elgg_services()->events->trigger('restore', $this->type, $this)) {
            return false;
        }

        if (!$this->canEdit()) {
            return false;
        }

        $result = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($recursive) {
            //TODO: Link to database team method to write to softDelete column
            $result = _elgg_services()->entityTable->restore($this);

            $this->enableAnnotations();

            if ($recursive) {
                $softDeleted_with_it = elgg_get_entities([
                    'relationship' => 'softDeleted_with',
                    'relationship_guid' => $this->guid,
                    'inverse_relationship' => true,
                    'limit' => false,
                    'batch' => true,
                    'batch_inc_offset' => false,
                ]);

                foreach ($softDeleted_with_it as $e) {
                    $e->enable($recursive);
                    $e->removeRelationship($this->guid, 'softDeleted_with');
                }
            }

            return $result;
        });

        if ($result) {
            $this->attributes['softDeleted'] = 'no';
            //TODO: Find out what enable events do, how to adapt to restore
            _elgg_services()->events->triggerAfter('restore', $this->type, $this);
        }

        return $result;
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
	 * @note Internal: Disabling an entity sets the 'enabled' column to 'no'.
	 *
	 * @param string $reason    Optional reason
	 * @param bool   $recursive Recursively disable all contained entities?
	 *
	 * @return bool
	 * @see \ElggEntity::enable()
	 */
	public function disable(string $reason = '', bool $recursive = true): bool {
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

		if (!empty($reason)) {
			$this->disable_reason = $reason;
		}

		$guid = (int) $this->guid;

		if ($recursive) {
			elgg_call(ELGG_IGNORE_ACCESS | ELGG_HIDE_DISABLED_ENTITIES, function () use ($guid, $reason) {
				$base_options = [
					'wheres' => [
						function(QueryBuilder $qb, $main_alias) use ($guid) {
							return $qb->compare("{$main_alias}.guid", '!=', $guid, ELGG_VALUE_GUID);
						},
					],
					'limit' => false,
					'batch' => true,
					'batch_inc_offset' => false,
				];

				foreach (['owner_guid', 'container_guid'] as $db_column) {
					$options = $base_options;
					$options[$db_column] = $guid;

					$subentities = elgg_get_entities($options);
					/* @var $subentity \ElggEntity */
					foreach ($subentities as $subentity) {
						if (!$subentity->isEnabled()) {
							continue;
						}
						
						$subentity->addRelationship($guid, 'disabled_with');
						$subentity->disable($reason, true);
					}
				}
			});
		}

		$this->disableAnnotations();

		$disabled = _elgg_services()->entityTable->disable($this);

		if ($unban_after) {
			$this->unban();
		}

		if ($disabled) {
			$this->invalidateCache();

			$this->attributes['enabled'] = 'no';
			_elgg_services()->events->triggerAfter('disable', $this->type, $this);
		}

		return $disabled;
	}

	/**
	 * Enable the entity
	 *
	 * @param bool $recursive Recursively enable all entities disabled with the entity?
	 * @see access_show_hiden_entities()
	 * @return bool
	 */
	public function enable(bool $recursive = true): bool {
		if (empty($this->guid)) {
			return false;
		}

		if (!_elgg_services()->events->trigger('enable', $this->type, $this)) {
			return false;
		}

		if (!$this->canEdit()) {
			return false;
		}

		$result = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($recursive) {
			$result = _elgg_services()->entityTable->enable($this);
				
			$this->deleteMetadata('disable_reason');
			$this->enableAnnotations();

			if ($recursive) {
				$disabled_with_it = elgg_get_entities([
					'relationship' => 'disabled_with',
					'relationship_guid' => $this->guid,
					'inverse_relationship' => true,
					'limit' => false,
					'batch' => true,
					'batch_inc_offset' => false,
				]);

				foreach ($disabled_with_it as $e) {
					$e->enable($recursive);
					$e->removeRelationship($this->guid, 'disabled_with');
				}
			}

			return $result;
		});

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
	public function isEnabled(): bool {
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
	public function delete(bool $recursive = true): bool {
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
	 * @param array $params Params to pass to the event
	 * @return \Elgg\Export\Entity
	 */
	public function toObject(array $params = []) {
		$object = $this->prepareObject(new \Elgg\Export\Entity());

		$params['entity'] = $this;

		return _elgg_services()->events->triggerResults('to:object', 'entity', $params, $object);
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

	/**
	 * Set latitude and longitude metadata tags for a given entity.
	 *
	 * @param float $lat  Latitude
	 * @param float $long Longitude
	 *
	 * @return void
	 */
	public function setLatLong(float $lat, float $long): void {
		$this->{'geo:lat'} = $lat;
		$this->{'geo:long'} = $long;
	}

	/**
	 * Return the entity's latitude.
	 *
	 * @return float
	 */
	public function getLatitude(): float {
		return (float) $this->{'geo:lat'};
	}

	/**
	 * Return the entity's longitude
	 *
	 * @return float
	 */
	public function getLongitude(): float {
		return (float) $this->{'geo:long'};
	}

	/*
	 * SYSTEM LOG INTERFACE
	 */

	/**
	 * {@inheritdoc}
	 */
	public function getSystemLogID(): int {
		return (int) $this->getGUID();
	}

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the system log. It can be called on any Loggable object.
	 *
	 * @param int $id GUID
	 *
	 * @return \ElggEntity|null
	 */
	public function getObjectFromID(int $id): ?\ElggEntity {
		return get_entity($id);
	}

	/**
	 * Returns tags for this entity.
	 *
	 * @param array $tag_names Optionally restrict by tag metadata names. Defaults to metadata with the name 'tags'.
	 *
	 * @return array
	 */
	public function getTags($tag_names = null) {
		if (!isset($tag_names)) {
			$tag_names = ['tags'];
		}
		
		if ($tag_names && !is_array($tag_names)) {
			$tag_names = [$tag_names];
		}

		$entity_tags = [];
		foreach ($tag_names as $tag_name) {
			$tags = $this->$tag_name;
			if (elgg_is_empty($tags)) {
				continue;
			}
			
			// if a single tag, metadata returns a string.
			// if multiple tags, metadata returns an array.
			if (is_array($tags)) {
				$entity_tags = array_merge($entity_tags, $tags);
			} else {
				$entity_tags[] = $tags;
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
			$result &= $ac->removeUser($this->guid, $collection->id);
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
	 * @return int
	 * @internal
	 */
	public function updateLastAction(int $posted = null): int {
		$posted = _elgg_services()->entityTable->updateLastAction($this, $posted);
		
		$this->attributes['last_action'] = $posted;
		$this->cache();
	
		return $posted;
	}

    /**
     * Update the time_soft_deleted column in the entities table.
     *
     *
     * @param int $posted Timestamp of last action
     * @return int
     * @internal
     */
    public function updateTimeSoftDeleted(int $posted = null): int {
        $posted = _elgg_services()->entityTable->updateTimeSoftDeleted($this, $posted);

        $this->attributes['time_soft_deleted'] = $posted;
        $this->cache();

        return $posted;
    }

	/**
	 * Disable runtime caching for entity
	 *
	 * @return void
	 * @internal
	 */
	public function disableCaching(): void {
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
	public function enableCaching(): void {
		$this->_is_cacheable = true;
	}

	/**
	 * Is entity cacheable in the runtime cache
	 *
	 * @return bool
	 * @internal
	 */
	public function isCacheable(): bool {
		if (!$this->guid) {
			return false;
		}
		
		if (_elgg_services()->session_manager->getIgnoreAccess()) {
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
	public function cache(bool $persist = true): void {
		if (!$this->isCacheable()) {
			return;
		}

		_elgg_services()->entityCache->save($this);

		if (!$persist) {
			return;
		}
		
		$tmp = $this->volatile;

		// don't store volatile data
		$this->volatile = [];

		_elgg_services()->sessionCache->entities->save($this->guid, $this);

		$this->volatile = $tmp;
	}

	/**
	 * Invalidate cache for entity
	 *
	 * @return void
	 * @internal
	 */
	public function invalidateCache(): void {
		if (!$this->guid) {
			return;
		}

		_elgg_services()->entityCache->delete($this->guid);
		_elgg_services()->dataCache->get('metadata')->delete($this->guid);
	}
	
	/**
	 * Checks a specific capability is enabled for the entity type/subtype
	 *
	 * @param string $capability capability to check
	 *
	 * @return bool
	 * @since 4.1
	 */
	public function hasCapability(string $capability): bool {
		return _elgg_services()->entity_capabilities->hasCapability($this->getType(), $this->getSubtype(), $capability);
	}
}
