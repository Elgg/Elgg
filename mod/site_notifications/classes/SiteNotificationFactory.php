<?php

abstract class SiteNotificationFactory {

	/**
	 * Create a site notification
	 * 
	 * @param ElggUser   $recipient
	 * @param string     $message
	 * @param ElggEntity $actor
	 * @return SiteNotification|null
	 */
	public static function create($recipient, $message, $actor) {
		$note = new SiteNotification();
		$note->owner_guid = $recipient->guid;
		$note->container_guid = $recipient->guid;
		$note->access_id = ACCESS_PRIVATE;
		$note->description = $message;
		$note->setRead(false);

		if ($note->save()) {
			$note->setActor($actor);
			return $note;
		} else {
			return null;
		}
	}
}
