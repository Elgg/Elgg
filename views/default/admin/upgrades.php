<?php
/**
 * Lists pending upgrades
 */

elgg_require_js('elgg/upgrader');

elgg_register_menu_item('title', [
	'name' => 'run_upgrades',
	'text' => elgg_echo('admin:upgrades:run'),
	'id' => 'elgg-upgrades-run',
	'link_class' => 'elgg-button elgg-button-action hidden',
]);

$upgrades = elgg_get_entities_from_private_settings([
	'type' => 'object',
	'subtype' => 'elgg_upgrade',
	'private_setting_name' => 'is_completed',
	'private_setting_value' => false
]);

if (!$upgrades) {
	echo elgg_echo('admin:upgrades:none');
} else {
	echo elgg_view_entity_list($upgrades);
}
