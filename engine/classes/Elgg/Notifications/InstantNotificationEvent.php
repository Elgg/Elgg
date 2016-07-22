<?php

namespace Elgg\Notifications;

use ElggData;
use ElggEntity;

/**
 * Instant notification event
 *
 * @since 2.3
 */
class InstantNotificationEvent implements NotificationEvent {

	const DEFAULT_ACTION_NAME = 'notify_user';

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
	public function __construct(ElggData $object = null, $action = null, ElggEntity $actor = null) {

		$this->object = $object;

		$this->actor = $actor;
		if (!isset($actor)) {
			$this->actor = _elgg_services()->session->getLoggedInUser();
		}

		$this->action = $action ? : self::DEFAULT_ACTION_NAME;
	}

	/**
	 * Get the actor of the event
	 *
	 * @return ElggEntity|false
	 */
	public function getActor() {
		return $this->actor;
	}

	/**
	 * Get the GUID of the actor
	 *
	 * @return int
	 */
	public function getActorGUID() {
		return $this->actor ? $this->actor->guid : 0;
	}

	/**
	 * Get the object of the event
	 *
	 * @return ElggData|null
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
		if (!$this->object) {
			return $this->action;
		}
		
		return implode(':', [
			$this->action,
			$this->object->getType(),
			$this->object->getSubtype(),
		]);
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
