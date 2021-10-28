<?php

namespace Elgg\TheWire\Notifications;

use Elgg\Notifications\NotificationEventHandler;

/**
 * Notification Event Handler for 'object' 'thewire' 'create' action
 */
class CreateTheWireEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritDoc}
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
		$entity = $this->event->getObject();
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
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('thewire:notify:subject', [$this->event->getActor()->getDisplayName()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('thewire:notify:summary', [elgg_get_excerpt($this->event->getObject()->description)], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		/* @var $entity \ElggWire */
		$entity = $this->event->getObject();
		$owner = $entity->getOwnerEntity();
		$language = $recipient->getLanguage();
		
		$body = '';
		if ($entity->reply) {
			$parent = $entity->getParent();
			if ($parent) {
				$parent_owner = $parent->getOwnerEntity();
				$body = elgg_echo('thewire:notify:reply', [$owner->getDisplayName(), $parent_owner->getDisplayName()], $language);
			}
		} else {
			$body = elgg_echo('thewire:notify:post', [$owner->getDisplayName()], $language);
		}
		
		$body .= PHP_EOL . PHP_EOL;
		$body .= $entity->description;
		$body .= PHP_EOL . PHP_EOL;
		
		$body .= elgg_echo('thewire:notify:footer', [$entity->getURL()], $language);
		
		return $body;
	}
}
