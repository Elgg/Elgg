<?php

use Elgg\Exceptions\RuntimeException as ElggRuntimeException;

/**
 * River item class
 *
 * @property-read int    $id            The unique identifier (read-only)
 * @property      int    $subject_guid  The GUID of the actor
 * @property      int    $object_guid   The GUID of the object
 * @property      int    $target_guid   The GUID of the object's container
 * @property      int    $annotation_id The ID of the annotation involved in the action
 * @property      string $action_type   The name of the action
 * @property      string $view          The view for displaying this river item
 * @property      int    $posted        UNIX timestamp when the action occurred
 * @property      int    $last_action   UNIX timestamp when the river item was bumped
 */
class ElggRiverItem {
	
	/**
	 * @var string[] attributes that are integers
	 */
	protected const INTEGER_ATTR_NAMES = [
		'id',
		'subject_guid',
		'object_guid',
		'target_guid',
		'annotation_id',
		'access_id',
		'posted',
		'last_action',
	];
	
	protected array $attributes = [];
	
	/**
	 * Construct a river item object
	 *
	 * @param \stdClass $row (optional) object obtained from database
	 */
	public function __construct(\stdClass $row = null) {
		$this->initializeAttributes();
		
		if (empty($row)) {
			return;
		}
		
		// build from database
		foreach ($row as $key => $value) {
			if (!array_key_exists($key, $this->attributes)) {
				continue;
			}
			
			if (in_array($key, static::INTEGER_ATTR_NAMES)) {
				$value = (int) $value;
			}
			
			$this->attributes[$key] = $value;
		}
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Elgg\Exceptions\RuntimeException
	 */
	public function __set(string $name, $value) {
		if (!array_key_exists($name, $this->attributes)) {
			throw new ElggRuntimeException("It's not allowed to set {$name} on " . get_class($this));
		}
		
		if (in_array($name, static::INTEGER_ATTR_NAMES)) {
			$value = (int) $value;
		}
		
		$this->attributes[$name] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		switch ($name) {
			case 'type':
			case 'subtype':
				$object = $this->getObjectEntity();
				if ($object) {
					return $object->$name;
				}
				break;
			default:
				if (array_key_exists($name, $this->attributes)) {
					return $this->attributes[$name];
				}
				break;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __isset($name) : bool {
		return isset($this->attributes[$name]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __unset($name): void {
		if (!array_key_exists($name, $this->attributes)) {
			return;
		}
		
		$this->attributes[$name] = null;
	}
	
	/**
	 * Initialize the attributes array
	 *
	 * @return void
	 */
	protected function initializeAttributes(): void {
		$this->attributes['id'] = null;
		$this->attributes['action_type'] = null;
		$this->attributes['view'] = null;
		$this->attributes['subject_guid'] = null;
		$this->attributes['object_guid'] = null;
		$this->attributes['target_guid'] = null;
		$this->attributes['annotation_id'] = null;
		$this->attributes['posted'] = null;
		$this->attributes['last_action'] = null;
	}

	/**
	 * Get the subject of this river item
	 *
	 * @return \ElggEntity|null
	 */
	public function getSubjectEntity(): ?\ElggEntity {
		return $this->subject_guid ? get_entity($this->subject_guid) : null;
	}

	/**
	 * Get the object of this river item
	 *
	 * @return \ElggEntity|null
	 */
	public function getObjectEntity(): ?\ElggEntity {
		return $this->object_guid ? get_entity($this->object_guid) : null;
	}

	/**
	 * Get the target of this river item
	 *
	 * @return \ElggEntity|null
	 */
	public function getTargetEntity(): ?\ElggEntity {
		return $this->target_guid ? get_entity($this->target_guid) : null;
	}

	/**
	 * Get the Annotation for this river item
	 *
	 * @return \ElggAnnotation|null
	 */
	public function getAnnotation(): ?\ElggAnnotation {
		return $this->annotation_id ? elgg_get_annotation_from_id($this->annotation_id) : null;
	}

	/**
	 * Get the view used to display this river item
	 *
	 * @return string
	 */
	public function getView(): string {
		return (string) $this->view;
	}

	/**
	 * Get the time this activity was posted
	 *
	 * @return int
	 */
	public function getTimePosted(): int {
		return (int) $this->posted;
	}

	/**
	 * Update the last_action column in the river table.
	 *
	 * @param int $last_action Timestamp of last action
	 *
	 * @return int
	 */
	public function updateLastAction(int $last_action = null): int {
		$this->last_action = _elgg_services()->riverTable->updateLastAction($this, $last_action);

		return $this->last_action;
	}

	/**
	 * Get the type of the object
	 *
	 * This is required for elgg_view_list_item(). All the other data types
	 * (entities, extenders, relationships) have a type/subtype.
	 *
	 * @return string 'river'
	 */
	public function getType(): string {
		return 'river';
	}

	/**
	 * Get the subtype of the object
	 *
	 * This is required for elgg_view_list_item().
	 *
	 * @return string 'item'
	 */
	public function getSubtype(): string {
		return 'item';
	}

	/**
	 * Can a user delete this river item?
	 *
	 * @tip Can be overridden by registering for the "permissions_check:delete", "river" event.
	 *
	 * @param int $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this river item should be considered deletable by the given user.
	 * @since 2.3
	 */
	public function canDelete(int $user_guid = 0): bool {
		return _elgg_services()->userCapabilities->canDeleteRiverItem($this, $user_guid);
	}

	/**
	 * Delete the river item
	 *
	 * @return bool False if the user lacks permission or the before event is cancelled
	 * @since 2.3
	 */
	public function delete(): bool {
		if (!$this->canDelete()) {
			return false;
		}

		return _elgg_services()->riverTable->delete($this);
	}

	/**
	 * Get a plain old object copy for public consumption
	 *
	 * @return \stdClass
	 */
	public function toObject(): \stdClass {
		$object = new \stdClass();
		$object->id = $this->id;
		$object->subject_guid = $this->subject_guid;
		$object->target_guid = $this->target_guid;
		$object->object_guid = $this->object_guid;
		$object->annotation_id = $this->annotation_id;
		$object->action = $this->action_type;
		$object->view = $this->view;
		$object->time_posted = date('c', $this->getTimePosted());
		$object->last_action = date('c', $this->last_action);
		
		$params = ['item' => $this];
		return _elgg_services()->events->triggerResults('to:object', 'river_item', $params, $object);
	}
	
	/**
	 * Save the river item to the database
	 *
	 * @return bool
	 */
	public function save(): bool {
		if ($this->id) {
			// update (not supported)
			return true;
		}
		
		return (bool) _elgg_services()->riverTable->create($this);
	}
}
