<?php
/**
 * View a single page
 *
 * @package ElggPages
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object');

$page = get_entity($guid);
if (!pages_is_page($page)) {
	forward('', '404');
}

$container = $page->getContainerEntity();
if (!$container) {
	forward(REFERER);
}

$sidebar = elgg_view('pages/sidebar/navigation');
echo elgg_view_profile_page($page, [], [
	'sidebar' => $sidebar,
]);
