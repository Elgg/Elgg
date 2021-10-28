<?php

namespace Elgg\SiteNotifications;

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;

/**
 * Cron handler
 *
 * @since 4.0
 * @internal
 */
class Cron {
	
	protected const CLEANUP_MAX_DURATION = [
		'fifteenmin' => 300, // 5 minutes
		'halfhour' => 600, // 10 minutes
		'hourly' => 900, // 15 minutes
		'daily' => 1800, // 30 minutes
		'weekly' => 3600, // 1 hour
	];
	
	/**
	 * Cleanup site notification for which the linked entity has been removed
	 *
	 * @param \Elgg\Hook $hook 'cron', 'fiveminute'
	 *
	 * @return string
	 */
	public static function cleanupSiteNotificationsWithRemovedLinkedEntities(\Elgg\Hook $hook) {
		set_time_limit(0);
		
		$result = $hook->getValue();
		$result .= elgg_echo('site_notifications:cron:linked_cleanup:start') . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() {
			$count = 0;
			$max_runtime = 120; // 2 minutes
			$start_time = microtime(true);
			
			/* @var $batch \ElggBatch */
			$batch = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'site_notification',
				'limit' => false,
				'wheres' => [
					function (QueryBuilder $qb, $main_alias) {
						$md = $qb->joinMetadataTable($main_alias, 'guid', 'linked_entity_guid', 'inner', 'lmd');
						
						$sub = $qb->subquery('entities');
						$sub->select('guid');
						
						return $qb->compare("{$md}.value", 'not in', $sub->getSQL());
					},
				],
				'batch' => true,
				'batch_inc_offset' => false,
				'batch_size' => 100,
			]);
			
			/* @var $entity \ElggEntity */
			foreach ($batch as $entity) {
				if (!$entity->delete()) {
					$batch->reportFailure();
					continue;
				}
				
				$count++;
				
				if ((microtime(true) - $start_time) > $max_runtime) {
					// max runtime expired
					break;
				}
			}
			
			return $count;
		});
		
		$result .= elgg_echo('site_notifications:cron:linked_cleanup:end', [$count]) . PHP_EOL;
		return $result;
	}
	
	/**
	 * Cleanup unread site notification
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void|string
	 */
	public static function cleanupUnreadSiteNotifications(\Elgg\Hook $hook) {
		set_time_limit(0);
		
		$days = (int) elgg_get_plugin_setting('unread_cleanup_days', 'site_notifications');
		$interval = elgg_get_plugin_setting('unread_cleanup_interval', 'site_notifications');
		if ($days < 1 || $interval !== $hook->getType()) {
			return;
		}
		
		$max_runtime = static::CLEANUP_MAX_DURATION[$hook->getType()];
		
		$result = $hook->getValue();
		$result .= elgg_echo('site_notifications:cron:unread_cleanup:start', [$days]) . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($days, $max_runtime) {
			$count = 0;
			$start_time = microtime(true);
			
			/* @var $batch \ElggBatch */
			$batch = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'site_notification',
				'limit' => false,
				'metadata_name_value_pairs' => [
					'read' => false,
				],
				'created_before' => "-{$days} days",
				'batch' => true,
				'batch_inc_offset' => false,
				'batch_size' => 100,
				'order_by' => [
					new OrderByClause('e.time_created', 'ASC'), // oldest first
				],
			]);
			
			/* @var $entity \ElggEntity */
			foreach ($batch as $entity) {
				if (!$entity->delete()) {
					$batch->reportFailure();
					continue;
				}
				
				$count++;
				
				if ((microtime(true) - $start_time) > $max_runtime) {
					// max runtime expired
					break;
				}
			}
			
			return $count;
		});
		
		$result .= elgg_echo('site_notifications:cron:unread_cleanup:end', [$count]) . PHP_EOL;
		return $result;
	}
	
	/**
	 * Cleanup unread site notification
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void|string
	 */
	public static function cleanupReadSiteNotifications(\Elgg\Hook $hook) {
		set_time_limit(0);
		
		$days = (int) elgg_get_plugin_setting('read_cleanup_days', 'site_notifications');
		$interval = elgg_get_plugin_setting('read_cleanup_interval', 'site_notifications');
		if ($days < 1 || $interval !== $hook->getType()) {
			return;
		}
		
		$max_runtime = static::CLEANUP_MAX_DURATION[$hook->getType()];
		
		$result = $hook->getValue();
		$result .= elgg_echo('site_notifications:cron:read_cleanup:start', [$days]) . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($days, $max_runtime) {
			$count = 0;
			$start_time = microtime(true);
			
			/* @var $batch \ElggBatch */
			$batch = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'site_notification',
				'limit' => false,
				'metadata_name_value_pairs' => [
					'read' => true,
				],
				'created_before' => "-{$days} days",
				'batch' => true,
				'batch_inc_offset' => false,
				'batch_size' => 100,
				'order_by' => [
					new OrderByClause('e.time_created', 'ASC'), // oldest first
				],
			]);
			
			/* @var $entity \ElggEntity */
			foreach ($batch as $entity) {
				if (!$entity->delete()) {
					$batch->reportFailure();
					continue;
				}
				
				$count++;
				
				if ((microtime(true) - $start_time) > $max_runtime) {
					// max runtime expired
					break;
				}
			}
			
			return $count;
		});
		
		$result .= elgg_echo('site_notifications:cron:read_cleanup:end', [$count]) . PHP_EOL;
		return $result;
	}
}
