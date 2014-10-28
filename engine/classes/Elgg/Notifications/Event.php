<?php
/**
 * Notification event
 *
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 */
class Elgg_Notifications_Event {
	/* @var string The name of the action/event */
	protected $action;

	/* @var string The type of the action's object */
	protected $object_type;

	/* @var string the subtype of the action's object */
	protected $object_subtype;

	/* @var int The identifier of the object (GUID for entity) */
	protected $object_id;

	/* @var int The GUID of the user who triggered the event */
	protected $actor_guid;


	/**
	 * Create a notification event
	 *
	 * @param ElggData $object The object of the event (ElggEntity)
	 * @param string   $action The name of the action (default: create)
	 * @param ElggUser $actor  The user that caused the event (default: logged in user)
	 * @throws InvalidArgumentException
	 */
	public function __construct(ElggData $object, $action, ElggUser $actor = null) {
		if (elgg_instanceof($object)) {
			$this->object_type = $object->getType();
			$this->object_subtype = $object->getSubtype();
			$this->object_id = $object->getGUID();
		} else {
			$this->object_type = $object->getType();
			$this->object_subtype = $object->getSubtype();
			$this->object_id = $object->id;
		}

		if ($actor == null) {
			$this->actor_guid = elgg_get_logged_in_user_guid();
		} else {
			$this->actor_guid = $actor->getGUID();
		}

		$this->action = $action;
	}

	/**
	 * Get the actor of the event
	 *
	 * @return ElggUser
	 */
	public function getActor() {
		return get_entity($this->actor_guid);
	}

	/**
	 * Get the GUID of the actor
	 *
	 * @return int
	 */
	public function getActorGUID() {
		return $this->actor_guid;
	}

	/**
	 * Get the object of the event
	 *
	 * @return ElggData
	 */
	public function getObject() {
		switch ($this->object_type) {
			case 'object':
			case 'user':
			case 'site':
			case 'group':
				return get_entity($this->object_id);
				break;
			case 'relationship':
				return get_relationship($this->object_id);
				break;
			case 'annotation':
				return elgg_get_annotation_from_id($this->object_id);
				break;
		}
		return null;
	}

	/**
	 * Get the name of the action
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Get a description of the event
	 *
	 * @return string
	 */
	public function getDescription() {
		return "{$this->action}:{$this->object_type}:{$this->object_subtype}";
	}
}