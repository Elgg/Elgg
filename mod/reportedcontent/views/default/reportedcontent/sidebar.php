<?php
/**
 * List related reports
 */

use Elgg\Database\QueryBuilder;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggReportedContent) {
	return;
}

$related_entity = $entity->getRelatedEntity();

$addresses = [
	$entity->address,
	$related_entity instanceof \ElggEntity ? $related_entity->getURL() : null,
];

$addresses = array_unique(array_filter($addresses));

if (empty($addresses) && empty($related_entity)) {
	return;
}

$contents = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'offset' => 0,
	'limit' => (int) elgg_extract('limit', $vars, 5),
	'pagination' => false,
	'item_view' => 'object/reported_content/sidebar',
	'wheres' => [
		function (QueryBuilder $qb, $main_alias) use ($addresses, $related_entity) {
			$ors = [];
			
			if (!empty($addresses)) {
				$md_alias = $qb->joinMetadataTable($main_alias, 'guid', 'address', 'left');
				
				$ors[] = $qb->compare("{$md_alias}.value", 'IN', $addresses, ELGG_VALUE_STRING);
			}
			
			if ($related_entity instanceof \ElggEntity) {
				$relationship_alias = $qb->joinRelationshipTable($main_alias, 'guid', 'reportedcontent', true, 'left');
				
				$ors[] = $qb->compare("{$relationship_alias}.guid_two", '=', $related_entity->guid, ELGG_VALUE_GUID);
			}
			
			return $qb->merge($ors, 'OR');
		},
		function (QueryBuilder $qb, $main_alias) use ($entity) {
			return $qb->compare("{$main_alias}.guid", '<>', $entity->guid, ELGG_VALUE_GUID);
		},
	],
]);

if (empty($contents)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('reportedcontent:related_reports'), $contents);
