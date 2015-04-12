<?php

/**
 * Group edit form
 *
 * This view contains the group tool options provided by the different plugins
 *
 * @package ElggGroups
 */

$tools = elgg_get_config("group_tool_options");
if ($tools) {
	usort($tools, create_function('$a, $b', 'return strcmp($a->label, $b->label);'));
	
	foreach ($tools as $group_option) {
		$group_option_toggle_name = $group_option->name . "_enable";
		$value = elgg_extract($group_option_toggle_name, $vars);
		
		echo elgg_format_element('div', null, elgg_view('input/checkbox', array(
			'name' => $group_option_toggle_name,
			'value' => 'yes',
			'default' => 'no',
			'checked' => ($value === 'yes') ? true : false,
			'label' => $group_option->label
		)));
	}
}