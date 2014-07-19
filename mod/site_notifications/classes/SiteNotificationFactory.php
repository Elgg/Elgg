<?php

abstract class SiteNotificationFactory {

	/**
	 * Create a site notification
	 *
	 * @param ElggUser   $recipient Recipient of the notification
	 * @param string     $message   Notification message
	 * @param ElggUser   $actor     User who caused the notification event
	 * @param ElggData   $object    Optional object involved in the notification event
	 * @return SiteNotification|null
	 */
	public static function create($recipient, $message, $actor, $object = null) {
		$note = new SiteNotification();
		$note->owner_guid = $recipient->guid;
		$note->container_guid = $recipient->guid;
		$note->access_id = ACCESS_PRIVATE;
		$note->description = $message;
		if ($object) {
			// TODO Add support for setting an URL for a notification about a new relationship
			switch ($object->getType()) {
				case 'annotation':
					// Annotations do not have an URL so we use the entity URL
					$note->setURL($object->getEntity()->getURL());
					break;
				default:
					$note->setURL($object->getURL());
			}
		}
		$note->setRead(false);

		if ($note->save()) {
			$note->setActor($actor);
			return $note;
		} else {
			return null;
		}
	}
}
