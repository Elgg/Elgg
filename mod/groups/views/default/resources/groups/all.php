<?php

// all groups doesn't get link to self
elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('groups'));

if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
	elgg_register_title_button();
}

$selected_tab = get_input('filter', 'newest');

switch ($selected_tab) {
	case 'popular':
		$content = elgg_list_entities_from_relationship_count(array(
			'type' => 'group',
			'relationship' => 'member',
			'inverse_relationship' => false,
			'full_view' => false,
			'no_results' => elgg_echo('groups:none'),
		));
		break;
	case 'discussion':
		// Get only the discussions that have been created inside a group
		$dbprefix = elgg_get_config('dbprefix');
		$content = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'discussion',
			'order_by' => 'e.last_action desc',
			'limit' => 40,
			'full_view' => false,
			'no_results' => elgg_echo('discussion:none'),
			'joins' => array("JOIN {$dbprefix}entities ce ON ce.guid = e.container_guid"),
			'wheres' => array('ce.type = "group"'),
			'distinct' => false,
			'preload_containers' => true,
		));
		break;
	case 'featured':
		$content = elgg_list_entities_from_metadata(array(
			'type' => 'group',
			'metadata_name' => 'featured_group',
			'metadata_value' => 'yes',
			'full_view' => false,
		));
		if (!$content) {
			$content = elgg_echo('groups:nofeatured');
		}
		break;
	case 'alpha':
		$dbprefix = elgg_get_config('dbprefix');
		$content = elgg_list_entities(array(
			'type' => 'group',
			'joins' => ["JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"],
			'order_by' => 'ge.name',
			'full_view' => false,
			'no_results' => elgg_echo('groups:none'),
			'distinct' => false,
		));
		break;
	case 'newest':
	default:
		$content = elgg_list_entities(array(
			'type' => 'group',
			'full_view' => false,
			'no_results' => elgg_echo('groups:none'),
			'distinct' => false,
		));
		break;
}

$filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));

$sidebar = elgg_view('groups/sidebar/find');
$sidebar .= elgg_view('groups/sidebar/featured');

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'filter' => $filter,
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page(elgg_echo('groups:all'), $body);