<?php
/**
 * User blog widget display view
 */

use Elgg\Database\Clauses\OrderByClause;

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$options = [
	'type' => 'object',
	'subtype' => 'discussion',
	'limit' => $num_display,
	'pagination' => false,
	'distinct' => false,
	'order_by' => new OrderByClause('e.last_action', 'desc'),
	'no_results' => elgg_echo('discussion:none'),
];

$owner = $widget->getOwnerEntity();
$url = elgg_generate_url('collection:object:discussion:all');

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
				$url = elgg_generate_url('collection:object:discussion:my_groups', ['username' => $owner->username]);
			}
		}
	} else {
		$options['owner_guid'] = $owner->guid;
		$url = elgg_generate_url('collection:object:discussion:owner', ['username' => $owner->username]);
	}
} elseif ($owner instanceof \ElggGroup) {
	$options['container_guid'] = $owner->guid;
	$url = elgg_generate_url('collection:object:discussion:group', ['guid' => $owner->guid]);
}

$options['widget_more'] = elgg_view_url($url, elgg_echo('more'));

echo elgg_list_entities($options);
