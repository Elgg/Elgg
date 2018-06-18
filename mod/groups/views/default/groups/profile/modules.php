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

$legacy = elgg_view("groups/tool_latest", $vars);
if ($legacy) {
	elgg_deprecated_notice(
		"Extending 'groups/tool_latest' to render group profile modules is deprecated. " .
		"Instead add a view corresponding to your group tool name in 'groups/profile/module/<tool_name>'",
		'3.0'
	);
}
