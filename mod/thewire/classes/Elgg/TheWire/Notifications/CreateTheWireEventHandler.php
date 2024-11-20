<?php

namespace Elgg\TheWire\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'thewire' 'create' action
 */
class CreateTheWireEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$subscriptions = parent::getSubscriptions();
	
		return $this->addOriginalPosterToSubscriptions($subscriptions);
	}
	
	/**
	 * Add subscription for original poster if not already registered to
	 * receive a notification of reply
	 *
	 * @param array $subscriptions Existing subscriptions
	 *
	 * @return array
	 */
	protected function addOriginalPosterToSubscriptions(array $subscriptions): array {
		$entity = $this->getEventEntity();
		if (!$entity instanceof \ElggWire) {
			return $subscriptions;
		}

		$parent = $entity->getParent();
		if (empty($parent)) {
			return $subscriptions;
		}
		
		// do not add a subscription if reply was to self
		if ($parent->owner_guid === $entity->owner_guid) {
			return $subscriptions;
		}
		
		if (array_key_exists($parent->owner_guid, $subscriptions)) {
			// already in the list
			return $subscriptions;
		}
		
		/* @var $parent_owner \ElggUser */
		$parent_owner = $parent->getOwnerEntity();
		$personal_methods = $parent_owner->getNotificationSettings();
		$methods = [];
		foreach ($personal_methods as $method => $state) {
			if ($state) {
				$methods[] = $method;
			}
		}
		
		if (empty($methods)) {
			return $subscriptions;
		}
		
		$subscriptions[$parent->owner_guid] = $methods;
		return $subscriptions;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('thewire:notify:subject', [$this->getEventActor()?->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('thewire:notify:summary', [elgg_get_excerpt((string) $this->getEventEntity()?->description)]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$entity = $this->getEventEntity();
		if (!$entity instanceof \ElggWire) {
			return '';
		}
		
		$owner = $entity->getOwnerEntity();
		
		$body = '';
		if ($entity->reply) {
			$parent = $entity->getParent();
			if ($parent instanceof \ElggWire) {
				$parent_owner = $parent->getOwnerEntity();
				$body = elgg_echo('thewire:notify:reply', [$owner?->getDisplayName(), $parent_owner?->getDisplayName()]);
			}
		}
		
		if (empty($body)) {
			$body = elgg_echo('thewire:notify:post', [$owner?->getDisplayName()]);
		}
		
		$body .= PHP_EOL . PHP_EOL;
		$body .= $entity->description;
		$body .= PHP_EOL . PHP_EOL;
		
		$body .= elgg_echo('thewire:notify:footer', [$entity->getURL()]);
		
		return $body;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return false;
	}
}
