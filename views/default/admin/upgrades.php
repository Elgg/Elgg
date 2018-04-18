-<?php
/**
 * Lists pending upgrades
 */

elgg_require_js('core/js/upgrader');

$upgrades = _elgg_services()->upgrades->getPendingUpgrades();

if (empty($upgrades)) {
	echo elgg_echo('admin:upgrades:none');
	return;
}

elgg_register_menu_item('title', [
	'name' => 'run_upgrades',
	'text' => elgg_echo('admin:upgrades:run'),
	'id' => 'elgg-upgrades-run',
	'link_class' => 'elgg-button elgg-button-action',
]);
	
echo elgg_view_entity_list($upgrades);
