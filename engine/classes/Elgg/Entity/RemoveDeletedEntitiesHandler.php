<?php

namespace Elgg\Entity;

use Elgg\Database\QueryBuilder;

/**
 * Cleanup deleted entities from the database
 */
class RemoveDeletedEntitiesHandler {
	
	/**
	 * After a grace period remove deleted entities from the database
	 *
	 * @param \Elgg\Event $event 'cron', 'daily'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		elgg_call(ELGG_SHOW_DELETED_ENTITIES | ELGG_IGNORE_ACCESS, function() {
			/* @var $entities \ElggBatch */
			$entities = elgg_get_entities([
				'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
				'wheres' => [
					function(QueryBuilder $qb, $main_alias) {
						return $qb->compare("{$main_alias}.soft_deleted", '=', 'yes', ELGG_VALUE_STRING);
					},
					function(QueryBuilder $qb, $main_alias) {
						$grace_period = (int) elgg_get_config('bin_cleanup_grace_period');
						return $qb->compare("{$main_alias}.time_soft_deleted", '<', \Elgg\Values::normalizeTimestamp("-{$grace_period} days"), ELGG_VALUE_TIMESTAMP);
					},
				],
			]);
			
			// this could take a while
			set_time_limit(0);
			
			/* @var $entity \ElggEntity */
			foreach ($entities as $entity) {
				if (!$entity->delete()) {
					$entities->reportFailure();
				}
			}
		});
	}
}
