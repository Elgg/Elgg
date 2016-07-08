<?php
namespace Elgg\Notifications;

use ElggData;
use ElggEntity;
use InvalidArgumentException;
use stdClass;

/**
 * Subscription notification event
 * 
 * @package    Elgg.Core
 * @subpackage Notifications
 * @deprecated 2.3
 */
class Event implements NotificationEvent {
	
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
	 * @param ElggData   $object The object of the event (ElggEntity)
	 * @param string     $action The name of the action (default: create)
	 * @param ElggEntity $actor  The entity that caused the event (default: logged in user)
	 * 
	 * @throws InvalidArgumentException
	 */
	public function __construct(ElggData $object = null, $action = null, ElggEntity $actor = null) {
		if (get_class($this) == Event::class || get_class($this) == Elgg_Notifications_Event::class) {
			_elgg_services()->deprecation->sendNotice(__CLASS__ . ' is deprecated. '
					. 'Use ' . SubscriptionNotificationEvent::class . ' instead', '2.3');
		}
		if (!$object instanceof ElggData) {
			throw new InvalidArgumentException(__METHOD__ . ' expects an object as an instance of ' . ElggData::class);
		}
		if (!$action) {
			throw new InvalidArgumentException(__METHOD__ . ' expects a valid action name');
		}
		
		if (elgg_instanceof($object)) {
			$this->object_type = $object->getType();
			$this->object_subtype = $object->getSubtype();
			$this->object_id = $object->guid;
		} else {
			$this->object_type = $object->getType();
			$this->object_subtype = $object->getSubtype();
			$this->object_id = $object->id;
		}
	
		if ($actor == null) {
			$this->actor_guid = _elgg_services()->session->getLoggedInUserGuid();
		} else {
			$this->actor_guid = $actor->guid;
		}

		$this->action = $action;
	}

	/**
	 * Get the actor of the event
	 *
	 * @return ElggEntity|false
	 */
	public function getActor() {
		return _elgg_services()->entityTable->get($this->actor_guid);
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
				return _elgg_services()->entityTable->get($this->object_id);
				
			case 'relationship':
				return get_relationship($this->object_id);
				
			case 'annotation':
				return elgg_get_annotation_from_id($this->object_id);
				
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

	/**
	 * Export
	 * @return stdClass
	 */
	public function toObject() {
		$obj = new stdClass();
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if (is_object($value) && is_callable([$value, 'toObject'])) {
				$obj->$key = $value->toObject();
			} else {
				$obj->$key = $value;
			}
		}
		return $obj;
	}
}

/**
 * Notification event
 * 
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 * @deprecated 2.3
 */
class Elgg_Notifications_Event extends Event {}

