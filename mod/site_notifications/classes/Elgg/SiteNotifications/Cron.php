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
	 * @return void
	 */
	public static function cleanupSiteNotificationsWithRemovedLinkedEntities(\Elgg\Hook $hook) {
		set_time_limit(0);
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
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
			]);
			
			/* @var $entity \ElggEntity */
			foreach ($batch as $entity) {
				if (!$entity->delete()) {
					$batch->reportFailure();
				}
				
				if (microtime(true) - $start_time > $max_runtime) {
					// max runtime expired
					break;
				}
			}
		});
	}
	
	/**
	 * Cleanup unread site notification
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void
	 */
	public static function cleanupUnreadSiteNotifications(\Elgg\Hook $hook) {
		set_time_limit(0);
		
		$days = (int) elgg_get_plugin_setting('unread_cleanup_days', 'site_notifications');
		if ($days < 1) {
			return;
		}
		
		echo elgg_echo('site_notifications:cron:unread_cleanup:start', [$days]) . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($days) {
			$count = 0;
			
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
			]);
			
			/* @var $entity \ElggEntity */
			foreach ($batch as $entity) {
				if (!$entity->delete()) {
					$batch->reportFailure();
					continue;
				}
				$count++;
			}
			
			return $count;
		});
		
		echo elgg_echo('site_notifications:cron:unread_cleanup:end', [$count]) . PHP_EOL;
	}
	
	/**
	 * Cleanup unread site notification
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void
	 */
	public static function cleanupReadSiteNotifications(\Elgg\Hook $hook) {
		set_time_limit(0);
		
		$days = (int) elgg_get_plugin_setting('read_cleanup_days', 'site_notifications');
		if ($days < 1) {
			return;
		}
		
		echo elgg_echo('site_notifications:cron:read_cleanup:start', [$days]) . PHP_EOL;
		
		$count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($days) {
			$count = 0;
			
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
			]);
			
			/* @var $entity \ElggEntity */
			foreach ($batch as $entity) {
				if (!$entity->delete()) {
					$batch->reportFailure();
					continue;
				}
				
				$count++;
			}
			
			return $count;
		});
		
		echo elgg_echo('site_notifications:cron:read_cleanup:end', [$count]) . PHP_EOL;
	}
}
