<?php

namespace Elgg\MessageBoard\Notifications;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Send a notification when a new messageboard message is posted
 *
 * @since 6.3
 */
class CreateMessageboardNotificationHandler extends NonConfigurableNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$result = [];
		
		$user = $this->getAnnotation()?->getEntity();
		if ($user instanceof \ElggUser) {
			$result[$user->guid] = array_keys(array_filter($user->getNotificationSettings()));
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('messageboard:email:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('messageboard:email:subject');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$actor = $this->getEventActor();
		$annotation = $this->getAnnotation();
		$user = $annotation?->getEntity();
		if (!$user instanceof \ElggUser || !$actor instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('messageboard:email:body', [
			$actor->getDisplayName(),
			$annotation->value,
			elgg_generate_url('collection:annotation:messageboard:owner', [
				'username' => $user->username,
			]),
			$actor->getDisplayName(),
			$actor->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$user = $this->getAnnotation()?->getEntity();
		if (!$user instanceof \ElggUser) {
			return parent::getNotificationURL($recipient, $method);
		}
		
		return elgg_generate_url('collection:annotation:messageboard:owner', [
			'username' => $user->username,
		]);
	}
	
	/**
	 * Get the messageboard annotation
	 *
	 * @return \ElggAnnotation|null
	 */
	protected function getAnnotation(): ?\ElggAnnotation {
		$annotation = $this->event->getObject();
		if (!$annotation instanceof \ElggAnnotation || $annotation->name !== 'messageboard') {
			return null;
		}
		
		return $annotation;
	}
}
