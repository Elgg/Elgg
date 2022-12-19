<?php
/**
 * Group edit form
 *
 * This view contains the group tool options provided by the different plugins
 */

$entity = elgg_extract('entity', $vars);

if ($entity instanceof \ElggGroup) {
	$tools = elgg()->group_tools->group($entity);
} else {
	$tools = elgg()->group_tools->all();
}

/* @var $tools \Elgg\Groups\Tool[] */
$tools = $tools->sort(function (\Elgg\Groups\Tool $a, \Elgg\Groups\Tool $b) {
	return strcmp($a->getLabel(), $b->getLabel());
})->all();

if (empty($tools)) {
	return;
}

foreach ($tools as $tool) {
	echo elgg_view('groups/edit/tool', [
		'entity' => $entity,
		'tool' => $tool,
		'value' => elgg_extract($tool->mapMetadataName(), $vars),
	]);
}
