<?php

/**
 * Group edit form
 *
 * This view contains the group tool options provided by the different plugins
 *
 * @package ElggGroups
 */

$entity = elgg_extract('entity', $vars);

if ($entity instanceof ElggGroup) {
	$tools = elgg()->group_tools->group($entity);
} else {
	$tools = elgg()->group_tools->all();
}

$tools = $tools->sort()->all();
/* @var $tools \Elgg\Groups\Tool[] */

if (empty($tools)) {
	return;
}

foreach ($tools as $tool) {
	$prop_name = $tool->mapMetadataName();
	$value = elgg_extract($prop_name, $vars);

	echo elgg_view_field([
		'#type' => 'checkbox',
		'#label' => $tool->label,
		'name' => $prop_name,
		'value' => 'yes',
		'default' => 'no',
		'switch' => true,
		'checked' => ($value === 'yes') ? true : false,
	]);
}
