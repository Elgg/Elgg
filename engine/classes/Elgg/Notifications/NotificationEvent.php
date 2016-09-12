<?php

namespace Elgg\Notifications;

use ElggData;
use ElggEntity;
use Serializable;

/**
 * Notification event interface
 *
 * @package    Elgg.Core
 * @subpackage Notifications
 */
interface NotificationEvent extends Serializable {

	/**
	 * Get the actor of the event
	 *
	 * @return ElggEntity|false
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
	 * @return ElggData
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
