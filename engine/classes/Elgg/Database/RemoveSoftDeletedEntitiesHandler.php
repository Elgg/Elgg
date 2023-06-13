<?php

namespace Elgg\Database;

/**
 * Plugin hook callbacks
 */
class RemoveSoftDeletedEntitiesHandler {
	
	/**
	 * Called on a low (100) priority
	 *
	 * @param \Elgg\Event $event 'prevent', 'something'
	 *
	 * @return mixed
	 */
	public function __invoke(\Elgg\Event $event) {
        $entities = elgg_get_entities([
            'type' => 'object',
            'subtype' => false,
            'limit' => false,
            'batch' => true,
            'where' => [
                function(QueryBuilder $qb, $main_alias) {
                    return $qb->compare("{$main_alias}.soft_deleted", '=', true, ELGG_VALUE_BOOL);
                },
                function(QueryBuilder $qb, $main_alias) {
                    return $qb->compare("{$main_alias}.soft_deleted_time", '<', \Elgg\Values::normalizeTimestamp('-30 days'));
                }
            ],
        ]);
        foreach ($entities as $entity) {
            $entity->delete();
        }
	}

}