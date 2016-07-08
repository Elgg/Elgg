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

	/* @var string The type of the action's object */
	protected $object_type;

	/* @var string the subtype of the action's object */
	protected $object_subtype;

	/* @var int The identifier of the object (GUID for entity) */
	protected $object_id;

	/* @var int The GUID of the user who triggered the event */
	protected $actor_guid;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(ElggData $object = null, $action = null, ElggEntity $actor = null) {
		if (elgg_instanceof($object)) {
			$this->object_type = $object->getType();
			$this->object_subtype = $object->getSubtype();
			$this->object_id = $object->getGUID();
		} else if ($object) {
			$this->object_type = $object->getType();
			$this->object_subtype = $object->getSubtype();
			$this->object_id = $object->id;
		}

		if ($actor == null) {
			$this->actor_guid = _elgg_services()->session->getLoggedInUserGuid();
		} else {
			$this->actor_guid = $actor->getGUID();
		}

		$this->action = $action ? : self::DEFAULT_ACTION_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActor() {
		return get_entity($this->actor_guid);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getActorGUID() {
		return $this->actor_guid;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObject() {
		switch ($this->object_type) {
			case 'object':
			case 'user':
			case 'site':
			case 'group':
				return get_entity($this->object_id);
			case 'relationship':
				return get_relationship($this->object_id);
			case 'annotation':
				return elgg_get_annotation_from_id($this->object_id);
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDescription() {
		$parts = [
			$this->action,
			$this->object_type,
			$this->object_subtype,
		];
		return implode(':', array_filter($parts));
	}

	/**
	 * Export
	 * @return \stdClass
	 */
	public function toObject() {
		$obj = new \stdClass();
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
