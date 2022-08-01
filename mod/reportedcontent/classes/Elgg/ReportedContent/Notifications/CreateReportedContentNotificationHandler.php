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
	 * {@inheritDoc}
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
	 * {@inheritDoc}
	 */
	public static function isConfigurableByUser(): bool {
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		/* @var $report \ElggReportedContent */
		$report = $this->event->getObject();
		
		return elgg_echo('reportedcontent:notifications:create:admin:subject', [$report->getDisplayname()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		/* @var $report \ElggReportedContent */
		$report = $this->event->getObject();
		
		return elgg_echo('reportedcontent:notifications:create:admin:summary', [$report->getDisplayname()], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		/* @var $reported \ElggReportedContent */
		$reported = $this->event->getObject();
		$actor = $this->event->getActor();
		
		return elgg_echo('reportedcontent:notifications:create:admin:body', [
			$actor->getDisplayName(),
			$reported->description,
			$reported->address,
			elgg_normalize_url('admin/administer_utilities/reportedcontent'),
		], $recipient->getLanguage());
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		return elgg_normalize_url('admin/administer_utilities/reportedcontent');
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function addMuteLink(): bool {
		return false;
	}
}
