<?php

namespace Elgg\Groups\Notifications;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Send a notification for the 'relationship', 'membership_request', 'create' event
 *
 * @since 6.3
 */
class RequestMembershipEventHandler extends NonConfigurableNotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$result = [];
		
		$owner = $this->getMembershipGroup()?->getOwnerEntity();
		if ($owner instanceof \ElggUser) {
			$result[$owner->guid] = $owner->getNotificationSettings('default', true);
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		$user = $this->getMembershipUser();
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('groups:request:subject', [
			$user->getDisplayName(),
			$group->getDisplayName(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		$user = $this->getMembershipUser();
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return parent::getNotificationSummary($recipient, $method);
		}
		
		return elgg_echo('groups:request:subject', [
			$user->getDisplayName(),
			$group->getDisplayName(),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		$user = $this->getMembershipUser();
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('groups:request:body', [
			$user->getDisplayName(),
			$group->getDisplayName(),
			$user->getURL(),
			elgg_generate_url('requests:group:group', [
				'guid' => $group->guid,
			]),
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
		
		return elgg_generate_url('requests:group:group', [
			'guid' => $group->guid,
		]);
	}
	
	/**
	 * Get the membership relationship
	 *
	 * @return \ElggRelationship|null
	 */
	protected function getMembershipRelationship(): ?\ElggRelationship {
		$rel = $this->event->getObject();
		if ($rel instanceof \ElggRelationship && $rel->relationship === 'membership_request') {
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
