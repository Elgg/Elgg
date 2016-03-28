<?php

$guid = elgg_extract('guid', $vars);

elgg_register_rss_link();

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_push_breadcrumb($group->name);

groups_register_profile_buttons($group);

$content = elgg_view('groups/profile/layout', array('entity' => $group));
$sidebar = '';

if (elgg_group_gatekeeper(false)) {
	if (elgg_is_active_plugin('search')) {
		$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
	}
	$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));

	$subscribed = false;
	if (elgg_is_active_plugin('notifications')) {
		$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
		foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
			$relationship = check_entity_relationship(elgg_get_logged_in_user_guid(),
					'notify' . $method, $guid);

			if ($relationship) {
				$subscribed = true;
				break;
			}
		}
	}

	$sidebar .= elgg_view('groups/sidebar/my_status', array(
		'entity' => $group,
		'subscribed' => $subscribed
	));
}

$params = array(
	'content' => $content,
	'sidebar' => $sidebar,
	'title' => $group->name,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($group->name, $body);