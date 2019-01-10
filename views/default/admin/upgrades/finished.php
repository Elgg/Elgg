<?php
/**
 * Show a list of completed upgrades
 */

echo elgg_view('navigation/filter', [
	'filter_id' => 'admin/upgrades',
	'filter_value' => 'completed',
]);

$upgrades = _elgg_services()->upgrades->getCompletedUpgrades();
echo elgg_view_entity_list($upgrades, [
	'limit' => false,
	'pagination' => false,
	'no_results' => true,
]);
