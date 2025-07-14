<?php

namespace Elgg\Messages\Notifications;

use Elgg\Notifications\InstantNotificationEventHandler;

/**
 * Send a notification about a new message in the inbox
 */
class SendMessageHandler extends InstantNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('messages:email:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('messages:email:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$actor = $this->getEventActor();
		$message = $this->event->getObject();
		if (!$message instanceof \ElggMessage || !$actor instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		$owner = $message->getOwnerEntity();
		
		return elgg_echo('messages:email:body', [
			$actor->getDisplayName(),
			$message->description,
			elgg_generate_url('collection:object:messages:owner', [
				'username' => $owner->username,
			]),
			$actor->getDisplayName(),
			elgg_generate_url('add:object:messages', [
				'send_to' => $actor->guid,
			]),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$message = $this->event->getObject();
		if (!$message instanceof \ElggMessage) {
			return parent::getNotificationURL($recipient, $method);
		}
		
		return $message->getURL();
	}
}
