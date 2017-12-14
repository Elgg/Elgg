<?php
/**
 * Lists pending upgrades
 */

elgg_require_js('core/js/upgrader');

$upgrades = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'elgg_upgrade',
	'limit' => false,
	'private_setting_name' => 'is_completed',
	'private_setting_value' => false,
]);

if ($upgrades) {
	foreach ($upgrades as $key => $upgrade) {
		// unsupported upgrade objects could still exist in the database and not be marked as completed
		// don't know what to do with them, so skipping if they are not supported by the output view
		if (!isset($upgrade->class)) {
			unset($upgrades[$key]);
		}
	}
}

if (!$upgrades) {
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
