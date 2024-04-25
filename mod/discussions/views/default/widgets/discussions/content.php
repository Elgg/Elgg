<?php
/**
 * User blog widget display view
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'sort_by' => [
		'property' => 'last_action',
		'direction' => 'DESC',
	],
	'no_results' => elgg_echo('discussion:none'),
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('more'))
];

$owner = $widget->getOwnerEntity();
if ($owner instanceof \ElggUser) {
	if ($widget->context === 'dashboard') {
		if (elgg_get_plugin_setting('enable_global_discussions', 'discussions') !== 1) {
			$group_guids = $owner->getGroups([
				'limit' => false,
				'callback' => function($row) {
					return (int) $row->guid;
				},
			]);
			
			if (!empty($group_guids)) {
				$options['container_guids'] = $group_guids;
			}
		}
	} else {
		$options['owner_guid'] = $owner->guid;
	}
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $owner->guid;
}

echo elgg_list_entities($options);
