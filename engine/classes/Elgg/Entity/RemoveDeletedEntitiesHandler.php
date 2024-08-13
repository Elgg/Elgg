<?php

namespace Elgg\Entity;

use Elgg\Database\QueryBuilder;
use Elgg\Database\RelationshipsTable;

/**
 * Cleanup deleted entities from the database
 */
class RemoveDeletedEntitiesHandler {
	
	/**
	 * After a grace period remove deleted entities from the database
	 *
	 * @param \Elgg\Event $event 'cron', 'hourly'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		$retention = (int) elgg_get_config('trash_retention');
		if ($retention < 1) {
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($retention) {
			/* @var $entities \ElggBatch */
			$entities = elgg_get_entities([
				'limit' => false,
				'batch' => true,
				'batch_inc_offset' => false,
				'wheres' => [
					function(QueryBuilder $qb, $main_alias) {
						// only deleted items
						return $qb->compare("{$main_alias}.deleted", '=', 'yes', ELGG_VALUE_STRING);
					},
					function(QueryBuilder $qb, $main_alias) use ($retention) {
						// past the retention period
						return $qb->compare("{$main_alias}.time_deleted", '<', \Elgg\Values::normalizeTimestamp("-{$retention} days"), ELGG_VALUE_TIMESTAMP);
					},
					function(QueryBuilder $qb, $main_alias) {
						// get only the root deleted items (not the related/sub items)
						// the related items will be deleted with the root item
						$sub = $qb->subquery(RelationshipsTable::TABLE_NAME);
						$sub->select('guid_one')
							->where($qb->compare('relationship', '=', 'deleted_with', ELGG_VALUE_STRING));
						
						return $qb->compare("{$main_alias}.guid", 'not in', $sub->getSQL());
					}
				],
				'sort_by' => [
					'property' => 'time_deleted',
					'direction' => 'ASC',
				],
			]);
			
			$starttime = microtime(true);
			
			/* @var $entity \ElggEntity */
			foreach ($entities as $entity) {
				if ((microtime(true) - $starttime) > 300) {
					// limit the cleanup to 5 minutes
					break;
				}
				
				if (!$entity->delete(true, true)) {
					$entities->reportFailure();
				}
			}
		});
	}
}
