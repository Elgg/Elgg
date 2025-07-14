<?php

namespace Elgg\Groups\Notifications;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Send a notification for the 'relationship', 'invited', 'create' event
 *
 * @since 6.3
 */
class InviteMembershipEventHandler extends NonConfigurableNotificationEventHandler {
	
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
		$user = $this->getMembershipUser();
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser) {
			return parent::getNotificationSubject($recipient, $method);
		}
		
		return elgg_echo('groups:invite:subject', [
			$user->getDisplayName(),
			$group->getDisplayName()
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
		
		return elgg_echo('groups:invite:subject', [
			$user->getDisplayName(),
			$group->getDisplayName()
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$group = $this->getMembershipGroup();
		$user = $this->getMembershipUser();
		$actor = $this->getEventActor();
		if (!$group instanceof \ElggGroup || !$user instanceof \ElggUser || !$actor instanceof \ElggUser) {
			return parent::getNotificationBody($recipient, $method);
		}
		
		return elgg_echo('groups:invite:body', [
			$actor->getDisplayName(),
			$group->getDisplayName(),
			elgg_generate_url('collection:group:group:invitations', [
				'username' => $user->username,
			]),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$user = $this->getMembershipUser();
		if (!$user instanceof \ElggUser) {
			return parent::getNotificationURL($recipient, $method);
		}
		
		return elgg_generate_url('collection:group:group:invitations', [
			'username' => $user->username,
		]);
	}
	
	/**
	 * Get the membership relationship
	 *
	 * @return \ElggRelationship|null
	 */
	protected function getMembershipRelationship(): ?\ElggRelationship {
		$rel = $this->event->getObject();
		if ($rel instanceof \ElggRelationship && $rel->relationship === 'invited') {
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
		
		$group = $rel ? get_entity($rel->guid_one) : null;
		
		return $group instanceof \ElggGroup ? $group : null;
	}
	
	/**
	 * Get the membership user
	 *
	 * @return \ElggUser|null
	 */
	protected function getMembershipUser(): ?\ElggUser {
		$rel = $this->getMembershipRelationship();
		
		$user = $rel ? get_user($rel->guid_two) : null;
		
		return $user instanceof \ElggUser ? $user : null;
	}
}
