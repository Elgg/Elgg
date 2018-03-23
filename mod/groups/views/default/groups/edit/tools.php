<?php

/**
 * Group edit form
 *
 * This view contains the group tool options provided by the different plugins
 *
 * @package ElggGroups
 */

$fields = [];

$tools = elgg_get_group_tool_options(elgg_extract('entity', $vars));
if (!empty($tools)) {
	usort($tools, function ($a, $b) {
		return strcmp($a->label, $b->label);
	});

	foreach ($tools as $group_option) {
		$group_option_toggle_name = $group_option->name . "_enable";
		$value = elgg_extract($group_option_toggle_name, $vars);

		$fields[] = [
			'#type' => 'checkbox',
			'#label' => $group_option->label,
			'name' => $group_option_toggle_name,
			'value' => 'yes',
			'default' => 'no',
			'switch' => true,
			'checked' => ($value === 'yes') ? true : false,
		];
	}
}

$fields = elgg_trigger_plugin_hook('fields:tools', 'group', $vars, $fields);

foreach ($fields as $field) {
	echo elgg_view_field($field);
}