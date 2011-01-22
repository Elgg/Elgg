<?php
/**
 * List a user's or group's pages
 *
 * @package ElggPages
 */

$guid = get_input('guid');

elgg_set_page_owner_guid($guid);
$owner = elgg_get_page_owner();
if (!$owner) {

}

// access check for closed groups
group_gatekeeper();

$title = elgg_echo('pages:owner', array($owner->name));

elgg_push_breadcrumb($title);

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'page_top',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => $limit,
	'full_view' => false,
));

$params = array(
	'filter_context' => 'mine',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar/navigation'),
);

if (elgg_instanceof($owner, 'group')) {
	$params['filter'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
