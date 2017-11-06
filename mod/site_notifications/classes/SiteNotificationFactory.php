<?php

/**
 * Site notification factory
 */
abstract class SiteNotificationFactory {

	/**
	 * Create a site notification
	 *
	 * @param ElggUser $recipient Recipient of the notification
	 * @param string   $message   Notification message
	 * @param ElggUser $actor     User who caused the notification event
	 * @param ElggData $object    Optional object involved in the notification event
	 * @param string   $url       Target URL
	 *
	 * @return void|SiteNotification
	 */
	public static function create($recipient, $message, $actor, $object = null, $url = null) {
		$note = new SiteNotification();
		$note->owner_guid = $recipient->guid;
		$note->container_guid = $recipient->guid;
		$note->access_id = ACCESS_PRIVATE;
		$note->description = $message;

		if (!isset($url) && $object) {
			// TODO Add support for setting an URL for a notification about a new relationship
			switch ($object->getType()) {
				case 'annotation':
					// Annotations do not have an URL so we use the entity URL
					$url = $object->getEntity()->getURL();
					break;
				default:
					$url = $object->getURL();
					break;
			}
		}

		if ($url && $url != elgg_get_site_url()) {
			$note->setURL($url);
		}
		
		$note->setRead(false);

		if ($note->save()) {
			$note->setActor($actor);
			return $note;
		}
	}
}
