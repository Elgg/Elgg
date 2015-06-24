<?php

$guid = get_input('guid');

// We now have RSS on topics
global $autofeed;
$autofeed = true;

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

$container = $topic->getContainerEntity();

elgg_load_js('elgg.discussion');

elgg_set_page_owner_guid($container->getGUID());

elgg_group_gatekeeper();

elgg_push_breadcrumb($container->getDisplayName(), "discussion/owner/$container->guid");
elgg_push_breadcrumb($topic->title);

$params = array(
	'topic' => $topic,
	'show_add_form' => false,
);

$content = elgg_view_entity($topic, array('full_view' => true));
if ($topic->status == 'closed') {
	$content .= elgg_view('discussion/replies', $params);
	$content .= elgg_view('discussion/closed');
} elseif (elgg_instanceof($container, 'group')) {
	// Allow only group members to reply to a discussion within a group
	if ($container->canWriteToContainer(0, 'object', 'discussion')) {
		$params['show_add_form'] = true;
	}
	$content .= elgg_view('discussion/replies', $params);
} else {
	$params['show_add_form'] = true;
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
