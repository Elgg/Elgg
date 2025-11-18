<?php

use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\DatabaseException;
use Elgg\Exceptions\Filesystem\IOException;
use Elgg\Exceptions\DomainException as ElggDomainException;
use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Traits\Entity\AccessCollections;
use Elgg\Traits\Entity\Annotations;
use Elgg\Traits\Entity\Icons;
use Elgg\Traits\Entity\Metadata;
use Elgg\Traits\Entity\Relationships;
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
 * @property-read  int    $time_deleted   A UNIX timestamp of when the entity was deleted
 * @property-read  string $deleted        Is this entity deleted ('yes' or 'no')
 * @property-read  string $enabled        Is this entity enabled ('yes' or 'no')
 *
 * Metadata (the above are attributes)
 * @property       string $location       A location of the entity
 */
abstract class ElggEntity extends \ElggData {

	use AccessCollections;
	use Annotations;
	use Icons;
	use Metadata;
	use Relationships;
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
		'deleted',
		'time_deleted',
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
		'time_deleted',
	];

	/**
	 * Volatile data structure for this object, allows for storage of data
	 * in-memory that isn't synced back to the metadata table.
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
	 * Create a new entity.
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * If no arguments are passed, it creates a new entity.
	 * If a database result is passed as a \stdClass instance, it instantiates
	 * that entity.
	 *
	 * @param null|\stdClass $row Database row result. Default is null to create a new object.
	 *
	 * @throws IOException If cannot load remaining data from db
	 */
	public function __construct(?\stdClass $row = null) {
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
		$this->attributes['type'] = null;
		$this->attributes['subtype'] = null;

		$this->attributes['owner_guid'] = _elgg_services()->session_manager->getLoggedInUserGuid();
		$this->attributes['container_guid'] = _elgg_services()->session_manager->getLoggedInUserGuid();

		$this->attributes['access_id'] = ACCESS_PRIVATE;
		$this->attributes['time_updated'] = null;
		$this->attributes['last_action'] = null;
		$this->attributes['enabled'] = 'yes';
		$this->attributes['deleted'] = 'no';
		$this->attributes['time_deleted'] = null;
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
			
			// arrays are stored with multiple entries per name
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
				case 'time_deleted':
				case 'time_updated':
				case 'type':
					return;
				case 'subtype':
					throw new ElggInvalidArgumentException(elgg_echo('ElggEntity:Error:SetSubtype', ['setSubtype()']));
				case 'enabled':
					throw new ElggInvalidArgumentException(elgg_echo('ElggEntity:Error:SetEnabled', ['enable() / disable()']));
				case 'deleted':
					throw new ElggInvalidArgumentException(elgg_echo('ElggEntity:Error:SetDeleted', ['delete() / restore()']));
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
	 *
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
	 *
	 * @return void
	 */
	public function setDisplayName(string $display_name): void {
		$this->name = $display_name;
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
		return (string) $this->attributes['type'];
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

		$url = _elgg_services()->events->triggerResults('entity:url', "{$this->getType()}:{$this->getSubtype()}", ['entity' => $this], $url);
		$url = _elgg_services()->events->triggerResults('entity:url', $this->getType(), ['entity' => $this], $url);
		if (empty($url)) {
			return '';
		}

		return elgg_normalize_url($url);
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
	 * @return int|false The new entity's GUID or false if prevented by an event handler
	 *
	 * @throws \Elgg\Exceptions\DomainException If the entity's type has not been set.
	 * @throws \Elgg\Exceptions\InvalidArgumentException If the entity's subtype has not been set, access_id is invalid or something is wrong with the owner or container
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
		$deleted = $this->attributes['deleted'];
		$time_deleted = (int) $this->attributes['time_deleted'];

		$container_guid = $this->attributes['container_guid'];
		if ($container_guid == 0) {
			$container_guid = $owner_guid;
			$this->attributes['container_guid'] = $container_guid;
		}
		
		$container_guid = (int) $container_guid;

		if ($access_id === ACCESS_DEFAULT) {
			throw new ElggInvalidArgumentException('ACCESS_DEFAULT is not a valid access level. See its documentation in constants.php');
		}
		
		if ($access_id === ACCESS_FRIENDS) {
			throw new ElggInvalidArgumentException('ACCESS_FRIENDS is not a valid access level. See its documentation in constants.php');
		}

		$user_guid = _elgg_services()->session_manager->getLoggedInUserGuid();

		// If given an owner, verify it can be loaded
		if (!empty($owner_guid)) {
			$owner = $this->getOwnerEntity();
			if (!$owner instanceof \ElggEntity) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}),";
				$error .= " but the given owner {$owner_guid} could not be loaded.";
				throw new ElggInvalidArgumentException($error);
			}

			// If different owner than logged in, verify can write to container.
			if ($user_guid !== $owner_guid && !$owner->canEdit() && !$owner->canWriteToContainer($user_guid, $type, $subtype)) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}) with owner {$owner_guid},";
				$error .= " but the user wasn't permitted to write to the owner's container.";
				throw new ElggInvalidArgumentException($error);
			}
		}

		// If given a container, verify it can be loaded and that the current user can write to it
		if (!empty($container_guid)) {
			$container = $this->getContainerEntity();
			if (!$container instanceof \ElggEntity) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}),";
				$error .= " but the given container {$container_guid} could not be loaded.";
				throw new ElggInvalidArgumentException($error);
			}

			if (!$container->canWriteToContainer($user_guid, $type, $subtype)) {
				$error = "User {$user_guid} tried to create a ({$type}, {$subtype}),";
				$error .= " but was not permitted to write to container {$container_guid}.";
				throw new ElggInvalidArgumentException($error);
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
			'deleted' => $deleted,
			'time_deleted' => $time_deleted
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
		$this->attributes['deleted'] = $deleted;
		$this->attributes['time_deleted'] = (int) $time_deleted;

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
			$container->updateLastAction($this->time_created);
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
		$deleted = $this->deleted;
		$time_deleted = (int) $this->time_deleted;

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
			'deleted' => $deleted,
			'time_deleted' => $time_deleted
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
			// not using ban function to bypass events
			$this->setMetadata('banned', 'yes');
			$unban_after = true;
		} else {
			$unban_after = false;
		}

		if (!empty($reason)) {
			$this->disable_reason = $reason;
		}

		$guid = (int) $this->guid;

		if ($recursive) {
			elgg_call(ELGG_IGNORE_ACCESS | ELGG_HIDE_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function () use ($guid, $reason) {
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

		$disabled = _elgg_services()->entityTable->disable($this);

		if ($unban_after) {
			$this->setMetadata('banned', 'no');
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
	 *
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

		$result = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($recursive) {
			$result = _elgg_services()->entityTable->enable($this);
			
			$this->deleteMetadata('disable_reason');

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
	 * @param bool      $recursive  If true (default) then all entities which are owned or contained by $this will also be deleted.
	 * @param bool|null $persistent persistently delete the entity (default: check the 'restorable' capability)
	 *
	 * @return bool
	 */
	public function delete(bool $recursive = true, ?bool $persistent = null): bool {
		if (!$this->canDelete()) {
			return false;
		}

		if (!elgg_get_config('trash_enabled')) {
			$persistent = true;
		}
		
		if (!isset($persistent)) {
			$persistent = !$this->hasCapability('restorable');
		}
		
		try {
			if (empty($this->guid) || $persistent) {
				return $this->persistentDelete($recursive);
			} else {
				return $this->trash($recursive);
			}
		} catch (DatabaseException $ex) {
			elgg_log($ex, \Psr\Log\LogLevel::ERROR);
			return false;
		}
	}
	
	/**
	 * Permanently delete the entity from the database
	 *
	 * @param bool $recursive If true (default) then all entities which are owned or contained by $this will also be deleted.
	 *
	 * @return bool
	 * @since 6.0
	 */
	protected function persistentDelete(bool $recursive = true): bool {
		return _elgg_services()->entityTable->delete($this, $recursive);
	}
	
	/**
	 * Move the entity to the trash
	 *
	 * @param bool $recursive If true (default) then all entities which are owned or contained by $this will also be trashed.
	 *
	 * @return bool
	 * @since 6.0
	 */
	protected function trash(bool $recursive = true): bool {
		$result = _elgg_services()->entityTable->trash($this, $recursive);
		if ($result) {
			$this->attributes['deleted'] = 'yes';
		}
		
		return $result;
	}
	
	/**
	 * Restore the entity
	 *
	 * @param bool $recursive Recursively restores all entities trashed with the entity?
	 *
	 * @return bool
	 * @since 6.0
	 */
	public function restore(bool $recursive = true): bool {
		if (!$this->isDeleted()) {
			return true;
		}
		
		if (empty($this->guid) || !$this->canEdit()) {
			return false;
		}
		
		return _elgg_services()->events->triggerSequence('restore', $this->type, $this, function () use ($recursive) {
			return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($recursive) {
				if (!_elgg_services()->entityTable->restore($this)) {
					return false;
				}
				
				$this->attributes['deleted'] = 'no';
				$this->attributes['time_deleted'] = 0;
				
				$this->removeAllRelationships('deleted_by');
				$this->removeAllRelationships('deleted_with');
				
				if (!$recursive) {
					return true;
				}
				
				set_time_limit(0);
				
				/* @var $deleted_with_it \ElggBatch */
				$deleted_with_it = elgg_get_entities([
					'relationship' => 'deleted_with',
					'relationship_guid' => $this->guid,
					'inverse_relationship' => true,
					'limit' => false,
					'batch' => true,
					'batch_inc_offset' => false,
				]);
				
				/* @var $e \ElggEntity */
				foreach ($deleted_with_it as $e) {
					if (!$e->restore($recursive)) {
						$deleted_with_it->reportFailure();
						continue;
					}
				}
				
				return true;
			});
		});
	}
	
	/**
	 * Is the entity marked as deleted
	 *
	 * @return bool
	 */
	public function isDeleted(): bool {
		return $this->deleted === 'yes';
	}
	
	/**
	 * Export an entity
	 *
	 * @param array $params Params to pass to the event
	 *
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
	 *
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
	 * Update the last_action column in the entities table.
	 *
	 * @warning This is different to time_updated.  Time_updated is automatically set,
	 * while last_action is only set when explicitly called.
	 *
	 * @param null|int $posted Timestamp of last action
	 *
	 * @return int
	 * @internal
	 */
	public function updateLastAction(?int $posted = null): int {
		$posted = _elgg_services()->entityTable->updateLastAction($this, $posted);
		
		$this->attributes['last_action'] = $posted;
		$this->cache();
		
		return $posted;
	}

	/**
	 * Update the time_deleted column in the entities table.
	 *
	 * @param null|int $deleted Timestamp of deletion
	 *
	 * @return int
	 * @internal
	 */
	public function updateTimeDeleted(?int $deleted = null): int {
		$deleted = _elgg_services()->entityTable->updateTimeDeleted($this, $deleted);
		
		$this->attributes['time_deleted'] = $deleted;
		$this->cache();
		
		return $deleted;
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
	 * Cache the entity in a session cache
	 *
	 * @return void
	 * @internal
	 */
	public function cache(): void {
		if (!$this->isCacheable()) {
			return;
		}

		_elgg_services()->entityCache->save($this->guid, $this);
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
		_elgg_services()->metadataCache->delete($this->guid);
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
	
	/**
	 * Returns a default set of fields to be used for forms related to this entity
	 *
	 * @return array
	 */
	public static function getDefaultFields(): array {
		return [];
	}
	
	/**
	 * Helper function to easily retrieve form fields for this entity
	 *
	 * @return array
	 */
	final public function getFields(): array {
		return _elgg_services()->fields->get($this->getType(), $this->getSubtype());
	}
}
