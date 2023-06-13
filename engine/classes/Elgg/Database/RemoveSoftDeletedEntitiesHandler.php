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
        $entities = elgg_call(ELGG_SHOW_SOFT_DELETED_ENTITIES, function (){
            return elgg_get_entities([
                'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
                'limit' => false,
                'batch' => true,
                'wheres' => [
                    function(QueryBuilder $qb, $main_alias) {
                        return $qb->compare("{$main_alias}.soft_deleted", '=', 'yes', ELGG_VALUE_STRING);
                    },
                    function(QueryBuilder $qb, $main_alias) {
                        return $qb->compare("{$main_alias}.soft_deleted_time", '<', \Elgg\Values::normalizeTimestamp('-30 days'));
                    }
                ],
            ]);
        });

        foreach ($entities as $entity) {
            $entity->delete();
        }

	}

}