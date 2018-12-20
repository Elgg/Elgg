<?php
/**
 * Lists pending upgrades
 */

echo elgg_view('navigation/filter', [
	'filter_id' => 'admin/upgrades',
	'filter_value' => 'pending',
]);

$upgrades = _elgg_services()->upgrades->getPendingUpgrades();
if (empty($upgrades)) {
	echo elgg_echo('admin:upgrades:none');
	return;
}

elgg_require_js('core/js/upgrader');

elgg_register_menu_item('title', [
	'name' => 'run_upgrades',
	'icon' => 'play',
	'text' => elgg_echo('admin:upgrades:run'),
	'id' => 'elgg-upgrades-run',
	'link_class' => 'elgg-button elgg-button-action',
]);
	
echo elgg_view_entity_list($upgrades, [
	'limit' => false,
	'pagination' => false,
	'no_results' => elgg_echo('admin:upgrades:none'),
]);
