<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggGroup) {
	return;
}

$tools = elgg()->group_tools->group($entity)
	->filter(function (\Elgg\Groups\Tool $tool) use ($entity) {
		return $entity->isToolEnabled($tool->name);
	})
	->sort();

foreach ($tools as $tool) {
	if (elgg_view_exists("groups/profile/module/{$tool->name}")) {
		$params = $vars;
		$params['tool'] = $tool;

		echo elgg_view("groups/profile/module/{$tool->name}", $params);
	}
}
