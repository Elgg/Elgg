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

elgg_import_esm('admin/upgrades');

elgg_register_menu_item('title', [
	'name' => 'run_upgrades',
	'icon' => 'play',
	'text' => elgg_echo('admin:upgrades:run'),
	'id' => 'elgg-upgrades-run',
	'link_class' => 'elgg-button elgg-button-action',
]);

// make sure to use the same options as in \Elgg\UpgradeService::executeUpgrade()
echo elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function () use ($upgrades) {
	return elgg_view_entity_list($upgrades, [
		'limit' => false,
		'pagination' => false,
		'no_results' => elgg_echo('admin:upgrades:none'),
	]);
});
