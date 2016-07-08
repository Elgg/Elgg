<?php

namespace Elgg\Notifications;

use ElggData;
use ElggEntity;
use InvalidArgumentException;

/**
 * Notification event interface
 *
 * @package    Elgg.Core
 * @subpackage Notifications
 */
interface NotificationEvent {

	/**
	 * Create a notification event
	 *
	 * @param ElggData   $object The object of the event, if any (ElggEntity)
	 * @param string     $action The name of the action that triggered the event (e.g. create or notify_user)
	 * @param ElggEntity $actor  The entity that caused the event (default: logged in user)
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(ElggData $object = null, $action = null, ElggEntity $actor = null);

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
