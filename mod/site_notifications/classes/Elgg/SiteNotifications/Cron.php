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
}
