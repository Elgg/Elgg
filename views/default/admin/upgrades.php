<?php
/**
 * Lists pending upgrades
 */

$upgrades = elgg_get_entities_from_private_settings(array(
	'type' => 'object',
	'subtype' => 'elgg_upgrade',
	'private_setting_name' => 'is_completed',
	'private_setting_value' => false
));

if (!$upgrades) {
	echo elgg_echo('admin:upgrades:none');
} else {
	echo elgg_view_entity_list($upgrades);
}