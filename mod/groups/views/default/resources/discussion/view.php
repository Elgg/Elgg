<?php

$guid = get_input('guid');

// We now have RSS on topics
global $autofeed;
$autofeed = true;

elgg_entity_gatekeeper($guid, 'object', 'groupforumtopic');

$topic = get_entity($guid);

$group = $topic->getContainerEntity();
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group:notfound'));
	forward();
}

elgg_load_js('elgg.discussion');

elgg_set_page_owner_guid($group->getGUID());

elgg_group_gatekeeper();

elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
elgg_push_breadcrumb($topic->title);

$params = array(
	'topic' => $topic,
	'show_add_form' => false,
);

$content = elgg_view_entity($topic, array('full_view' => true));
if ($topic->status == 'closed') {
	$content .= elgg_view('discussion/replies', $params);
	$content .= elgg_view('discussion/closed');
} elseif ($group->canWriteToContainer(0, 'object', 'groupforumtopic') || elgg_is_admin_logged_in()) {
	$params['show_add_form'] = true;
	$content .= elgg_view('discussion/replies', $params);
} else {
	$content .= elgg_view('discussion/replies', $params);
}

$params = array(
	'content' => $content,
	'title' => $topic->title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($topic->title, $body);
