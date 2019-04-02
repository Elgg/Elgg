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
 * @todo remove this class in Elgg 4.0 in favour of SubscriptionNotificationEvent (see https://github.com/Elgg/Elgg/issues/11241)
 */
class Event implements NotificationEvent {

	use EventSerialization;
	
	/* @var string The name of the action/event */
	protected $action;

	/* @var string Action's object */
	protected $object;

	/* @var ElggEntity User who triggered the event */
	protected $actor;

	/**
	 * Create a notification event
	 *
	 * @param ElggData   $object The object of the event (ElggEntity)
	 * @param string     $action The name of the action (default: create)
	 * @param ElggEntity $actor  The entity that caused the event (default: logged in user)
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(ElggData $object, $action, ElggEntity $actor = null) {
		if (get_class($this) == Event::class) {
			_elgg_services()->deprecation->sendNotice(__CLASS__ . ' is deprecated. '
					. 'Use ' . SubscriptionNotificationEvent::class . ' instead', '2.3');
		}
		if (!$object instanceof ElggData) {
			throw new InvalidArgumentException(__METHOD__ . ' expects an object as an instance of ' . ElggData::class);
		}
		if (!$action) {
			throw new InvalidArgumentException(__METHOD__ . ' expects a valid action name');
		}
		
		$this->object = $object;

		$this->actor = $actor;
		if (!isset($actor)) {
			$this->actor = _elgg_services()->session->getLoggedInUser();
		}

		$this->action = $action;
	}

	/**
	 * Get the actor of the event
	 *
	 * @note Note that the actor and the object of the notification event
	 * may have been deleted/disabled since the event was serialized and
	 * stored in the database.
	 *
	 * @return ElggEntity|false|null
	 */
	public function getActor() {
		return $this->actor;
	}

	/**
	 * Get the GUID of the actor
	 *
	 * @note Note that the actor and the object of the notification event
	 * may have been deleted/disabled since the event was serialized and
	 * stored in the database.
	 *
	 * @return int
	 */
	public function getActorGUID() {
		return $this->actor ? $this->actor->guid : 0;
	}

	/**
	 * Get the object of the event
	 *
	 * @note Note that the actor and the object of the notification event
	 * may have been deleted/disabled since the event was serialized and
	 * stored in the database.
	 *
	 * @return ElggData|false|null
	 */
	public function getObject() {
		return $this->object;
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
		return implode(':', [
			$this->action,
			$this->object->getType(),
			$this->object->getSubtype(),
		]);
	}

	/**
	 * Export the notification event into a serializable object
	 * This method is mainly used for logging purposes
	 *
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

