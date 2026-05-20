<?php
/**
 * Elgg log rotator plugin settings.
 */

use Elgg\Database\Select;
use Elgg\SystemLog\SystemLog;

$plugin = elgg_extract('entity', $vars);
if (!$plugin instanceof ElggPlugin) {
	return;
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('logrotate:period'),
	'name' => 'params[period]',
	'options_values' => [
		'weekly' => elgg_echo('interval:weekly'),
		'monthly' => elgg_echo('interval:monthly'),
		'yearly' => elgg_echo('interval:yearly'),
		'never' => elgg_echo('never'),
	],
	'value' => $plugin->period,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('logrotate:retention'),
	'#help' => elgg_echo('logrotate:retention:help'),
	'name' => 'params[retention]',
	'value' => $plugin->retention,
	'min' => 0,
]);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('system_log:settings:enable_ip_logging'),
	'#help' => elgg_echo('system_log:settings:enable_ip_logging:help'),
	'name' => 'params[enable_ip_logging]',
	'value' => $plugin->enable_ip_logging,
]);

if (!$plugin->enable_ip_logging) {
	// check if we need to clean the current logs of IP addresses
	$select = Select::fromTable(SystemLog::TABLE_NAME);
	$select->select('count(*) as total')
		->where($select->compare('ip_address', '!=', '', ELGG_VALUE_STRING));
	
	$result = elgg()->db->getDataRow($select);
	if (!empty($result) && $result->total > 0) {
		elgg_register_menu_item('title', [
			'name' => 'clear_ip',
			'text' => elgg_echo('system_log:settings:clear_ip_addresses'),
			'href' => elgg_generate_action_url('system_log/clear_ip_addresses'),
			'confirm' => elgg_echo('deleteconfirm:plural'),
			'link_class' => ['elgg-button', 'elgg-button-delete'],
		]);
	}
}
