<?php

namespace Elgg\SiteNotifications;

use Elgg\Database\QueryBuilder;

/**
 * Cron handler
 *
 * @since 4.0
 * @internal
 */
class Cron {
	
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
		if ($days < 1) {
			return;
		}
		
		$result = $hook->getValue();
		$result .= elgg_echo('site_notifications:cron:unread_cleanup:start', [$days]) . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($days) {
			$count = 0;
			$max_runtime = 1800; // 30 minutes
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
		if ($days < 1) {
			return;
		}
		
		$result = $hook->getValue();
		$result .= elgg_echo('site_notifications:cron:read_cleanup:start', [$days]) . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($days) {
			$count = 0;
			$max_runtime = 1800; // 30 minutes
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
