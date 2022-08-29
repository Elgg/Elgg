<?php

namespace Elgg\SiteNotifications;

use Elgg\Database\Delete;
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
				'order_by' => false, // no ordering because of performance on large databases, this will remove oldest notifications first
				'callback' => function($row) {
					return (int) $row->guid;
				},
			]);
			
			$guids = [];
			foreach ($batch as $guid) {
				$guids[] = $guid;
				if (count($guids) < 100) {
					continue;
				}
				
				$count += self::removeSiteNotifications($guids);
				$guids = [];
				
				if ((microtime(true) - $start_time) > $max_runtime) {
					// max runtime expired
					break;
				}
			}
			
			if (!empty($guids)) {
				$count += self::removeSiteNotifications($guids);
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
				'order_by' => false, // no ordering because of performance on large databases, this will remove oldest notifications first
				'callback' => function($row) {
					return (int) $row->guid;
				},
			]);
			
			$guids = [];
			foreach ($batch as $guid) {
				$guids[] = $guid;
				if (count($guids) < 100) {
					continue;
				}
				
				$count += self::removeSiteNotifications($guids);
				$guids = [];
				
				if ((microtime(true) - $start_time) > $max_runtime) {
					// max runtime expired
					break;
				}
			}
			
			if (!empty($guids)) {
				$count += self::removeSiteNotifications($guids);
			}
			
			return $count;
		});
		
		$result .= elgg_echo('site_notifications:cron:read_cleanup:end', [$count]) . PHP_EOL;
		return $result;
	}
	
	/**
	 * Remove the site notification without the use of the Elgg event system by using direct DB queries
	 *
	 * @param array $guids the site notification guids to remove
	 *
	 * @return int
	 */
	protected static function removeSiteNotifications(array $guids): int {
		// cleanup filestore
		foreach ($guids as $guid) {
			$dir = new \Elgg\EntityDirLocator($guid);
			$file_path = elgg_get_data_path() . $dir->getPath();
			elgg_delete_directory($file_path);
		}
		
		// access collection membership
		$acl_membership = Delete::fromTable('access_collection_membership');
		$acl_membership->where($acl_membership->compare('user_guid', 'in', $guids, ELGG_VALUE_GUID));
		
		elgg()->db->deleteData($acl_membership);
		
		// access collections
		$acl = Delete::fromTable('access_collections');
		$acl->where($acl->compare('owner_guid', 'in', $guids, ELGG_VALUE_GUID));
		
		elgg()->db->deleteData($acl);
		
		// annotations
		$annotations = Delete::fromTable('annotations');
		$annotations->where($annotations->merge([
			$annotations->compare('entity_guid', 'in', $guids, ELGG_VALUE_GUID),
			$annotations->compare('owner_guid', 'in', $guids, ELGG_VALUE_GUID),
		], 'OR'));
		
		elgg()->db->deleteData($annotations);
		
		// delayed email queue
		$delayed = Delete::fromTable('delayed_email_queue');
		$delayed->where($delayed->compare('recipient_guid', 'in', $guids, ELGG_VALUE_GUID));
		
		elgg()->db->deleteData($delayed);
		
		// entity relationships
		$rels = Delete::fromTable('entity_relationships');
		$rels->where($rels->merge([
			$rels->compare('guid_one', 'in', $guids, ELGG_VALUE_GUID),
			$rels->compare('guid_two', 'in', $guids, ELGG_VALUE_GUID),
		], 'OR'));
		
		elgg()->db->deleteData($rels);
		
		// metadata
		$md = Delete::fromTable('metadata');
		$md->where($md->compare('entity_guid', 'in', $guids, ELGG_VALUE_GUID));
		
		elgg()->db->deleteData($md);
		
		// river
		$river = Delete::fromTable('river');
		$river->where($river->merge([
			$river->compare('subject_guid', 'in', $guids, ELGG_VALUE_GUID),
			$river->compare('object_guid', 'in', $guids, ELGG_VALUE_GUID),
			$river->compare('target_guid', 'in', $guids, ELGG_VALUE_GUID),
		], 'OR'));
		
		elgg()->db->deleteData($river);
		
		// users api sessions
		$users_api = Delete::fromTable('users_apisessions');
		$users_api->where($users_api->compare('user_guid', 'in', $guids, ELGG_VALUE_GUID));
		
		elgg()->db->deleteData($users_api);
		
		// users remember me cookies
		$cookies = Delete::fromTable('users_remember_me_cookies');
		$cookies->where($cookies->compare('guid', 'in', $guids, ELGG_VALUE_GUID));
		
		elgg()->db->deleteData($cookies);
		
		// entities
		$entities = Delete::fromTable('entities');
		$entities->where($entities->merge([
			$entities->compare('guid', 'in', $guids, ELGG_VALUE_GUID),
			$entities->compare('owner_guid', 'in', $guids, ELGG_VALUE_GUID),
			$entities->compare('container_guid', 'in', $guids, ELGG_VALUE_GUID),
		], 'OR'));
		
		return elgg()->db->deleteData($entities);
	}
}
