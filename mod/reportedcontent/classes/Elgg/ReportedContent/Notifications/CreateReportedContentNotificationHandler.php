<?php

namespace Elgg\ReportedContent\Notifications;

use Elgg\Database\QueryBuilder;
use Elgg\Notifications\NotificationEventHandler;

/**
 * Handle the notification to site admins about new reported content
 *
 * @since 4.2
 */
class CreateReportedContentNotificationHandler extends NotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$admins = elgg_get_entities([
			'type' => 'user',
			'limit' => false,
			'batch' => true,
			'metadata_name_value_pairs' => [
				[
					'name' => 'admin',
					'value' => 'yes',
				],
			],
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					// no idea why an admin would report content and not fix it themselfs, but just in case
					return $qb->compare("{$main_alias}.guid", '!=', $this->event->getActorGUID(), ELGG_VALUE_GUID);
				}
			],
		]);
		
		$subscriptions = [];
		/* @var $admin \ElggUser */
		foreach ($admins as $admin) {
			$settings = $admin->getNotificationSettings('reportedcontent');
			$settings = array_keys(array_filter($settings));
			if (empty($settings)) {
				continue;
			}
			
			$subscriptions[$admin->guid] = $settings;
		}
		
		return $subscriptions;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function isConfigurableByUser(): bool {
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		return elgg_echo('reportedcontent:notifications:create:admin:subject', [$this->getEventEntity()?->getDisplayname()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return elgg_echo('reportedcontent:notifications:create:admin:summary', [$this->getEventEntity()?->getDisplayname()]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$reported = $this->getEventEntity();
		if (!$reported instanceof \ElggReportedContent) {
			$reported = null;
		}
		
		return elgg_echo('reportedcontent:notifications:create:admin:body', [
			$this->getEventActor()?->getDisplayName(),
			$reported?->description,
			$reported?->address,
			elgg_normalize_url('admin/administer_utilities/reportedcontent'),
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		return elgg_normalize_url('admin/administer_utilities/reportedcontent');
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function addMuteLink(): bool {
		return false;
	}
}
