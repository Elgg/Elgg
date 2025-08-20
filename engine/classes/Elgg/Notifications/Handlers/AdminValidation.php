<?php

namespace Elgg\Notifications\Handlers;

use Elgg\Notifications\NonConfigurableNotificationEventHandler;

/**
 * Send a notification to all (subscribed) site admins that there are unvalidated users
 *
 * @since 6.3
 */
class AdminValidation extends NonConfigurableNotificationEventHandler {
	
	protected int $unvalidated_count = 0;
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$this->unvalidated_count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
			return elgg_count_entities([
				'type' => 'user',
				'metadata_name_value_pairs' => [
					'validated' => 0,
				],
			]);
		});
		if (empty($this->unvalidated_count)) {
			return [];
		}
		
		$result = [];
		
		$admins = elgg_get_admins([
			'limit' => false,
			'batch' => true,
		]);
		/* @var $admin \ElggUser */
		foreach ($admins as $admin) {
			$notification_preferences = $admin->getNotificationSettings('admin_validation_notification', true);
			if (empty($notification_preferences)) {
				continue;
			}
			
			$result[$admin->guid] = $notification_preferences;
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('admin:notification:unvalidated_users:subject', [$site->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('admin:notification:unvalidated_users:subject', [$site->getDisplayName()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$site = elgg_get_site_entity();
		
		return elgg_echo('admin:notification:unvalidated_users:body', [
			$this->unvalidated_count,
			$site->getDisplayName(),
			elgg_generate_url('admin', [
				'segments' => 'users/unvalidated',
			]),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		return elgg_generate_url('admin', [
			'segments' => 'users/unvalidated',
		]);
	}
}
