<?php

/**
 * Group edit form
 *
 * This view contains the group tool options provided by the different plugins
 *
 * @package ElggGroups
 */

$tools = groups_get_group_tool_options(elgg_extract('entity', $vars));
if (empty($tools)) {
	return;
}

usort($tools, function($a, $b) {
	return strcmp($a->label, $b->label);
});

foreach ($tools as $group_option) {
	$group_option_toggle_name = $group_option->name . "_enable";
	$value = elgg_extract($group_option_toggle_name, $vars);

	echo elgg_format_element([
		'#tag_name' => 'div',
		'#text' => elgg_view('input/checkbox', [
			'name' => $group_option_toggle_name,
			'value' => 'yes',
			'default' => 'no',
			'checked' => ($value === 'yes') ? true : false,
			'label' => $group_option->label,
		]),
	]);
}
