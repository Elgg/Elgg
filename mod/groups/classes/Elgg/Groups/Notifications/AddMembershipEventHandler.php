<?php

namespace Elgg\Groups\Notifications;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Send a notification for the 'relationship', 'member', 'add_membership' event
 *
 * @since 6.3
 */
class AddMembershipEventHandler extends NonConfigurableNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$result = [];
		
		$user = $this->getMembershipUser();
		if ($user instanceof \ElggUser) {
			$result[$user->guid] = array_keys(array_filter($user->getNotificationSettings()));
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		if (!$group instanceof \ElggGroup) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('groups:welcome:subject', [$group->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		if (!$group instanceof \ElggGroup) {
			return parent::getNotificationSummary($recipient, $method);
		}
		
		return elgg_echo('groups:welcome:subject', [$group->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		if (!$group instanceof \ElggGroup) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('groups:welcome:body', [
			$group->getDisplayName(),
			$group->getURL(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		if (!$group instanceof \ElggGroup) {
			return parent::getNotificationURL($recipient, $method);
		}
		
		return $group->getURL();
	}
	
	/**
	 * Get the membership relationship
	 *
	 * @return \ElggRelationship|null
	 */
	protected function getMembershipRelationship(): ?\ElggRelationship {
		$rel = $this->event->getObject();
		if ($rel instanceof \ElggRelationship && $rel->relationship === 'member') {
			return $rel;
		}
		
		return null;
	}
	
	/**
	 * Get the membership group
	 *
	 * @return \ElggGroup|null
	 */
	protected function getMembershipGroup(): ?\ElggGroup {
		$rel = $this->getMembershipRelationship();
		
		$group = $rel ? get_entity($rel->guid_two) : null;
		
		return $group instanceof \ElggGroup ? $group : null;
	}
	
	/**
	 * Get the membership user
	 *
	 * @return \ElggUser|null
	 */
	protected function getMembershipUser(): ?\ElggUser {
		$rel = $this->getMembershipRelationship();
		
		$user = $rel ? get_user($rel->guid_one) : null;
		
		return $user instanceof \ElggUser ? $user : null;
	}
}
