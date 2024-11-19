<?php

namespace Elgg\Notifications;

/**
 * Notification event interface
 *
 * @internal
 * @todo typehint function return values
 */
interface NotificationEvent {

	/**
	 * Get the actor of the event
	 *
	 * @return \ElggEntity|false
	 * @todo change return type to nullable instead of false
	 */
	public function getActor();

	/**
	 * Get the GUID of the actor
	 *
	 * @return int
	 */
	public function getActorGUID();

	/**
	 * Get the object of the event
	 *
	 * @return \ElggData|false
	 * @todo change return type to nullable instead of false
	 */
	public function getObject();

	/**
	 * Get the name of the action
	 *
	 * @return string
	 */
	public function getAction();

	/**
	 * Get a description of the event
	 *
	 * @return string
	 */
	public function getDescription();
}
