<?php
/**
 * Show a list of completed upgrades
 */

echo elgg_view('navigation/filter', [
	'filter_id' => 'admin/upgrades',
	'filter_value' => 'completed',
]);

$upgrades = _elgg_services()->upgrades->getCompletedUpgrades();

$offset = (int) get_input('offset', 0);
$limit = 5;
$count = count($upgrades);

$items = array_slice($upgrades, $offset, $limit);

echo elgg_view_entity_list($items, [
	'offset' => $offset,
	'limit' => $limit,
	'count' => $count,
	'pagination_behaviour' => 'ajax-replace',
	'no_results' => true,
]);
